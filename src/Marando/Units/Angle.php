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
 * Represents a geometric angle.
 *
 * @property string $sign   Sign (+/-) of the angle.
 * @property double $deg    Angle expressed in decimal degrees.
 * @property double $rad    Angle expressed in decimal radians.
 * @property double $amin   Angle expressed in decimal arcminutes.
 * @property double $asec   Angle expressed in decimal arcseconds.
 * @property double $mas    Angle expressed in decimal milliarcseconds.
 * @property int    $d      Integer degree component of the angle.
 * @property int    $m      Integer arcminute component of the angle.
 * @property int    $s      Integer arcsecond component of the angle.
 * @property string $f      Fractional arcsecond component of the angle.
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Angle extends TimeBase
{

    //--------------------------------------------------------------------------
    // Constants
    //--------------------------------------------------------------------------

    /**
     * Default format, -012°34'56".123
     */
    const FORMAT_DEFAULT = '+0d°0m\'0s".3f';

    /**
     * Compact format, -12°34'56".1
     */
    const FORMAT_COMPACT = 'd°m\'s".1f';

    /**
     * Spaced format, -012 34 56.123
     */
    const FORMAT_SPACED = '+0d 0m 0s.3f';

    /**
     * Colon format, -012:34:56.123
     */
    const FORMAT_COLON = '+0d:0m:0s.3f';

    /**
     * Decimal format, 12.5822565
     */
    const FORMAT_DECIMAL = '9D';

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Creates a new angle from a number of milliarcseconds.
     *
     * @param double $mas Milliarcseconds
     */
    private function __construct($mas)
    {
        $this->mas    = $mas;
        $this->format = static::FORMAT_DEFAULT;
    }

    // // // Static

    /**
     * Creates a new angle from a number of degrees.
     *
     * @param double $deg Degrees
     *
     * @return static
     */
    public static function deg($deg)
    {
        return static::mas($deg * 3.6e6);
    }

    /**
     * Creates a new angle from a number of radians.
     *
     * @param double $rad Radians
     *
     * @return static
     */
    public static function rad($rad)
    {
        return static::deg(rad2deg($rad));
    }

    /**
     * Creates a new angle from degree, arcminute and arcsecond components.
     *
     * @param int        $d Degree component
     * @param int        $m Arcminute component
     * @param int|double $s Arcsecond component
     * @param int|double $f Fractional arcsecond component
     *
     * @return static
     */
    public static function dms($d, $m = 0, $s = 0, $f = 0)
    {
        $asec = static::dmsf2asec($d, $m, $s, $f);

        return static::mas($asec * 1000);
    }

    /**
     * Creates a new angle from a number of milliarcseconds.
     *
     * @param double $mas Milliarcseconds
     *
     * @return static
     */
    public static function mas($mas)
    {
        return new static($mas);
    }

    /**
     * Creates a new angle from a number of arcseconds.
     *
     * @param double $asec Arcseconds
     *
     * @return static
     */
    public static function asec($asec)
    {
        return static::mas($asec * 1000);
    }

    /**
     * Creates a new angle from a number of arcminutes.
     *
     * @param double $amin Arcminutes
     *
     * @return static
     */
    public static function amin($amin)
    {
        return static::mas($amin * 6e4);
    }

    /**
     * Creates a new angle equal to the number of revolutions of a duration of
     * time within a specified time interval.
     *
     * @param Time $time
     * @param Time $interval
     *
     * @return Angle
     */
    public static function time(Time $time, Time $interval = null)
    {
        $secInDay = Time::days(1)->sec;
        $interval = $interval ? $interval->sec : $secInDay;

        return static::deg($time->sec / $interval * 360);
    }

    /**
     * Creates a new angle representing the value of π (Pi).
     *
     * @return static
     */
    public static function pi()
    {
        return static::rad(pi());
    }

    /**
     * Creates a new angle from the arc tangent of two Angle instances or float
     * values expressed in radians.
     *
     * @param float|Angle $y Dividend
     * @param float|Angle $x Divisor
     *
     * @return static
     */
    public static function atan2($y, $x)
    {
        $y = $y instanceof Angle ? $y->rad : $y;
        $x = $x instanceof Angle ? $x->rad : $x;

        return static::rad(atan2($y, $x));
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    /**
     * Value of this instance expressed in milliarcseconds.
     *
     * @var double
     */
    private $mas;

    /**
     * String format of this instance.
     *
     * @var string
     */
    private $format;

    /**
     * Rounding place of this instance.
     *
     * @var int
     */
    private $round = 9;

    public function __get($name)
    {
        switch ($name) {
            case 'sign':
                return $this->mas < 0 ? '-' : '+';

            case 'deg':
                return $this->mas / 3.6e6;

            case 'rad':
                return deg2rad($this->deg);

            case 'mas':
                return $this->mas;

            case 'asec':
                return $this->mas / 1e3;

            case 'amin':
                return $this->mas / 1e3 / 60;

            case 'd':
                return static::asec2dmsf($this->asec, $this->round)[0];

            case 'm':
                return static::asec2dmsf($this->asec, $this->round)[1];

            case 's':
                return static::asec2dmsf($this->asec, $this->round)[2];

            case 'f':
                return static::asec2dmsf($this->asec, $this->round)[3];

            default:
                throw new Exception("{$name} is not a valid property.");
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'mas':
                $this->mas = $value;
                break;

            case 'asec':
                $this->mas = $value * 1000;
                break;

            case 'amin':
                $this->mas = $value * 6e4;
                break;

            case 'deg':
                $this->mas = $value * 3.6e6;
                break;

            case 'rad':
                $this->mas = rad2deg($value) * 3.6e6;
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
     * supplied then the default used is [0, 2π) (between 0 and 360 degrees).
     *
     * @param int $lb Lower bound of the interval (in degrees)
     * @param int $ub Upper bound of the interval (in degrees)
     *
     * @return $this
     */
    public function norm($lb = 0, $ub = 360)
    {
        // Convert bounds to milliarcseconds.
        $lb = $lb * 3.6e6;
        $ub = $ub * 3.6e6;

        // Modulate to interval and adjust for lower bound.
        $mas       = fmod($this->mas, $ub);
        $this->mas = $mas < $lb ? $mas += $ub : $mas;

        return $this;
    }

    /**
     * Converts this instance to a proportion of time passed within a specified
     * time interval where 360 degrees is equal to the interval.
     *
     * @param Time $interval
     *
     * @return Time
     */
    public function toTime(Time $interval = null)
    {
        $secInDay = Time::days(1)->sec;
        $interval = $interval ? $interval->sec : $secInDay;

        return Time::sec(($this->deg / 360) * $interval);
    }

    /**
     * Adds an angle to this instance and returns a new instance with the sum.
     *
     * @param Angle $b Angle to add
     *
     * @return Angle Sum of the two angles
     */
    public function add(Angle $b)
    {
        return Angle::mas($this->mas + $b->mas);
    }

    /**
     * Subtracts an angle from this instance and returns a new instance with the
     * difference.
     *
     * @param Angle $b Angle to subtract
     *
     * @return Angle Difference of the two angles
     */
    public function sub(Angle $b)
    {
        return Angle::mas($this->mas - $b->mas);
    }

    /**
     * Returns a new angle with the negation of this instance.
     *
     * @return static
     */
    public function neg()
    {
        return Angle::mas($this->mas * -1);
    }

    /**
     * Formats this instance in the specified string format.
     *
     * @param string $format Formatter string, e.g. +0d°0m'0s".3f
     *
     * @return string
     */
    public function format($format)
    {
        $pad    = false;
        $rChar  = 'dmsfD+';
        $string = $this->format = $format;
        $string = static::encode($string, $rChar);

        // Perform rounding.
        $this->round = static::maxRound($format);

        // Add sign.
        $s = '';
        if (strstr($string, '+')) {
            $string = str_replace('+', sprintf('%s', $this->sign), $string);
        } else {
            $s = $this->sign == '-' ? '-' : '';
            if (strstr($string, '0d') && $s == '') {
                $s = ' ';
            }
        }

        // Decimal degrees and radians
        static::fmtRep('D', $string, $this->deg);
        static::fmtRep('R', $string, $this->rad);

        // Decimal arcseconds, arcminutes and milliarcseconds.
        $string = str_replace('asec.', $this->asec, $string);
        $string = str_replace('amin.', $this->amin, $string);
        $string = str_replace('mas.', $this->mas, $string);

        // Rounded integer arcseconds, arcminutes and milliarcseconds.
        $string = str_replace('asec', round($this->asec, 0), $string);
        $string = str_replace('amin', round($this->amin, 0), $string);
        $string = str_replace('mas', round($this->mas, 0), $string);

        // Leading zeros before ° ' "
        $string = str_replace('0d', $s . sprintf('%03d', $this->d), $string);
        $string = str_replace('0m', sprintf('%02d', $this->m), $string);
        $string = str_replace('0s', sprintf('%02d', $this->s), $string);

        // No leading zeros before ° ' "
        $string = str_replace('d', $s . $this->d, $string);
        $string = str_replace('m', $this->m, $string);
        $string = str_replace('s', $this->s, $string);

        // Fractional arcseconds.
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

    // // // Overrides

    /**
     * Represents this instance as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format($this->format);
    }

}