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
use ABGEO\POPO\Test\Meta\Classes\Class5;
use ABGEO\POPO\Test\Meta\Classes\Class6;
use ABGEO\POPO\Test\Meta\Classes\Class7;
use ABGEO\POPO\Test\Meta\Classes\Class8;
use ABGEO\POPO\Test\Meta\Classes\Class9;
use PHPUnit\Framework\TestCase;

class ComposerTest extends TestCase
{
    public function testComposeObjectMethodClassNotFoundException(): void
    {
        $composer = new Composer();
        $this->expectExceptionMessage('Class \'InvalidClass\' not found!');
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

    public function testComposeObjectMethodWithObject(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/2.json');
        $excepted = json_decode($jsonContent);

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

        $this->expectExceptionMessage(
            'Property ABGEO\POPO\Test\Meta\Classes\Class1::$undefinedProperty does not exist'
        );
        $composer->composeObject($jsonContent, Class1::class);
    }

    public function testComposeObjectMethodSetterNotFoundException(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/9.json');

        $this->expectExceptionMessage(
            'Property \'ABGEO\POPO\Test\Meta\Classes\Class7::$classWithoutSetter\' does not have a setter!'
        );
        $composer->composeObject($jsonContent, Class7::class);
    }

    public function testComposeObjectMethodUndefinedClassPropertyType(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/5.json');
        $this->expectExceptionMessage(
            'Type of Property \'ABGEO\POPO\Test\Meta\Classes\Class5::$undefinedType\' is undefined!'
        );
        $composer->composeObject($jsonContent, Class5::class);
    }

    public function testComposeObjectMethodInvalidJSONException(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/6.json');
        $this->expectExceptionMessage('The JSON content is invalid!');
        $composer->composeObject($jsonContent, 'InvalidClass');
    }

    public function testComposeObjectMethodWithKeyedArrays(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/7.json');
        $excepted = json_decode($jsonContent, true);

        /** @var Class6 $actual */
        $actual = $composer->composeObject($jsonContent, Class6::class);

        $this->assertInstanceOf(Class6::class, $actual);
        $this->assertIsArray($actual->getKeyedArray());
        $this->assertEquals($excepted['keyedArray'], $actual->getKeyedArray());
    }

    public function testComposeObjectMethodWithNestedKeyedArrays(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/8.json');
        $excepted = json_decode($jsonContent, true);

        /** @var Class6 $actual */
        $actual = $composer->composeObject($jsonContent, Class6::class);

        $this->assertInstanceOf(Class6::class, $actual);
        $this->assertIsArray($actual->getKeyedArray());
        $this->assertEquals($excepted['keyedArray'], $actual->getKeyedArray());
    }

    public function testComposeObjectMethodPOPOContainsNotAllJSONFields(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/10.json');
        /** @var Class8 $actual */
        $actual = $composer->composeObject($jsonContent, Class8::class);

        $this->assertInstanceOf(Class8::class, $actual);
    }

    public function testComposeObjectMethodWithObjectsArray(): void
    {
        $composer = new Composer();
        $jsonContent = file_get_contents(__DIR__ . '/Meta/JSON/11.json');

        /** @var Class9 $actual */
        $actual = $composer->composeObject($jsonContent, Class9::class);

        $this->assertInstanceOf(Class9::class, $actual);
        $this->assertIsArray($actual->getClass2s());
        $this->assertCount(3, $actual->getClass2s());
        $this->assertEquals('Object 1 Title', $actual->getClass2s()[0]->getTitle());
    }
}
