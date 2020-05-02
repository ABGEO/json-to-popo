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

/**
 * Parse Class, Method or Property annotations.
 *
 * @author Temuri Takalandze <takalandzet@gmail.com>
 */
class AnnotationParser
{
    /**
     * Parse given parameter of given Doc Comment
     *
     * @param string $docComment Document Comment.
     * @param string $parameter  Parameter to parse.
     *
     * @return string|null Parsed value or null on fail.
     */
    public static function parseParameter(string $docComment, string $parameter)
    {
        preg_match_all("/@{$parameter} (.*)[ ]*(?:@|\r\n|\n)/U", $docComment, $matches);

        return sizeof($matches[1]) === 0 ? null : ($matches[1][0] ?? null);
    }

    /**
     * Parse var parameter of given Document Comment.
     *
     * @param string $docComment Document Comment.
     *
     * @return string|null Parsed value or null on fail.
     */
    public static function getType(string $docComment): ?string
    {
        if ($parsed = self::parseParameter($docComment, 'var')) {
            $parsed = explode(' ', $parsed);
            return $parsed[0];
        }

        return null;
    }
}
