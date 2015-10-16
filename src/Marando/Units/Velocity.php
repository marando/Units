<?php

namespace Marando\Units;

/**
 * Represents a measure of velocity
 *
 * @property float $ms  The velocity in meters per second (m/s)
 * @property float $kms The velocity in kilometers per second (km/s)
 * @property float $kmh The velocity in kilometers per hour (km/h)
 * @property float $mph The velocity in miles per hour (mph)
 * @property float $pcy The velocity in parsecs per year (pc/y)
 * @property float $aud The velocity in AU per day (AU/y)
 *
 */
class Velocity {

  use \Marando\Units\Traits\RoundingTrait,
      \Marando\Units\Traits\SetUnitTrait;

  const c_ms = 299792458;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new instance from a velocity in meters per second (m/s)
   * @param type $ms The number of meters
   */
  public function __construct($ms) {
    $this->ms = $ms;
  }

  /**
   * Creates a new instance from a velocity in meters per second (m/s)
   * @param float $ms The number of meters
   * @return static
   */
  public static function ms($ms) {
    return new static($ms);
  }

  /**
   * Creates a new instance from a velocity in kilometers per second (km/s)
   * @param float $kms The number of kilometers
   * @return static
   */
  public static function kms($kms) {
    return new static($kms * 1e3);
  }

  /**
   * Creates a new instance from a velocity in kilometers per hour (km/h)
   * @param float $kmh The number of kilometers
   * @return static
   */
  public static function kmh($kmh) {
    return new static($kmh * 1e3 / 60 / 60);
  }

  /**
   * Creates a new instance from a velocity in miles per hour (mph)
   * @param type $mph The number of miles per hour
   * @return \static
   */
  public static function mph($mph) {
    return new static($mph * 1e3 / 60 / 60 / 0.621371192);
  }

  /**
   * Creates a new instance from a velocity in astronomical units per day (au/d)
   * @param type $aud The number of astronomical units per day
   * @return \static
   */
  public static function aud($aud) {
    return new static($aud * 149597870700 / 86400);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  protected $properties = [];

  public function __get($name) {
    switch ($name) {
      case 'ms':
        return $this->properties['ms'];

      case 'kms':
        return $this->ms * 1e-3;

      case 'kmh':
        return $this->ms * 1e-3 * 60 * 60;

      case 'mph':
        return $this->ms * 1e-3 * 60 * 60 * 0.621371192;

      case 'pcy':
        return $this->kms / 977792;

      case 'aud':
        return $this->ms / 149597870700 * 86400;
    }
  }

  public function __set($name, $value) {
    switch ($name) {
      case 'ms':
        $this->properties[$name] = $value;
        break;

      default:
        throw new \Exception("{$name} is not a valid property.");
    }
  }

  //----------------------------------------------------------------------------
  // Functions
  //----------------------------------------------------------------------------

  /**
   * Represents the instance as a string.
   * @return string
   */
  public function __toString() {
    switch (strtolower($this->unit)) {
      case 'kms':
      case 'km/s':
        return round($this->kms, $this->decimalPlaces) . ' km/s';

      case 'mph':
        return round($this->mph, $this->decimalPlaces) . ' mph';

      case 'kmh':
      case 'kph':
      case 'km/h':
        return round($this->kmh, $this->decimalPlaces) . ' km/h';

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


