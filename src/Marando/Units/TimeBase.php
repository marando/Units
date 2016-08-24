<?php

/*
 * Copyright (C) 2015 Ashley Marando
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Marando\Units;

/**
 * Provides base time and angular functionality.
 *
 * @author Ashley Marando <a.marando@me.com>
 */
abstract class TimeBase
{

    /**
     * Performs a decimal regular expression string formatter replace that
     * provides value at a specified decimal precision.
     *
     * @param $char   Character to match for replacing
     * @param $string String being replaced
     * @param $value  Value to replace
     */
    protected static function rep($char, &$string, $value)
    {
        if (preg_match_all('/([0-9]{0,1})' . $char . '/', $string, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $val    = round($value, (int)$m[1][$i]);
                $string = str_replace($m[0][$i], $val, $string);
            }
        }
    }

    /**
     * Finds the maximum rounding place of a formatter string.
     *
     * @param string $format
     *
     * @return int
     */
    protected static function maxRound($format)
    {
        if (preg_match_all('/([0-9])f/', $format, $m)) {
            return (int)max($m[1]);
        } else {
            return 9;
        }
    }

    /**
     * Encodes reserved characters for formatter strings.
     *
     * @param string $string String to encode
     * @param string $key    Characters to encode
     *
     * @return string
     */
    protected static function encode($string, $key)
    {
        for ($i = 0; $i < strlen($key); $i++) {
            $char   = $key[$i];
            $string = str_replace("\\{$char}", "%{$i}", $string);
        }

        return $string;
    }

    /**
     * Decodes reserved characters for formatter strings.
     *
     * @param string $string String to decode
     * @param string $key    Characters to decode
     *
     * @return string
     */
    protected static function decode($string, $key)
    {
        for ($i = 0; $i < strlen($key); $i++) {
            $char   = $key[$i];
            $string = str_replace("%{$i}", "{$char}", $string);
        }

        return $string;
    }

    // // //

    /**
     * Decomposes arcseconds into ° ' "
     *
     * @param double $asec  Arcseconds to decompose.
     * @param int    $round Places to round arcseconds.
     *
     * @return array
     */
    protected static function asec2dmsf($asec, $round)
    {
        // Round arcseconds to the desired place.
        $asec = round($asec, $round);

        // Calculate ° ' "
        $d = (int)abs($asec / 3600);
        $m = abs($asec / 60 % 60);
        $s = abs($asec % 60);

        // asec -> string (no scientific notation), then take only fraction.
        $asec = number_format($asec, $round, '.', '');
        $f    = str_replace((int)$asec, '', $asec);
        $f    = str_replace('.', '', $f);
        $f    = rtrim($f, '0');

        // Return components
        return [$d, $m, $s, $f];
    }

    /**
     * Decomposes seconds into h m s
     *
     * @param double $sec   Seconds to decompose.
     * @param int    $round Places to round seconds.
     *
     * @return array
     */
    protected static function sec2hmsf($sec, $round)
    {
        // Return components
        return static::asec2dmsf($sec, $round);
    }

    /**
     * Composes ° ' " into arcseconds.
     *
     * @param int        $d Degree component
     * @param int        $m Arcminute component
     * @param int|double $s Arcsecond component
     * @param int|double $f Fractional arcsecond component
     *
     * @return double
     */
    protected static function dmsf2asec($d, $m, $s, $f)
    {
        // Find the sign.
        $sign = static::findSign($d, $m, $s, $f);

        // Cast to integers.
        $d = (int)$d;
        $m = (int)$m;

        if ($f) {
            // If fraction is present, seconds should be cast to int.
            $s = (int)$s;

            if ($f >= 1) {
                // Fix for fraction given as integer instead of fraction.
                $f = (double)('0.' . (int)$f);
            }
        }

        // Calculate arcseconds
        $asec = (abs($d) * 3600) + (abs($m) * 60) + abs($s) + abs($f);

        // Return arcseconds with appropriate sign.
        return $sign == '-' ? -1 * $asec : $asec;
    }

    /**
     * Composes h m s into seconds.
     *
     * @param int        $h Hour component
     * @param int        $m Minute component
     * @param int|double $s Second component
     * @param int|double $f Fractional Second component
     *
     * @return double
     */
    protected static function hmsf2sec($h, $m, $s, $f)
    {
        return static::dmsf2asec($h, $m, $s, $f);
    }

    /**
     * Finds the sign of a set of ° ' " or h m s values
     *
     * @param int        $dh Degree or hour component
     * @param int        $m  Minute component
     * @param int|double $s  Second component
     * @param int|double $f  Fractional second component
     *
     * @return string + or -
     */
    protected static function findSign($dh, $m, $s, $f)
    {
        if ($dh != 0) {
            return $dh < 0 ? '-' : '+';
        } elseif ($m != 0) {
            return $m < 0 ? '-' : '+';
        } elseif ($s != 0) {
            return $s < 0 ? '-' : '+';
        } elseif ($f != 0) {
            return $f < 0 ? '-' : '+';
        } else {
            return '+';
        }
    }

}