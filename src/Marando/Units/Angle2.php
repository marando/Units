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

use \Exception;
use \Marando\Units\Time;

/**
 * Represents a geometric angle.
 *
 * @property double $deg  Angle expressed in decimal degrees.
 * @property double $rad  Angle expressed in decimal radians.
 * @property string $sign Sign (+ or -) of the angle.
 * @property int    $d    Integer degree component of the angle.
 * @property int    $m    Integer arcminute component of the angle.
 * @property int    $s    Integer arcsecond component of the angle.
 * @property double $amin Angle expressed in decimal arcminutes.
 * @property double $asec Angle expressed in decimal arcseconds.
 * @property double $mas  Angle expressed in decimal milliarcseconds.
 */
class Angle2
{

    //--------------------------------------------------------------------------
    // Constants
    //--------------------------------------------------------------------------

    /**
     * Number of arcminutes in one degree.
     */
    const AMIN_DEG = 60;

    /**
     * Number of arcseconds in one degree.
     */
    const ASEC_DEG = 3600;

    /**
     * Number of milliarcseconds in one degree.
     */
    const MAS_DEG = 3.6e6;

    /**
     * Value of Pi (π)
     */
    const π = 3.141592653589793238462643383279502884197169399375105820974944592;

    /**
     * Default string format, +43°07'08".399
     */
    const FORMAT_DEFAULT = '+0d°0m\'0s".3u';

    /**
     * Compacted string format with no leading zeros, +43°7'8".399
     */
    const FORMAT_COMPACT = 'd°m\'s".1u';

    /**
     * Spaced string format, +43 07 08.399
     */
    const FORMAT_SPACED = '+0d 0m 0s.3u';

    /**
     * Decimal format, 43.119000
     */
    const FORMAT_DECIMAL = 'D';

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Creates a new angle from degrees or radians.
     *
     * @param double $deg
     * @param double $rad
     */
    private function __construct($deg = null, $rad = null)
    {
        // Set degrees.
        $this->deg = $deg ? $deg : $rad * 180 / static::π;

        // Set default string format.
        $this->format = static::FORMAT_DEFAULT;
    }

    // // // Static

    /**
     * Creates a new angle from degree, arcminute and arcsecond components.
     *
     * @param $d int Degree component
     * @param $m int Arcminute component
     * @param $s int Arcsecond component
     *
     * @return static
     */
    public static function dms($d, $m, $s)
    {
        $deg = abs($d) + abs($m / 60) + abs($s / 3600);

        if ($d != 0) {
            $deg = $d < 0 ? $deg * -1 : $deg;
        } elseif ($m != 0) {
            $deg = $m < 0 ? $deg * -1 : $deg;
        } elseif ($s != 0) {
            $deg = $m < 0 ? $deg * -1 : $deg;
        }

        return new static($deg);
    }

    /**
     * Creates a new angle from degrees.
     *
     * @param $deg
     *
     * @return static
     */
    public static function deg($deg)
    {
        return new static($deg);
    }

    /**
     * Creates a new angle from radians.
     *
     * @param $rad
     *
     * @return static
     */
    public static function rad($rad)
    {
        return new static(null, $rad);
    }

    /**
     * Creates an angle from a number of milliarcseconds.
     *
     * @param $mas
     *
     * @return Angle2
     */
    public static function mas($mas)
    {
        return static::deg($mas / static::MAS_DEG);
    }

    /**
     * Creates an angle from a number of arcseconds.
     *
     * @param $asec
     *
     * @return Angle2
     */
    public static function asec($asec)
    {
        return static::deg($asec / static::ASEC_DEG);
    }

    /**
     * Creates an angle from a number of arcminutes.
     *
     * @param $amin
     *
     * @return Angle2
     */
    public static function amin($amin)
    {
        return static::deg($amin / static::AMIN_DEG);
    }

    public static function time(Time $time, $interval = Time::SEC_IN_DAY)
    {
        return static::deg($time->sec / $interval * 360);
    }

    /**
     * Creates an angle with the value of Pi.
     *
     * @return Angle2
     */
    public static function pi()
    {
        return static::rad(static::π);
    }

    /**
     * Creates an angle from the arc tangent of two Angle instances or float
     * numeric expressed in radians.
     *
     * @param float|Angle2 $y Dividend
     * @param float|Angle2 $x Divisor
     *
     * @return Angle2
     */
    public static function atan2($y, $x)
    {
        $y = $y instanceof Angle2 ? $y->rad : $y;
        $x = $x instanceof Angle2 ? $x->rad : $x;

        return Angle2::rad(atan2($y, $x));
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    /**
     * Degree value of this angle.
     *
     * @var double
     */
    private $deg;

    /**
     * String format of the instance.
     *
     * @var string
     */
    private $format;

    function __get($name)
    {
        switch ($name) {
            case 'rad':
                return $this->deg * static::π / 180;

            case 'amin':
                return $this->deg * static::AMIN_DEG;

            case 'asec':
                return $this->deg * static::ASEC_DEG;

            case 'mas':
                return $this->deg * static::MAS_DEG;

            case 'd':
                return abs((int)$this->deg);

            case 'm':
                return abs($this->amin % 60);

            case 's':
                return round(abs(fmod($this->asec, 60)), 10);

            case 'sign':
                return $this->deg < 0 ? '-' : '+';

            case 'deg':
                return $this->{$name};
        }
    }

    function __set($name, $value)
    {
        switch ($name) {
            case 'deg':
                $this->deg = $value;
                break;

            default:
                throw new Exception("{$name} is not a valid property.");
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    /**
     * Normalizes this angle to a specified interval. If an interval is not
     * supplied, the default is [0, 2π) (between 0 and 360 degrees).
     *
     * @param int $lb Lower bound of the interval (in degrees).
     * @param int $ub Upper bound of the interval (in degrees).
     *
     * @return $this
     */
    public function norm($lb = 0, $ub = 360)
    {
        $deg       = fmod($this->deg, $ub);
        $this->deg = $deg < $lb ? $deg += $ub : $deg;

        return $this;
    }

    /**
     * Converts this instance to the proportion of time within a specified time
     * interval.
     *
     * @param int $interval
     *
     * @return Time
     */
    public function toTime($interval = Time::SEC_IN_DAY)
    {
        return new Time(($this->deg / 360) * $interval);
    }

    /**
     * Adds an angle to this instance and returns a new instance with the sum.
     *
     * @param Angle2 $angle
     *
     * @return Angle2
     */
    public function add(Angle2 $angle)
    {
        return Angle2::deg($this->deg + $angle->deg);
    }

    /**
     * Subtracts an angle to this instance and returns a new instance with the
     * difference.
     *
     * @param Angle2 $angle
     *
     * @return Angle2
     */
    public function sub(Angle2 $angle)
    {
        return Angle2::deg($this->deg - $angle->deg);
    }

    /**
     * Multiplies an angle to this instance and returns a new instance with the
     * product.
     *
     * @param Angle2 $angle
     *
     * @return Angle2
     */
    public function mul(Angle2 $angle)
    {
        return Angle2::deg($this->deg * $angle->deg);
    }

    /**
     * Divides an angle to by instance and returns a new instance with the
     * quotient.
     *
     * @param Angle2 $angle
     *
     * @return Angle2
     */
    public function div(Angle2 $angle)
    {
        return Angle2::deg($this->deg / $angle->deg);
    }

    /**
     * Negates this instance.
     *
     * @return $this
     */
    public function neg()
    {
        $this->deg *= -1;

        return $this;
    }

    /**
     * Formats this instance in the specified string format.
     *
     * @param $format string Format of the string, e.g. +0d°0m'0s".u
     *
     * @return string
     */
    public function format($format)
    {
        $str = $this->format = $format;

        // Calculate micro arcseconds.
        $μ = $this->s - (int)$this->s;
        $μ = number_format($μ, 15, '.', '');
        $μ = str_replace('0.', '', $μ);
        $μ = str_replace('.', '', $μ);
        $μ = str_pad($μ, 14, '0', STR_PAD_RIGHT);

        // Calculate and round arcseconds.
        $round = '0.' . $μ > 0.5 ? true : false;
        $s     = $round ? round($this->s) : (int)$this->s;

        // Round micro arcseconds
        $μ = round('0.' . $μ, 6);
        $μ = number_format($μ, 15, '.', '');
        $μ = $μ >= 1 ? abs(1 - $μ) : $μ;
        $μ = str_replace('0.', '', $μ);
        $μ = substr($μ, 0, 5);
        $μ = str_pad($μ, 5, '0', STR_PAD_RIGHT);

        // Encode special format chars that appear in string.
        $str = $this->encode($str, 'dmsaec');

        /**
         * +  ->  + or -
         * D  ->  decimal degrees, e.g. 103.435432
         */
        $str = str_replace('+', sprintf('%s', $this->sign), $str);
        $str = str_replace('D', round(sprintf('%.9f', $this->deg), 9), $str);

        // Decimal valued arcseconds, arcminutes and milliarcseconds.
        $str = str_replace('asec.', $this->asec, $str);
        $str = str_replace('amin.', $this->amin, $str);
        $str = str_replace('mas.', $this->mas, $str);

        // Rounded integer value arcseconds, arcminutes and milliarcseconds.
        $str = str_replace('asec', round($this->asec, 0), $str);
        $str = str_replace('amin', round($this->amin, 0), $str);
        $str = str_replace('mas', round($this->mas, 0), $str);

        // Leading zeros before d/m/s
        $str = str_replace('0d', sprintf('%03d', $this->d), $str);
        $str = str_replace('0m', sprintf('%02d', $this->m), $str);
        $str = str_replace('0s', sprintf('%02d', $s), $str);

        // Integer value (not padded) d/m/s
        $str = str_replace('d', $this->d, $str);
        $str = str_replace('m', $this->m, $str);
        $str = str_replace('s', $s, $str);

        // Fractional arcseconds
        /**
         * u   ->  just display the fraction, e.g. 12373
         * 3u  ->  display the fraction to # spaces and pad 124
         *                                                  100
         */
        if (preg_match_all('/([0-9])u/', $str, $m)) {
            for ($i = 0; $i < count($m[0]); $i++) {
                $round = $m[1][$i];

                $u = substr($μ, 0, $round);
                $u = rtrim($u, '0');

                if ($μ == 0) {
                    // Fraction is zero, remove any mention of the micro.
                    $str = str_replace(".{$round}u", '', $str);
                    $str = str_replace("{$round}u", '', $str);
                } else {
                    $str = str_replace("{$round}u", $u, $str);
                }
            }
        } else {
            $str = str_replace('u', $μ, $str);
        }

        // Return decoded string.
        return $this->decode($str, 'dmsaec');
    }

    // // // Private

    private function encode($string, $key)
    {
        for ($i = 0; $i < strlen($key); $i++) {
            $char   = $key[$i];
            $string = str_replace("\\{$char}", "%{$i}", $string);
        }

        return $string;
    }

    private function decode($string, $key)
    {
        for ($i = 0; $i < strlen($key); $i++) {
            $char   = $key[$i];
            $string = str_replace("%{$i}", "{$char}", $string);
        }

        return $string;
    }

    // // // Overrides

    /**
     * Represents this instance as a string.
     *
     * @return string
     */
    function __toString()
    {
        return $this->format($this->format);
    }

}