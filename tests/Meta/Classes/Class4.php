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

class Class4
{
    /**
     * @var \ABGEO\POPO\Test\Meta\Classes\Class3
     */
    private $class3;

    public function getClass3(): Class3
    {
        return $this->class3;
    }

    public function setClass3(Class3 $class3): void
    {
        $this->class3 = $class3;
    }
}
