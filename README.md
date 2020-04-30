# json-to-popo

Fill Plain Old PHP Object with JSON content.

[![Build Status](https://travis-ci.com/ABGEO07/json-to-popo.svg?branch=master)](https://travis-ci.com/ABGEO07/json-to-popo)
[![Coverage Status](https://coveralls.io/repos/github/ABGEO07/json-to-popo/badge.svg?branch=master)](https://coveralls.io/github/ABGEO07/json-to-popo?branch=master)
[![GitHub release](https://img.shields.io/github/release/ABGEO07/json-to-popo.svg)](https://github.com/ABGEO07/json-to-popo/releases)
[![GitHub license](https://img.shields.io/github/license/ABGEO07/json-to-popo.svg)](https://github.com/ABGEO07/json-to-popo/blob/master/LICENSE)

## Authors

- [**Temuri Takalandze**](https://abgeo.dev) - *Initial work*

## Installation

You can install this library with [Composer](https://getcomposer.org/):

- `composer require abgeo/json-to-popo`

## Usage

Include composer autoloader in your main file (Ex.: index.php)

- `require __DIR__.'/../vendor/autoload.php';`

Consider that you have `example.json` with the following content:

```json
{
  "firstName": "Temuri",
  "lastName": "Takalandze",
  "active": true,
  "position": {
    "title": "Developer",
    "department": {
      "title": "IT"
    }
  }
}
```

and several POPO classes to represent this JSON data:

`Department.php`

```php
<?php

class Department
{
    private $title;

    // Getters and Setters here...
}
```

`Position.php`

```php
<?php

class Position
{
    private $title;
    private $department;

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): void
    {
        $this->department = $department;
    }
    
    // Other Getters and Setters here...
}

```

`Person.php`

```php
<?php

class Person
{
    private $firstName;
    private $lastName;
    private $active;
    private $position;

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    // Other Getters and Setters here...
}

```

Now you want to convert this JSON to POPO with relations. This package gives you this ability.

Let's create new `ABGEO\POPO\Composer` object and read `example.json` content:

```php
$composer = new Composer();
$jsonContent = file_get_contents(__DIR__ . '/example.json');
```

Now map some JSON keys with your POPOs:

```php
$composer
    ->addClassMapping('position', Position::class)
    ->addClassMapping('department', Department::class);
```

**Note**: You should only map object types to classes. Other primitive data types will be mapped automatically.

Time for magic! Call `composeObject()` with the contents of JSON and the main class, and this will give you POPO:

```php
$resultObject = $composer->composeObject($jsonContent, Person::class);
```

print `$resultObject`:

```php
var_dump($resultObject);

//class ABGEO\POPO\Example\Person#2 (4) {
//  private $firstName =>
//  string(6) "Temuri"
//  private $lastName =>
//  string(10) "Takalandze"
//  private $active =>
//  bool(true)
//  private $position =>
//  class ABGEO\POPO\Example\Position#4 (2) {
//    private $title =>
//    string(9) "Developer"
//    private $department =>
//    class ABGEO\POPO\Example\Department#7 (1) {
//      private $title =>
//      string(2) "IT"
//    }
//  }
//}
```

**See full example [here](examples).**

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

Copyright © 2020 [Temuri Takalandze](https://abgeo.dev).  
Released under the [MIT](LICENSE) license.
