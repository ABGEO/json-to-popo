# json-to-popo

Fill Plain Old PHP Object with JSON content.

[![Build Status](https://travis-ci.com/ABGEO07/json-to-popo.svg?branch=master)](https://travis-ci.com/ABGEO07/json-to-popo)
[![Coverage Status](https://coveralls.io/repos/github/ABGEO07/json-to-popo/badge.svg?branch=master)](https://coveralls.io/github/ABGEO07/json-to-popo?branch=master)
[![GitHub release](https://img.shields.io/github/release/ABGEO07/json-to-popo.svg)](https://github.com/ABGEO07/json-to-popo/releases)
[![Packagist Version](https://img.shields.io/packagist/v/abgeo/json-to-popo.svg)](https://packagist.org/packages/abgeo/json-to-popo)
[![GitHub license](https://img.shields.io/github/license/ABGEO07/json-to-popo.svg)](https://github.com/ABGEO07/json-to-popo/blob/master/LICENSE)

## Authors

- [**Temuri Takalandze**](https://abgeo.dev) - *Initial work*

## Installation

**Note**: This library requires **PHP 7.4** or higher.

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
    private string $title;

    // Getters and Setters here...
}
```

`Position.php`

```php
<?php

class Position
{
    private string $title;
    private Department $department;

    // Getters and Setters here...
}

```

`Person.php`

```php
<?php

class Person
{
    private string $firstName;
    private string $lastName;
    private bool $active;
    private Position $position;

    // Getters and Setters here...
}

```

**Note**: All properties in POPO classes must type-hinted (That's why we need PHP 7.4) and have setters.

Now you want to convert this JSON to POPO with relations. This package gives you this ability.

Let's create new `ABGEO\POPO\Composer` object and read `example.json` content:

```php
$composer = new Composer();
$jsonContent = file_get_contents(__DIR__ . '/example.json');
```

Time for magic! Call `composeObject()` with the contents of JSON and the main class, and this will give you POPO:

```php
$resultObject = $composer->composeObject($jsonContent, Person::class);
```

Print `$resultObject`:

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
