<?php

/*
 * This file is part of the abgeo/json-to-popo.
 *
 * Copyright (C) 2020 Temuri Takalandze <takalandzet@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ABGEO\POPO;

use ABGEO\POPO\Util\AnnotationParser;
use ABGEO\POPO\Util\Normalizer;
use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;
use stdClass;

use function call_user_func_array;
use function class_exists;
use function get_object_vars;
use function in_array;
use function is_object;
use function method_exists;
use function json_decode;
use function json_last_error;
use function substr;
use function ucfirst;
use function get_class;

/**
 * Compose Plain Old PHP Object from JSON content.
 *
 * @author Temuri Takalandze <takalandzet@gmail.com>
 */
class Composer
{
    /**
     * This mode means that undefined JSON fields
     * in target POPO will be IGNORED.
     */
    public const MODE_NON_STRICT = 0;

    /**
     * This mode means that all JSON fields
     * MUST be represented in target POPO.
     */
    public const MODE_STRICT = 1;

    /**
     * Available modes.
     *
     * @var array
     */
    private array $availableModes = [
        self::MODE_NON_STRICT,
        self::MODE_STRICT,
    ];

    /**
     * Current mode.
     *
     * @var int
     */
    private int $mode = self::MODE_STRICT;

    /**
     * Compose a new object of this given class
     * and fill it with the given JSON content.
     *
     * @param string $json  JSON content to fill the new object.
     * @param string $class Class to create a new object from.
     * @param int    $mode  Compose mode:
     *                          self::MODE_NON_STRICT - Undefined JSON fields in target POPO will be IGNORED;
     *                          self::MODE_STRICT     - All JSON fields MUST be represented in target POPO;
     *
     * @return object New filled with JSON content object of $class class.
     */
    public function composeObject(string $json, string $class, int $mode = self::MODE_STRICT): object
    {
        $jsonDecoded = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("The JSON content is invalid!");
        }

        if (!class_exists($class)) {
            throw new InvalidArgumentException("Class '$class' not found!");
        }

        if (!in_array($mode, $this->availableModes)) {
            throw new InvalidArgumentException("Invalid compose mode '$mode'!");
        }

        $this->mode = $mode;

        return $this->composeObjectFromStdClass($class, $jsonDecoded);
    }

    /**
     * Create new object of given class
     * and fill it with given Std Class items.
     *
     * @param string   $class    Class for create new object from.
     * @param stdClass $stdClass The Std class whose elements will fill the new object.
     *
     * @return object New filled with Std Class items object of $class class.
     */
    public function composeObjectFromStdClass(string $class, stdClass $stdClass): object
    {
        $return = new $class();

        foreach (get_object_vars($stdClass) as $property => $value) {
            $this->doFillObject(Normalizer::camelize($property), $value, $return);
        }

        return $return;
    }

    /**
     * Recursively fill a given array with a given Std Class.
     *
     * @param stdClass $stdClass Std Class Value to fill array with.
     *
     * @return array New filled with Std Class items array.
     */
    private function composeKeyedArrayFromStdClass(stdClass $stdClass): array
    {
        $return = [];

        foreach (get_object_vars($stdClass) as $key => $value) {
            $return[$key] = is_object($value) ? $this->composeKeyedArrayFromStdClass($value) : $value;
        }

        return $return;
    }

    /**
     * Validate given property of the given object and call object filler.
     *
     * @param string $property  Object property to fill.
     * @param mixed  $value     Value to fill object property with.
     * @param mixed  $object    Object to fill.
     */
    private function doFillObject(string $property, $value, $object): void
    {
        $class              = get_class($object);
        $reflectionProperty = null;
        $propertyType       = null;

        try {
            $reflectionProperty = new ReflectionProperty($class, $property);
        } catch (ReflectionException $e) {
            if (
                "Property {$class}::\${$property} does not exist" === $e->getMessage()
                && self::MODE_NON_STRICT === $this->mode
            ) {
                return;
            } else {
                throw new RuntimeException($e->getMessage());
            }
        }

        if (!$propertyType = AnnotationParser::getType($reflectionProperty->getDocComment())) {
            throw new RuntimeException(
                "Type of Property '{$class}::\${$property}' is undefined!"
            );
        }

        $this->fillObject($property, $propertyType, $value, $object);
    }

    /**
     * Fill a given property of a given object
     * with a given value based on data type.
     *
     * @param string $property     Object property to fill.
     * @param string $propertyType Type of object property.
     * @param mixed  $value        Value to fill object property with.
     * @param mixed  $object       Object to fill.
     */
    private function fillObject(string $property, string $propertyType, $value, $object): void
    {
        $setter = 'set' . ucfirst($property);
        if (!method_exists($object, $setter)) {
            throw new RuntimeException(
                'Property \'' . get_class($object) . '::$' . $property . '\' does not have a setter!'
            );
        }

        if (is_object($value)) {
            $value = $this->getDataForObject($propertyType, $value);
        } elseif (is_array($value)) {
            $value = $this->getDataForArray($propertyType, $value);
        }

        call_user_func_array([$object, $setter], [$value]);
    }

    /**
     * Compose data from Std Class based on Property Type.
     *
     * @param string $propertyType Host property type.
     * @param stdClass $value      Std Class value.
     *
     * @return array|object Composed data.
     */
    private function getDataForObject(string $propertyType, stdClass $value)
    {
        if (!empty(AnnotationParser::getArrayElementsType($propertyType))) {
            return $this->composeKeyedArrayFromStdClass($value);
        }

        return $this->composeObjectFromStdClass($propertyType, $value);
    }

    /**
     * Compose data from array based on Property Type.
     *
     * @param string $propertyType Host property type.
     * @param array $value         Array value.
     *
     * @return array Composed data.
     */
    private function getDataForArray(string $propertyType, array $value): array
    {
        $propertyType = AnnotationParser::getArrayElementsType($propertyType);
        $returnData = [];

        if ($propertyType) {
            foreach ($value as $item) {
                if (is_object($item)) {
                    $returnData[] = $this->composeObjectFromStdClass($propertyType, $item);
                } else {
                    $returnData[] = $item;
                }
            }

            return $returnData;
        }

        return $value;
    }
}
