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

use ABGEO\POPO\Util\Normalizer;
use PHPUnit\Framework\TestCase;

class NormalizerTest extends TestCase
{
    public function testClassifyMethod(): void
    {
        $exceptedArray = [
            'someClass' => 'SomeClass',
            'SomeClass' => 'SomeClass',

            'Some Class' => 'SomeClass',
            'some class' => 'SomeClass',
            'some Class' => 'SomeClass',
            'Some class' => 'SomeClass',

            'Some_Class' => 'SomeClass',
            'some_class' => 'SomeClass',
            'some_Class' => 'SomeClass',
            'Some_class' => 'SomeClass',

            'Some-Class' => 'SomeClass',
            'some-class' => 'SomeClass',
            'some-Class' => 'SomeClass',
            'Some-class' => 'SomeClass',
        ];

        foreach ($exceptedArray as $input => $excepted) {
            $this->assertEquals($excepted, Normalizer::classify($input));
        }
    }

    public function testCamelizeMethod(): void
    {
        $exceptedArray = [
            'someClass' => 'someClass',
            'SomeClass' => 'someClass',

            'Some Class' => 'someClass',
            'some class' => 'someClass',
            'some Class' => 'someClass',
            'Some class' => 'someClass',

            'Some_Class' => 'someClass',
            'some_class' => 'someClass',
            'some_Class' => 'someClass',
            'Some_class' => 'someClass',

            'Some-Class' => 'someClass',
            'some-class' => 'someClass',
            'some-Class' => 'someClass',
            'Some-class' => 'someClass',
        ];

        foreach ($exceptedArray as $input => $excepted) {
            $this->assertEquals($excepted, Normalizer::camelize($input));
        }
    }
}
