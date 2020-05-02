<?php

/*
 * This file is part of the abgeo/json-to-popo.
 *
 * Copyright (C) 2020 Temuri Takalandze <takalandzet@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ABGEO\POPO\Test\Meta\Classes;

class Class1
{
    /**
     * @var int
     */
    private $int;

    /**
     * @var string
     */
    private $string;

    /**
     * @var float
     */
    private $float;

    /**
     * @var bool
     */
    private $bool;

    /**
     * @var array
     */
    private array $array;

    public function getInt()
    {
        return $this->int;
    }

    public function setInt($int): void
    {
        $this->int = $int;
    }

    public function getString()
    {
        return $this->string;
    }

    public function setString($string): void
    {
        $this->string = $string;
    }

    public function getFloat()
    {
        return $this->float;
    }

    public function setFloat($float): void
    {
        $this->float = $float;
    }

    public function getBool()
    {
        return $this->bool;
    }

    public function setBool($bool): void
    {
        $this->bool = $bool;
    }

    public function getArray()
    {
        return $this->array;
    }

    public function setArray($array): void
    {
        $this->array = $array;
    }
}
