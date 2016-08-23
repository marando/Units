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
 * Represents an interval of time.
 *
 * @property string $sign  Sign (+/-) of the time duration.
 * @property double $years Time expressed in decimal years.
 * @property double $weeks Time expressed in decimal weeks.
 * @property double $days  Time expressed in decimal days.
 * @property double $hours Time expressed in decimal hours.
 * @property double $min   Time expressed in decimal minutes.
 * @property double $sec   Time expressed in decimal seconds.
 * @property int    $h     Integer hour component of the time duration.
 * @property int    $m     Integer minute component of the time duration.
 * @property int    $s     Integer second component of the time duration.
 * @property int    $f     Fractional second component of the time duration.
 */
class Time extends TimeBase
{

    //--------------------------------------------------------------------------
    // Constants
    //--------------------------------------------------------------------------

    /**
     * Number of days in a Julian year.
     */
    const JulianYear = 365.25;

    /**
     * Default format, 07:47:16.8
     */
    const FORMAT_DEFAULT = "0h:0m:0s.3f";

    /**
     * HMS format, 07ʰ47ᵐ16ˢ.8
     */
    const FORMAT_HMS = "0hʰ0mᵐ0sˢ.3f";

    /**
     * Spaced format, 7h 47m 16.8s
     */
    const FORMAT_SPACED = "h\h m\m s.3f\s";

    /**
     * Year format, 1.767 years
     */
    const FORMAT_YEARS = '3Y year\s';

    /**
     * Week format, 2.046 weeks
     */
    const FORMAT_WEEKS = '3W week\s';

    /**
     * Day format, 1.325 days
     */
    const FORMAT_DAYS = '3D day\s';

    /**
     * Hour format, 7.788 hours
     */
    const FORMAT_HOURS = '3H \hour\s';

    /**
     * Minute format, 467.28 min
     */
    const FORMAT_MIN = '3M \min';

    /**
     * Second format, 86.4 sec
     */
    const FORMAT_SEC = '3S \sec';

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Creates a new Time instance from a number of seconds.
     *
     * @param double $sec Seconds
     */
    private function __construct($sec)
    {
        $this->sec    = $sec;
        $this->format = static::FORMAT_DEFAULT;
    }

    // // // Static

    /**
     * Creates a new Time instance from a number of seconds.
     *
     * @param double $sec Seconds
     *
     * @return static
     */
    public static function sec($sec)
    {
        return new static($sec);
    }

    /**
     * Creates a new Time instance from a number of minutes.
     *
     * @param double $min Minutes
     *
     * @return static
     */
    public static function min($min)
    {
        return static::sec($min * 60);
    }

    /**
     * Creates a new Time instance from a number of hours.
     *
     * @param double $hours Hours
     *
     * @return static
     */
    public static function hours($hours)
    {
        return static::sec($hours * 3600);
    }

    /**
     * Creates a new Time instance from a number of days.
     *
     * @param double $days Days
     *
     * @return static
     */
    public static function days($days)
    {
        return static::sec($days * 86400);
    }

    /**
     * Creates a new Time instance from a number of weeks.
     *
     * @param double $weeks Weeks
     *
     * @return static
     */
    public static function weeks($weeks)
    {
        return static::sec($weeks * 86400 * 7);
    }

    /**
     * Creates a new Time instance from a number of years.
     *
     * @param double $years       Years
     * @param double $daysPerYear Optional override to number of days per year
     *
     * @return Time
     */
    public static function years($years, $daysPerYear = 365.25)
    {
        return static::sec($years * 86400 * $daysPerYear);
    }

    /**
     * Creates a new Time instance from hour, minute and second components.
     *
     * @param            $h Hour component
     * @param int        $m Minute component
     * @param int|double $s Second component
     * @param int|double $f Fractional second component
     *
     * @return Time
     */
    public static function hms($h, $m = 0, $s = 0, $f = 0)
    {
        $sec = static::hmsf2sec($h, $m, $s, $f);

        return static::sec($sec);
    }

    /**
     * Creates a new Time instance from an angle representing the proportion of
     * time passed within a defined interval.
     *
     * @param Angle $angle
     * @param Time  $interval
     *
     * @return Time
     */
    public static function angle(Angle $angle, Time $interval)
    {
        return $angle->toTime($interval);
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    /**
     * Value of this instance expressed in seconds.
     *
     * @var double
     */
    private $sec;

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
                return $this->sec < 0 ? '-' : '+';

            case 'years':
                return $this->sec / 86400 / 365.25;

            case 'weeks':
                return $this->sec / 86400 / 7;

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

    public function __set($name, $value)
    {
        switch ($name) {
            case 'sec':
                $this->sec = $value;
                break;

            case 'min':
                $this->sec = $value * 60;
                break;

            case 'hours':
                $this->sec = $value * 3600;
                break;

            case 'days':
                $this->sec = $value * 86400;
                break;

            default:
                throw new Exception("{$name} is not a valid property.");
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    /**
     * Adds another Time instance to this instance and returns a new instance
     * with the sum.
     *
     * @param Time $b Time instance to add
     *
     * @return Time Sum of the two Time instances
     */
    public function add(Time $b)
    {
        return Time::sec($this->sec + $b->sec);
    }

    /**
     * Subtracts another Time instance from this instance and returns a new
     * instance with the difference.
     *
     * @param Time $b Time instance to subtract
     *
     * @return Time Difference of the two Time instances
     */
    public function sub(Time $b)
    {
        return Time::sec($this->sec - $b->sec);
    }

    /**
     * Multiplies another Time instance with this instance and returns a new
     * instance with the product.
     *
     * @param Time $b Time instance to multiply
     *
     * @return Time Product of the two Time instances
     */
    public function mul(Time $b)
    {
        return Time::sec($this->sec * $b->sec);
    }

    /**
     * Divides this instance by another Time instance and returns a new instance
     * with the quotient.
     *
     * @param Time $b Divisor Time instance
     *
     * @return Time Quotient of the two Time instances
     */
    public function div(Time $b)
    {
        return Time::sec($this->sec / $b->sec);
    }

    /**
     * Negates this instance.
     *
     * @return $this
     */
    public function neg()
    {
        $this->sec *= -1;

        return $this;
    }

    /**
     * Formats this instance in the specified string format.
     *
     * @param string $format Formatter string, e.g. 0h:0m:0s.3f
     *
     * @return string
     */
    public function format($format)
    {
        $pad    = false;
        $rChar  = 'hmsfHMS';
        $string = $this->format = $format;
        $string = static::encode($string, $rChar);

        // Perform rounding.
        $this->round = static::maxRound($format);

        // Decimal years, weeks, days, hours, minutes, and seconds
        static::rep('Y', $string, $this->years);
        static::rep('W', $string, $this->weeks);
        static::rep('D', $string, $this->days);
        static::rep('H', $string, $this->hours);
        static::rep('M', $string, $this->min);
        static::rep('S', $string, $this->sec);

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