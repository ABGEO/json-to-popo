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

/**
 * Compose Plain Old PHP Object from JSON content.
 *
 * @author Temuri Takalandze <takalandzet@gmail.com>
 */
class Composer
{
    /**
     * Additional class mapping.
     *
     * @var array JSON.
     */
    private $classMapping = [];

    /**
     * Map target JSON Property to POPO Class.
     *
     * @param string $property JSON Property.
     * @param string $class    Property Class (Use MyClass::class).
     *
     * @return $this
     */
    public function addClassMapping(string $property, string $class)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class \"$class\" not found!");
        }

        $this->classMapping[Normalizer::classify($property)] = $class;

        return $this;
    }

    /**
     * Compose a new object of this given class
     * and fill it with the given JSON content.
     *
     * @param string $json  JSON content to fill the new object.
     * @param string $class Class to create a new object from.
     *
     * @return mixed New filled with JSON content object of $class class.
     */
    public function composeObject(string $json, string $class)
    {
        $mainObject = new $class();
        $objectVariables = get_object_vars(json_decode($json));

        foreach ($objectVariables as $property => $value) {
            $this->fillObject(Normalizer::classify($property), $value, $mainObject);
        }

        return $mainObject;
    }

    /**
     * Recursively fill a given property
     * of a given object with a given value.
     *
     * @param string $property
     * @param $value
     * @param $object
     */
    private function fillObject(string $property, $value, $object)
    {
        $propertySetter = "set{$property}";
        if (!method_exists($object, $propertySetter)) {
            throw new \RuntimeException("Method \"$propertySetter\" not found in target object!");
        }

        if (is_object($value)) {
            if (!isset($this->classMapping[$property])) {
                throw new \RuntimeException(
                    'Class mapping not found for property "' . Normalizer::camelize($property) . '"!'
                );
            }

            $_object = new $this->classMapping[$property]();
            foreach (get_object_vars($value) as $_property => $_value) {
                $this->fillObject(Normalizer::classify($_property), $_value, $_object);
            }
            $value = $_object;
        }

        call_user_func_array([$object, $propertySetter], [$value]);
    }
}
