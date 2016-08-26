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

use Marando\Units\Distance;
use Marando\Units\Time;

/**
 * Represents a velocity.
 *
 * @property double   $ms   Meters per second, m/s
 * @property double   $kms  Kilometers per second, km/s
 * @property double   $kmh  Kilometers per hour, km/h
 * @property double   $kmd  Kilometers per day, km/d
 * @property double   $fts  Feet per second, ft/s
 * @property double   $aud  Astronomical units per day, au/d
 * @property double   $pcy  Parsecs per year, pc/y
 * @property double   $mph  Miles per hour, mph
 * @property Distance $dist Distance component
 * @property Time     $time Time component
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Velocity2
{

    //--------------------------------------------------------------------------
    // Constants
    //--------------------------------------------------------------------------

    /**
     * Default string format.
     */
    const FORMAT_DEFAULT = '%1.3f ';

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Creates a new velocity from a distance and time.
     *
     * @param Distance $dist
     * @param Time     $time
     */
    public function __construct(Distance $dist, Time $time)
    {
        // Set distance and time, and make sure time is positive.
        $this->dist = $dist;
        $this->time = $time->hours < 0 ? $time->neg() : $time;

        // Use this by default.
        $this->format = static::FORMAT_DEFAULT . 'm/s';
    }

    // // // Static

    /**
     * Creates a new velocity from a value and velocity unit.
     *
     * @param $value Value of the velocity
     * @param $units Unit of measurement, e.g. m/s
     *
     * @return static
     */
    public static function create($value, $units)
    {
        // Explode units...
        list($distUnit, $timeUnit) = static::explUnits($units);

        // Create distance and time placeholders.
        $dist = Distance::m(0);
        $time = Time::sec(0);

        // Set each distance/time unit.
        $dist->{$distUnit} = $value;
        $time->{$timeUnit} = 1;

        // Create the velocity and set the format.
        $velocity         = new Velocity2($dist, $time);
        $velocity->format = static::FORMAT_DEFAULT . $units;

        return $velocity;
    }

    /**
     * Creates a new velocity from a number of kilometers per second, km/s
     *
     * @param $kms
     *
     * @return static
     */
    public static function kms($kms)
    {
        return static::create($kms, 'km/s');
    }

    /**
     * Creates a new velocity from a number of kilometers per hour, km/h
     *
     * @param $kmh
     *
     * @return static
     */
    public static function kmh($kmh)
    {
        return static::create($kmh, 'km/h');
    }

    /**
     * Creates a new velocity from a number of kilometers per day, km/d
     *
     * @param $kmd
     *
     * @return static
     */
    public static function kmd($kmd)
    {
        return static::create($kmd, 'km/d');
    }

    /**
     * Creates a new velocity from a number of meters per second, m/s
     *
     * @param $ms
     *
     * @return static
     */
    public static function ms($ms)
    {
        return static::create($ms, 'm/s');
    }

    /**
     * Creates a new velocity from a number of miles per hours, mph
     *
     * @param $mph
     *
     * @return static
     */
    public static function mph($mph)
    {
        return static::create($mph, 'mph');
    }

    /**
     * Creates a new velocity from a number of feet per second, ft/s
     *
     * @param $fts
     *
     * @return static
     */
    public static function fts($fts)
    {
        return static::create($fts, 'ft/s');
    }

    /**
     * Creates a new velocity from a number of astronomical units per day, au/d
     *
     * @param $aud
     *
     * @return static
     */
    public static function aud($aud)
    {
        return static::create($aud, 'au/d');
    }

    /**
     * Creates a new velocity from a number of parsecs per year, pc/y
     *
     * @param $pcy
     *
     * @return static
     */
    public static function pcy($pcy)
    {
        return static::create($pcy, 'pc/s');
    }

    /**
     * Creates a new velocity instance with the speed of light in a vacuum.
     *
     * @return static
     */
    public static function c()
    {
        return static::ms(299792458);
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    /**
     * Distance component of this velocity.
     *
     * @var Distance
     */
    private $dist;

    /**
     * Time component of this velocity.
     *
     * @var Time
     */
    private $time;

    /**
     * String format of this velocity.
     *
     * @var string
     */
    private $format;

    public function __get($name)
    {
        switch ($n = $name) {
            case 'ms':
                return $this->units(substr($n, 0, 1) . '/' . substr($n, 1, 1));

            case 'kms':
            case 'kmh':
            case 'kmd':
            case 'fts':
            case 'aud':
            case 'pcy':
                return $this->units(substr($n, 0, 2) . '/' . substr($n, 2, 1));

            case 'mph':
                return $this->units('mph');

            case 'dist':
            case 'time':
                return $this->{$name};
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'dist':
            case 'time' :
                $this->{$name} = $value;
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    /**
     * Gets the value of this instance at the specified units.
     *
     * @param      $units  Units, e.g. m/s
     * @param bool $string True to return as string for higher precision
     *
     * @return double|string
     */
    public function units($units, $string = false)
    {
        // Explode units
        list($distUnit, $timeUnit) = static::explUnits($units);

        // Get and format the distance and time unit values
        $distVal = $this->dist->unit($distUnit);
        $timeVal = $this->time->{$timeUnit};
        $distVal = number_format($distVal, 99, '.', '');
        $timeVal = number_format($timeVal, 99, '.', '');

        // Calculate the velocity value
        $velocity = bcdiv($distVal, $timeVal, 999);
        $velocity = static::removeTrailingZeros($velocity);

        // Return either string or double
        return $string ? $velocity : (double)$velocity;
    }

    /**
     * Formats this instance as a string with the provided format.
     *
     * @param $format Format, e.g. %1.3f m/s
     *
     * @return string
     */
    public function format($format)
    {
        // Sprintf and unit regex
        $pattern = '/(%(?:\d+\$)?[+-]?(?:[ 0]|\'.{1})?-?\d*(?:\.\d+)?[bcdeEufFgGosxX])([ ]*)(.*)/';

        // Check if string has sprintf and unit...
        if (preg_match_all($pattern, $format, $m)) {
            $sprintf = $m[1][0];
            $space   = $m[2][0];
            $units   = $m[3][0];

            $velocity = $this->units($units);

            // Should just display as scientific notation?
            if (sprintf($sprintf, $velocity) == 0 || $velocity > 10e9) {
                // Change sprintf to use e for scientific notation
                $sprintf = preg_replace('/[bcdeEufFgGosxX]/', 'e', $sprintf);
            }

            // Return format
            return sprintf($sprintf, $velocity) . $space . $units;
        }
    }

    /**
     * Adds another velocity to this instance and returns a new instance with
     * the sum.
     *
     * @param Velocity2 $b Velocity to add
     *
     * @return static Sum of the two velocity instances
     */
    public function add(Velocity2 $b)
    {
        $dist     = $this->dist->add($b->dist);
        $velocity = new Velocity2($dist, $this->time);

        $velocity->format = $this->format;

        return $velocity;
    }

    /**
     * Subtracts another velocity from this instance and returns a new instance
     * with the difference.
     *
     * @param Velocity2 $b Velocity to subtract
     *
     * @return static Difference of the two velocity instances
     */
    public function sub(Velocity2 $b)
    {
        $dist     = $this->dist->sub($b->dist);
        $velocity = new Velocity2($dist, $this->time);

        $velocity->format = $this->format;

        return $velocity;
    }

    /**
     * Calculates the time required to travel the provided distance at the
     * velocity of this instance.
     *
     * @param Distance $dist
     *
     * @return Time
     */
    public function time(Distance $dist)
    {
        return Time::sec(($dist->m / $this->dist->m) * $this->time->sec);
    }

    /**
     * Calculates the distance traveled in the provided time at the velocity of
     * this instance.
     *
     * @param Time $time
     *
     * @return Distance
     */
    public function dist(Time $time)
    {
        return Distance::m($this->dist->m * $time->sec / $this->time->sec);
    }

    // // // Private

    /**
     * Explodes a string of units divided by / and converts them to the
     * appropriate value for usage in this class.
     *
     * @param $units
     *
     * @return array
     */
    private static function explUnits($units)
    {
        $units = explode('/', $units);
        if ($units[0] == 'mph') {
            $distUnit = 'mi';
            $timeUnit = 'hours';
        } elseif (count($units) == 2) {
            //Get units
            $distUnit = $units[0];
            $timeUnit = static::findTimeUnit($units[1]);
        }

        return [$distUnit, $timeUnit];
    }

    /**
     * For a time unit expressed as a string returns the string value that will
     * provide that unit as a property of the Time class.
     *
     * @param $unit
     *
     * @return string
     */
    private static function findTimeUnit($unit)
    {
        switch (strtolower($unit)) {
            case 'y':
            case 'yr':
            case 'year':
            case 'years':
                return 'years';

            case 'w':
            case 'wk':
            case 'week':
            case 'weeks':
                return 'weeks';

            case 'd':
            case 'day':
            case 'days':
                return 'days';

            case 'h':
            case 'hour':
            case 'hours':
                return 'hours';

            case 'm':
            case 'min':
            case 'minutes':
                return 'min';

            case 's':
            case 'sec':
            case 'seconds':
                return 'sec';
        }
    }

    // // // Static

    /**
     * Removes trailing zeros for a numeric value expressed as a string.
     *
     * @param $num
     *
     * @return string
     */
    private static function removeTrailingZeros($num)
    {
        // Only do this if number has a decimal value.
        if (strstr($num, '.')) {
            $num  = rtrim($num, '0');
            $last = substr($num, strlen($num) - 1, 1) == '.';
            $num  = $last ? substr($num, 0, strlen($num) - 1) : $num;

            return $num;
        } else {
            // No decimal value, don't modify number
            return $num;
        }
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
