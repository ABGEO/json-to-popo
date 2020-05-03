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

use function preg_match_all;
use function sizeof;
use function explode;
use function strpos;
use function str_replace;
use function substr;
use function preg_match;
use function trim;

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
        preg_match_all("/@{$parameter}[@ (](.*)[ ]*(?:@|\r\n|\n|\))/U", $docComment, $matches);

        return sizeof($matches[1]) === 0 ? null : (trim($matches[1][0]) ?? null);
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

            if (false !== strpos($parsed[0], '|')) {
                $parsed = explode('|', $parsed[0]);
            }

            return $parsed[0];
        }

        return null;
    }

    /**
     * Parse ignoredProperties parameter of given Document Comment.
     *
     * @param string $docComment Document Comment.
     *
     * @return array|null Parsed value or null.
     */
    public static function getIgnoredProperties(string $docComment): ?array
    {
        $return = [
            'properties' => [],
            'options' => [],
        ];
        $parsed = self::parseParameter($docComment, 'ignoredProperties');

        if (!$parsed) {
            return $return;
        }

        foreach (explode(',', $parsed) as $property) {
            $property = trim(str_replace(['"', '\'', '$'], null, $property));

            if (false !== strpos($property, '=')) {
                $option = explode('=', $property);
                $return['options'][trim($option[0])] = trim($option[1]);
            } else {
                $return['properties'][] = $property;
            }
        }

        return $return;
    }

    /**
     * Get class part from array type hint.
     *
     * @param string $type Document Comment.
     *
     * @return string|false array, class type or false if not array.
     */
    public static function getArrayElementsType(?string $type): string
    {
        if (!$type) {
            return false;
        }

        $type = str_replace(' ', null, $type);

        if ('[]' === substr($type, -2)) {
            return substr($type, 0, -2);
        }

        if (empty($type)) {
            return 'array';
        }

        preg_match('/<(.*?)>/', $type, $matches);
        if (isset($matches[1]) && !empty($matches[1])) {
            return $matches[1];
        }

        preg_match('/\[(.*?)]/', $type, $matches);
        if (isset($matches[1]) && !empty($matches[1])) {
            return $matches[1];
        }

        if ('array' == $type) {
            return $type;
        }

        return false;
    }
}
