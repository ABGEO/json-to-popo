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
use ABGEO\POPO\Test\Meta\Classes\Class1;
use ABGEO\POPO\Test\Meta\Classes\Class2;
use ABGEO\POPO\Test\Meta\Classes\Class3;
use ABGEO\POPO\Test\Meta\Classes\Class4;
use PHPUnit\Framework\TestCase;

class ComposerTest extends TestCase
{
    public function testAddClassMappingMethodClassNotFoundException(): void
    {
        $composer = new Composer();
        $this->expectExceptionMessage('Class "InvalidClass" not found!');
        $composer->addClassMapping('someProperty', 'InvalidClass');
    }

    public function testComposeObjectMethodClassNotFoundException(): void
    {
        $composer = new Composer();
        $this->expectExceptionMessage('Class "InvalidClass" not found!');
        $composer->composeObject('{}', 'InvalidClass');
    }

    public function testComposeObjectMethodWithPrimitiveDataTypes(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/1.json');
        $excepted = json_decode($jsonContent);

        /** @var Class1 $actual */
        $actual = $composer->composeObject($jsonContent, Class1::class);

        $this->assertInstanceOf(Class1::class, $actual);

        $this->assertIsInt($actual->getInt());
        $this->assertIsString($actual->getString());
        $this->assertIsFloat($actual->getFloat());
        $this->assertIsBool($actual->getBool());
        $this->assertIsArray($actual->getArray());

        $this->assertEquals($excepted->int, $actual->getInt());
        $this->assertEquals($excepted->string, $actual->getString());
        $this->assertEquals($excepted->float, $actual->getFloat());
        $this->assertEquals($excepted->bool, $actual->getBool());
        $this->assertEquals($excepted->array, $actual->getArray());
    }

    public function testComposeObjectMethodClassMappingNotFoundException(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/2.json');

        $this->expectExceptionMessage('Class mapping not found for property "class2"!');
        $composer->composeObject($jsonContent, Class3::class);
    }

    public function testComposeObjectMethodWithObject(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/2.json');
        $excepted = json_decode($jsonContent);

        $composer->addClassMapping('class2', Class2::class);

        /** @var Class3 $actual */
        $actual = $composer->composeObject($jsonContent, Class3::class);

        $this->assertInstanceOf(Class3::class, $actual);
        $this->assertInstanceOf(Class2::class, $actual->getClass2());

        $this->assertEquals($excepted->class2->title, $actual->getClass2()->getTitle());
    }

    public function testComposeObjectMethodWithNestedObject(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/3.json');
        $excepted = json_decode($jsonContent);

        $composer->addClassMapping('class2', Class2::class);
        $composer->addClassMapping('class3', Class3::class);

        /** @var Class4 $actual */
        $actual = $composer->composeObject($jsonContent, Class4::class);

        $this->assertInstanceOf(Class4::class, $actual);
        $this->assertInstanceOf(Class3::class, $actual->getClass3());
        $this->assertInstanceOf(Class2::class, $actual->getClass3()->getClass2());

        $this->assertEquals($excepted->class3->class2->title, $actual->getClass3()->getClass2()->getTitle());
    }

    public function testComposeObjectMethodUndefinedProperty(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/4.json');

        $this->expectExceptionMessage('Method "setUndefinedProperty" not found in target object!');
        $composer->composeObject($jsonContent, Class1::class);
    }
}
