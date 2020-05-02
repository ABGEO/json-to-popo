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
     * @return mixed New filled with JSON content object of $class class.
     */
    public function composeObject(string $json, string $class, int $mode = self::MODE_STRICT)
    {
        $mainObject  = null;
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
        $mainObject = new $class();

        foreach (get_object_vars($jsonDecoded) as $property => $value) {
            $this->fillObject(Normalizer::camelize($property), $value, $mainObject);
        }

        return $mainObject;
    }

    /**
     * Recursively fill a given property
     * of a given object with a given value.
     *
     * @param string $property Object property to fill.
     * @param mixed  $value    Value to fill object property with.
     * @param mixed  $object   Object to fill.
     */
    private function fillObject(string $property, $value, $object)
    {
        $reflectionProperty = null;
        $propertySetter     = null;
        $propertyType       = null;
        $propertyTypeName   = null;
        $_value             = null;
        $_object            = null;
        $class              = get_class($object);

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

        $propertySetter = 'set' . ucfirst($property);
        if (!method_exists($object, $propertySetter)) {
            throw new RuntimeException("Class '{$class}' does not have a method '{$propertySetter}'");
        }

        if (is_object($value)) {
            if (!$propertyType = $reflectionProperty->getType()) {
                throw new RuntimeException(
                    "Type of Property '{$class}::\${$property}' is undefined!"
                );
            }

            $propertyTypeName = $propertyType->getName();

            if ('array' === $propertyTypeName) {
                $_value = [];
                $this->fillArray($_value, $value);
                $value = $_value;
            } else {
                $_object = new $propertyTypeName();
                foreach (get_object_vars($value) as $_property => $_value) {
                    $this->fillObject(Normalizer::camelize($_property), $_value, $_object);
                }
                $value = $_object;
            }
        }

        call_user_func_array([$object, $propertySetter], [$value]);
    }

    /**
     * Recursively fill a given array with a given Std Class.
     *
     * @param array     $array Reference to Array to fill.
     * @param stdClass $value Std Class Value to fill array with.
     */
    private function fillArray(array &$array, stdClass $value)
    {
        foreach (get_object_vars($value) as $_key => $_value) {
            if (is_object($_value)) {
                $array[$_key] = [];
                $this->fillArray($array[$_key], $_value);
            } else {
                $array[$_key] = $_value;
            }
        }
    }
}
