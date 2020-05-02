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

class Class8
{
    /**
     * @var string
     */
    private $fieldPresentedInPOPO;

    public function getFieldPresentedInPOPO(): string
    {
        return $this->fieldPresentedInPOPO;
    }

    public function setFieldPresentedInPOPO(string $fieldPresentedInPOPO): void
    {
        $this->fieldPresentedInPOPO = $fieldPresentedInPOPO;
    }
}
