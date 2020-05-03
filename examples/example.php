<?php

/*
 * This file is part of the abgeo/json-to-popo.
 *
 * Copyright (C) 2020 Temuri Takalandze <takalandzet@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use ABGEO\POPO\Example\Person;
use ABGEO\POPO\Composer;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/POPO/Department.php';
require __DIR__ . '/POPO/Position.php';
require __DIR__ . '/POPO/Person.php';

$composer = new Composer();
$jsonContent = file_get_contents(__DIR__ . '/example.json');

$resultObject = $composer->composeObject($jsonContent, Person::class);

var_dump($resultObject);
