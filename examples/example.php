<?php

use ABGEO\POPO\Example\Department;
use ABGEO\POPO\Example\Person;
use ABGEO\POPO\Example\Position;
use ABGEO\POPO\Composer;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/POPO/Department.php';
require __DIR__ . '/POPO/Position.php';
require __DIR__ . '/POPO/Person.php';

$composer = new Composer();
$jsonContent = file_get_contents(__DIR__ . '/example.json');

$composer
    ->addClassMapping('position', Position::class)
    ->addClassMapping('department', Department::class);

$resultObject = $composer->composeObject($jsonContent, Person::class);

var_dump($resultObject);
