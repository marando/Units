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

use Marando\Units\Angle;


/**
 * @property string $sign  Sign (+/-) of the time duration.
 * @property double $days  Time expressed in decimal days.
 * @property double $hours Time expressed in decimal hours.
 * @property double $min   Time expressed in decimal minutes.
 * @property double $sec   Time expressed in decimal seconds.
 * @property int    $h     Integer hour component of the time duration.
 * @property int    $m     Integer minute component of the time duration.
 * @property int    $s     Integer second component of the time duration.
 * @property int    $f     Fractional second component of the time duration.
 */
class Time2
{

    //--------------------------------------------------------------------------
    // Constants
    //--------------------------------------------------------------------------

    const SEC_IN_DAY = 86400;

    const FORMAT_DEFAULT = "0h:0m:0s.3f";
    const FORMAT_HMS = "0hʰ0mᵐ0sˢ.3f";
    const FORMAT_SPACED = "h\h m\m s.3f\s";
    const FORMAT_DAYS = '3D day\s';
    const FORMAT_HOURS = '3H \hour\s';
    const FORMAT_MIN = '3M \min';
    const FORMAT_SEC = '3S \sec';

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    public function __construct($sec)
    {
        $this->sec    = $sec;
        $this->format = static::FORMAT_DEFAULT;
    }

    public static function sec($sec)
    {
        return new static($sec);
    }

    public static function min($min)
    {
        return static::sec($min * 60);
    }

    public static function hours($hours)
    {
        return static::sec($hours * 3600);
    }

    public static function days($days)
    {
        return static::sec($days * 86400);
    }

    public static function hms($h, $m, $s, $f)
    {
        $sec = static::hmsf2sec($h, $m, $s, $f);

        return static::sec($sec);
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    private $sec;
    private $format;
    private $round = 9;

    public function __get($name)
    {
        switch ($name) {
            case 'sign':
                return $this->sec < 0 ? '-' : '+';

            case 'days':
                return $this->sec / 86400;

            case 'hours':
                return $this->sec / 3600;

            case 'min':
                return $this->sec / 60;

            case 'sec':
                return $this->sec;

            case 'h':
                return static::sec2hmsf($this->sec, $this->round)[0];

            case 'm':
                return static::sec2hmsf($this->sec, $this->round)[1];

            case 's':
                return static::sec2hmsf($this->sec, $this->round)[2];

            case 'f':
                return static::sec2hmsf($this->sec, $this->round)[3];
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    public function format($format)
    {
        $pad    = false;
        $rChar  = 'hmsfHMS';
        $string = $this->format = $format;
        $string = static::encode($string, $rChar);

        // Perform rounding.
        $this->round = static::maxRound($format);

        // Decimal days
        if (preg_match_all('/([0-9]{0,1})D/', $string, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $D      = round($this->days, (int)$m[1][$i]);
                $string = str_replace($m[0][$i], $D, $string);
            }
        }

        // Decimal hours
        if (preg_match_all('/([0-9]{0,1})H/', $string, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $H      = round($this->hours, (int)$m[1][$i]);
                $string = str_replace($m[0][$i], $H, $string);
            }
        }

        // Decimal minutes
        if (preg_match_all('/([0-9]{0,1})M/', $string, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $M      = round($this->min, (int)$m[1][$i]);
                $string = str_replace($m[0][$i], $M, $string);
            }
        }

        // Decimal seconds
        if (preg_match_all('/([0-9]{0,1})S/', $string, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $S      = round($this->sec, (int)$m[1][$i]);
                $string = str_replace($m[0][$i], $S, $string);
            }
        }

        // Leading zeros h m s
        $string = str_replace('0h', sprintf('%02d', $this->h), $string);
        $string = str_replace('0m', sprintf('%02d', $this->m), $string);
        $string = str_replace('0s', sprintf('%02d', $this->s), $string);

        // No leading zeros, h m s
        $string = str_replace('h', $this->h, $string);
        $string = str_replace('m', $this->m, $string);
        $string = str_replace('s', $this->s, $string);

        // Fractional seconds.
        if (preg_match_all('/([0-9])f/', $string, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $f = substr($this->f, 0, $m[1][$i]);

                if ($pad) {
                    $f      = str_pad($f, $m[1][$i], '0');
                    $string = str_replace($m[0][$i], $f, $string);
                } else {
                    if ($f == 0) {
                        $string = str_replace('.' . $m[0][$i], '', $string);
                        $string = str_replace($m[0][$i], '', $string);
                    } else {
                        $string = str_replace($m[0][$i], $f, $string);
                    }
                }
            }
        }

        return static::decode($string, $rChar);
    }

    // // // Static

    private static function hmsf2sec($h, $m, $s, $f)
    {
        // Find the sign.
        $sign = static::findSign($h, $m, $s, $f);

        // Cast to integers.
        $h = (int)$h;
        $m = (int)$m;

        if ($f) {
            // If fraction is present, seconds should be cast to int.
            $s = (int)$s;

            if ($f >= 1) {
                // Fix for fraction given as integer instead of fraction.
                $f = (double)('0.' . (int)$f);
            }
        }

        // Calculate seconds
        $sec = (abs($h) * 3600) + (abs($m) * 60) + abs($s) + abs($f);

        // Return arcseconds with appropriate sign.
        return $sign == '-' ? -1 * $sec : $sec;
    }

    private static function sec2hmsf($sec, $round)
    {
        // Round seconds to the desired place.
        $sec = round($sec, $round);

        // Calculate h m s
        $h = (int)abs($sec / 3600);
        $m = abs($sec / 60 % 60);
        $s = abs($sec % 60);

        // sec -> string (no scientific notation), then take only fraction.
        $sec = number_format($sec, $round, '.', '');
        $f   = str_replace((int)$sec, '', $sec);
        $f   = str_replace('.', '', $f);
        $f   = rtrim($f, '0');

        // Return components
        return [$h, $m, $s, $f];
    }

    private static function findSign($h, $m, $s, $f)
    {
        if ($h != 0) {
            return $h < 0 ? '-' : '+';
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

    /**
     * Encodes reserved characters for formatter strings.
     *
     * @param string $string String to encode
     * @param string $key    Characters to encode
     *
     * @return string
     */
    private static function encode($string, $key)
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
    private static function decode($string, $key)
    {
        for ($i = 0; $i < strlen($key); $i++) {
            $char   = $key[$i];
            $string = str_replace("%{$i}", "{$char}", $string);
        }

        return $string;
    }

    /**
     * Finds the maximum rounding place of a formatter string.
     *
     * @param string $format
     *
     * @return int
     */
    private static function maxRound($format)
    {
        if (preg_match_all('/([0-9])f/', $format, $m)) {
            return (int)max($m[1]);
        } else {
            return 9;
        }
    }

    // // //

    public function __toString()
    {
        return $this->format($this->format);
    }

}