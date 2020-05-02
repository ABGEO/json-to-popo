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

class Class3
{
    /**
     * @var \ABGEO\POPO\Test\Meta\Classes\Class2
     */
    private $class2;

    public function getClass2(): Class2
    {
        return $this->class2;
    }

    public function setClass2(Class2 $class2): void
    {
        $this->class2 = $class2;
    }
}
