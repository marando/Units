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

use \Marando\Units\Time;
use \Marando\Units\Distance;

/**
 * Represents a measure of velocity
 *
 * @property float    $ms  Velocity in meters per second (m/s)
 * @property float    $kms Velocity in kilometers per second (km/s)
 * @property float    $kmh Velocity in kilometers per hour (km/h)
 * @property float    $kmd Velocity in kilometers per day (km/d)
 * @property float    $mph Velocity in miles per hour (mph)
 * @property float    $pcy Velocity in parsecs per year (pc/y)
 * @property float    $aud Velocity in AU per day (AU/y)
 * @property Distance $dist
 * @property Time     $time
 */
class Velocity
{

    use Traits\SetUnitTrait,
      Traits\RoundingTrait,
      \Marando\Units\Traits\CopyTrait;

    //----------------------------------------------------------------------------
    // Constants
    //----------------------------------------------------------------------------

    /**
     * Represents the speed of light in a vacuum in m/s
     */
    const c_ms = 299792458;

    /**
     * Represents the value provided by Jean Meeus, being the number of km/s in
     * one pc/y (parsecs per year)
     */
    const kms_in_pcy = 977792;

    //----------------------------------------------------------------------------
    // Constructors
    //----------------------------------------------------------------------------

    /**
     * Creates a new velocity instance form distance and time components
     *
     * @param Distance $distance
     * @param Time     $time
     */
    public function __construct(Distance $distance, Time $time)
    {
        $this->dist = $distance;
        $this->time = $time;
    }

    // // // Static

    /**
     * Creates a new velocity instance from meters per second (m/s)
     *
     * @param  float $ms
     *
     * @return static
     */
    public static function ms($ms)
    {
        return (new static(Distance::m($ms), Time::sec(1)))->setUnit('ms');
    }

    /**
     * Creates a new velocity instance from kilometers per second (km/s)
     *
     * @param  float $kms
     *
     * @return static
     */
    public static function kms($kms)
    {
        return (new static(Distance::km($kms), Time::sec(1)))->setUnit('km/s');
    }

    /**
     * Creates a new velocity instance from kilometers per hour (km/h)
     *
     * @param  float $kmh
     *
     * @return static
     */
    public static function kmh($kmh)
    {
        return (new static(Distance::km($kmh),
          Time::hours(1)))->setUnit('km/h');
    }

    /**
     * Creates a new velocity instance from kilometers per day (km/d)
     *
     * @param  float $kmd
     *
     * @return static
     */
    public static function kmd($kmd)
    {
        return (new static(Distance::km($kmd),
          Time::days(1)))->setUnit('km/d');
    }

    /**
     * Creates a new velocity instance from miles per hour (mph)
     *
     * @param  float $mph
     *
     * @return static
     */
    public static function mph($mph)
    {
        return (new static(Distance::mi($mph),
          Time::hours(1)))->setUnit('mph');
    }

    /**
     * Creates a new velocity instance from astronomical units per day (AU/d)
     *
     * @param  float $aud
     *
     * @return static
     */
    public static function aud($aud)
    {
        return (new static(Distance::au($aud),
          Time::days(1)))->setUnit('au/d');
    }

    /**
     * Creates a new velocity instance from parsecs per year (pc/y)
     *
     * @param  float $ms
     *
     * @return static
     */
    public static function pcy($pcy, $year = Time::JulianYear)
    {
        return (new static(Distance::pc($pcy),
          Time::days($year)))->setUnit('pc/y');
    }

    //----------------------------------------------------------------------------
    // Properties
    //----------------------------------------------------------------------------

    /**
     * Holds the distance component of this instance
     *
     * @var Distance
     */
    protected $dist;

    /**
     * Holds the time component of this instance
     *
     * @var Time
     */
    protected $time;

    public function __get($name)
    {
        switch ($name) {
            case 'ms':
                return $this->dist->m / $this->time->sec;

            case 'kms':
                return $this->dist->km / $this->time->sec;

            case 'kmh':
                return $this->dist->km / $this->time->hours;

            case 'kmd':
                return $this->dist->km / $this->time->days;

            case 'mph':
                return $this->dist->mi / $this->time->hours;

            case 'pcy':
                return $this->kms / static::kms_in_pcy;

            case 'aud':
                return $this->dist->au / $this->time->days;

            case 'dist':
            case 'time':
                return $this->{$name};

            default:
                throw new \Exception("{$name} is not a valid property.");
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'dist':
            case 'time':
                $this->{$name} = $value;

            default:
                throw new \Exception("{$name} is not a valid property.");
        }
    }

    //----------------------------------------------------------------------------
    // Functions
    //----------------------------------------------------------------------------

    /**
     * Calculates the time required to travel the provided distance at the
     * velocity of this instance
     *
     * @param  Distance $dist
     *
     * @return Time
     */
    public function time(Distance $dist)
    {
        $time      = $this->time;
        $time->sec = ($dist->m / $this->dist->m) * $this->time->sec;

        return $time;
    }

    /**
     * Calculates the distance traveled in provided time duration at the
     * velocity of this instance
     *
     * @param  Time $time
     *
     * @return Distance
     */
    public function dist(Time $time)
    {
        // Find distance covered in provided time
        $dist = Distance::m($this->dist->m * $time->sec / $this->time->sec);

        return $dist;
    }

    /**
     * Adds another velocity to this instance
     *
     * @param  Velocity $b
     *
     * @return static
     */
    public function add(Velocity $b)
    {
        // Add the two instances in comparable units
        $c = Velocity::ms($this->ms + $b->ms);

        // Alter this instance
        $this->dist = $c->dist;
        $this->time = $c->time;

        return $this;
    }

    /**
     * Subtracts another velocity from this instance
     *
     * @param  Velocity $b
     *
     * @return static
     */
    public function subtract(Velocity $b)
    {
        // Add the two instances in comparable units
        $c = Velocity::ms($this->ms - $b->ms);

        // Alter this instance
        $this->dist = $c->dist;
        $this->time = $c->time;

        return $this;
    }

    // // // Protected

    /**
     * Sets the distance component of this instance
     *
     * @param Distance $distance
     */
    protected function setDistance(Distance $distance)
    {
        $this->properties['distance'] = $distance;
    }

    /**
     * Sets the time component of this instance
     *
     * @param Time $time
     */
    protected function setTime(Time $time)
    {
        $this->properties['time'] = $time;
    }

    // // // Overrides

    /**
     * Represents this instance as a string
     *
     * @return string
     */
    public function __toString()
    {
        switch (strtolower($this->unit)) {
            case 'kms':
            case 'km/s':
                return round($this->kms, $this->decimalPlaces) . ' km/s';

            case 'kmh':
            case 'kph':
            case 'km/h':
                return round($this->kmh, $this->decimalPlaces) . ' km/h';

            case 'mi/h':
            case 'mph':
                return round($this->mph, $this->decimalPlaces) . ' mph';

            case 'pcy':
            case 'pc/y':
                return round($this->pcy, $this->decimalPlaces) . ' pc/y';

            case 'aud':
            case 'au/d':
                return round($this->aud, $this->decimalPlaces) . ' AU/d';

            default:
            case 'ms':
            case 'm/s':
                return round($this->ms, $this->decimalPlaces) . ' m/s';
        }
    }

}

/*
// format works like this...
// do vector calculations to have dynamic units....
$velocity->format('%1.5f', 'km', 'h');
*/