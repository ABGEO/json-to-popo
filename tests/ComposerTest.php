<?php

/*
 * This file is part of the abgeo/json-to-popo.
 *
 * Copyright (C) 2020 Temuri Takalandze <takalandzet@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ABGEO\POPO\Test;

use ABGEO\POPO\Composer;
use PHPUnit\Framework\TestCase;

class ComposerTest extends TestCase
{
    public function testAddClassMappingMethodClassNotFoundException()
    {
        $composer = new Composer();
        $this->expectExceptionMessage('Class "InvalidClass" not found!');
        $composer->addClassMapping('someProperty', 'InvalidClass');
    }

    public function testAddComposeObjectMethodClassNotFoundException()
    {
        $composer = new Composer();
        $this->expectExceptionMessage('Class "InvalidClass" not found!');
        $composer->composeObject('{}', 'InvalidClass');
    }
}
