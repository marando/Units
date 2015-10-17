<?php

namespace Marando\Units;

use \Marando\Units\Time;
use \Marando\Units\Distance;

/**
 * Represents a measure of velocity
 *
 * @property float $ms  Velocity in meters per second (m/s)
 * @property float $kms Velocity in kilometers per second (km/s)
 * @property float $kmh Velocity in kilometers per hour (km/h)
 * @property float $kmd Velocity in kilometers per day (km/d)
 * @property float $mph Velocity in miles per hour (mph)
 * @property float $pcy Velocity in parsecs per year (pc/y)
 * @property float $aud Velocity in AU per day (AU/y)
 */
class Velocity {

  use Traits\SetUnitTrait,
      Traits\RoundingTrait;

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
   * @param Distance $distance
   * @param Time     $time
   */
  public function __construct(Distance $distance, Time $time) {
    $this->distance = $distance;
    $this->time     = $time;
  }

  // // // Static

  /**
   * Creates a new velocity instance from meters per second (m/s)
   * @param float $ms
   * @return static
   */
  public static function ms($ms) {
    return new static(Distance::m($ms), Time::fromSeconds(1));
  }

  /**
   * Creates a new velocity instance from kilometers per second (km/s)
   * @param float $kms
   * @return static
   */
  public static function kms($kms) {
    return new static(Distance::km($kms), Time::fromSeconds(1));
  }

  /**
   * Creates a new velocity instance from kilometers per hour (km/h)
   * @param float $kmh
   * @return static
   */
  public static function kmh($kmh) {
    return new static(Distance::km($kmh), Time::fromHours(1));
  }

  /**
   * Creates a new velocity instance from kilometers per day (km/d)
   * @param float $kmd
   * @return static
   */
  public static function kmd($kmd) {
    return new static(Distance::km($kmd), Time::fromDays(1));
  }

  /**
   * Creates a new velocity instance from miles per hour (mph)
   * @param float $mph
   * @return static
   */
  public static function mph($mph) {
    return new static(Distance::mi($mph), Time::fromHours(1));
  }

  /**
   * Creates a new velocity instance from astronomical units per day (AU/d)
   * @param float $aud
   * @return static
   */
  public static function aud($aud) {
    return new static(Distance::au($aud), Time::fromDays(1));
  }

  /**
   * Creates a new velocity instance from parsecs per year (pc/y)
   * @param float $ms
   * @return static
   */
  public static function pcy($pcy, $year = Time::JulianYear) {
    return new static(Distance::pc($pcy), Time::fromDays($year));
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * Holds the distance component of this instance
   * @var Distance
   */
  protected $distance;

  /**
   * Holds the time component of this instance
   * @var Time
   */
  protected $time;

  public function __get($name) {
    switch ($name) {
      case 'ms':
        return $this->distance->m / $this->time->sec;

      case 'kms':
        return $this->distance->km / $this->time->sec;

      case 'kmh':
        return $this->distance->km / $this->time->hours;

      case 'kmd':
        return $this->distance->km / $this->time->days;

      case 'mph':
        return $this->distance->mi / $this->time->hours;

      case 'pcy':
        return $this->kms / static::kms_in_pcy;

      case 'aud':
        return $this->distance->au / $this->time->days;

      default:
        throw new \Exception("{$name} is not a valid property.");
    }
  }

  //----------------------------------------------------------------------------
  // Functions
  //----------------------------------------------------------------------------

  /**
   * Gets the distance component of this instance
   * @return Distance $distance
   */
  public function getDistance() {
    return $this->distance;
  }

  /**
   * Gets the time component of this instance
   * @return Time $time
   */
  public function getTime() {
    return $this->time;
  }

  // // // Protected

  /**
   * Sets the distance component of this instance
   * @param Distance $distance
   */
  protected function setDistance(Distance $distance) {
    $this->properties['distance'] = $distance;
  }

  /**
   * Sets the time component of this instance
   * @param Time $time
   */
  protected function setTime(Time $time) {
    $this->properties['time'] = $time;
  }

  // // // Overrides

  /**
   * Represents this instance as a string
   * @return string
   */
  public function __toString() {
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
