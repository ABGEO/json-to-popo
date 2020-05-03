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

use ABGEO\POPO\Util\AnnotationParser;
use PHPUnit\Framework\TestCase;

class AnnotationParserTest extends TestCase
{
    public function testParseParameter(): void
    {
        $exceptedArray = [
            [
                'parameter' => 'var',
                'docComment' => "/**\n * @var string\n */",
                'excepted' => 'string',
            ],
            [
                'parameter' => 'someAnnotation',
                'docComment' => "/**\n * @someAnnotation some annotation\n */",
                'excepted' => 'some annotation',
            ],
            [
                'parameter' => 'someAnnotation',
                'docComment' => "/**\n * @someAnnotation2 some annotation2\n */",
                'excepted' => null,
            ],
            [
                'parameter' => 'someAnnotation',
                'docComment' => "/**\n * @someAnnotation(value, value2, option = value)\n */",
                'excepted' => 'value, value2, option = value',
            ],
        ];

        foreach ($exceptedArray as $data) {
            $this->assertEquals(
                $data['excepted'],
                AnnotationParser::parseParameter($data['docComment'], $data['parameter'])
            );
        }
    }

    public function testGetType(): void
    {
        $exceptedArray = [
            [
                'docComment' => "/**\n * @var string\n */",
                'excepted' => 'string',
            ],
            [
                'docComment' => "/**\n * @var \ABGEO\POPO\Test\Meta\Classes\Class2\n */",
                'excepted' => '\ABGEO\POPO\Test\Meta\Classes\Class2',
            ],
            [
                'docComment' => "/**\n * @var array Variable Description.\n */",
                'excepted' => 'array',
            ],
            [
                'docComment' => "/**\n * @var mixed|null\n */",
                'excepted' => 'mixed',
            ],
            [
                'docComment' => "/**\n * @notVar null\n */",
                'excepted' => null,
            ],
        ];

        foreach ($exceptedArray as $data) {
            $this->assertEquals($data['excepted'], AnnotationParser::getType($data['docComment']));
        }
    }

    public function testGetIgnoredProperties(): void
    {
        $exceptedArray = [
            [
                'docComment' => "/**\n * @notIgnoredProperties()\n */",
                'excepted' => [
                    'properties' => [],
                    'options' => [],
                ],
            ],
            [
                'docComment' => "/**\n * @ignoredProperties(someProperty, anotherProperty)\n */",
                'excepted' => [
                    'properties' => ['someProperty', 'anotherProperty'],
                    'options' => [],
                ],
            ],
            [
                'docComment' => "/**\n * @ignoredProperties(ignoreUnknown = true)\n */",
                'excepted' => [
                    'properties' => [],
                    'options' => [
                        'ignoreUnknown' => 'true',
                    ],
                ],
            ],
            [
                'docComment' => "/**\n * @ignoredProperties(someProperty, anotherProperty, ignoreUnknown = true)\n */",
                'excepted' => [
                    'properties' => ['someProperty', 'anotherProperty'],
                    'options' => [
                        'ignoreUnknown' => 'true',
                    ],
                ],
            ],
        ];

        foreach ($exceptedArray as $data) {
            $this->assertEquals($data['excepted'], AnnotationParser::getIgnoredProperties($data['docComment']));
        }
    }

    public function testGetArrayElementsType(): void
    {
        $exceptedArray = [
            [
                'type' => '',
                'excepted' => false,
            ],
            [
                'type' => '\ABGEO\POPO\Test\Meta\Classes\Class2[]',
                'excepted' => '\ABGEO\POPO\Test\Meta\Classes\Class2',
            ],
            [
                'type' => '<\ABGEO\POPO\Test\Meta\Classes\Class2>',
                'excepted' => '\ABGEO\POPO\Test\Meta\Classes\Class2',
            ],
            [
                'type' => '[\ABGEO\POPO\Test\Meta\Classes\Class2]',
                'excepted' => '\ABGEO\POPO\Test\Meta\Classes\Class2',
            ],
            [
                'type' => 'array',
                'excepted' => 'array',
            ],
            [
                'type' => 'invalid',
                'excepted' => false,
            ],
        ];

        foreach ($exceptedArray as $data) {
            $this->assertEquals($data['excepted'], AnnotationParser::getArrayElementsType($data['type']));
        }
    }
}
