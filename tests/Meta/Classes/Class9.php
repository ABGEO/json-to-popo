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

class Class9
{
    /**
     * @var \ABGEO\POPO\Test\Meta\Classes\Class2[]
     */
    private $class2s;

    public function getClass2s(): array
    {
        return $this->class2s;
    }

    public function setClass2s(array $class2s): void
    {
        $this->class2s = $class2s;
    }
}
