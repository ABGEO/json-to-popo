<?php

/*
 * This file is part of the abgeo/json-to-popo.
 *
 * Copyright (C) 2020 Temuri Takalandze <takalandzet@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ABGEO\POPO\Util;

use function lcfirst;
use function str_replace;
use function ucwords;

/**
 * Normalize things.
 *
 * @author Temuri Takalandze <takalandzet@gmail.com>
 */
class Normalizer
{
    /**
     * Converts a word into the class name format.
     *
     * @param string $word Word for converting.
     *
     * @return string Converted word.
     */
    public static function classify(string $word): string
    {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
    }

    /**
     * Camelizes a word. This uses the classify()
     * method and turns the first character to lowercase.
     *
     * @param string $word Word for Camelizing.
     *
     * @return string Camelized word.
     */
    public static function camelize(string $word): string
    {
        return lcfirst(self::classify($word));
    }
}
