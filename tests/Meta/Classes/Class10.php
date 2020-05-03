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

/**
 * @ignoredProperties($field1)
 */
class Class10
{
    /**
     * @var string
     */
    private $field1;

    /**
     * @var string
     */
    private $field2;

    public function getField1(): ?string
    {
        return $this->field1;
    }

    public function setField1(string $field1): void
    {
        $this->field1 = $field1;
    }

    public function getField2(): string
    {
        return $this->field2;
    }

    public function setField2(string $field2): void
    {
        $this->field2 = $field2;
    }
}
