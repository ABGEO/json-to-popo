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

class Class2
{
    private string $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }
}
