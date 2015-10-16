<?php

namespace Marando\Units;

/**
 * Represents a measure of distance.
 *
 * @property float $mm The distance in millimeters
 * @property float $cm The distance in centimeters
 * @property float $m  The distance in meters
 * @property float $km The distance in kilometers
 * @property float $au The distance in astronomical units
 * @property float $pc The distance in parsecs
 * @property float $ly The distance in light-years
 * @property float $mi The distance in miles
 */
class Distance {

  use \Marando\Units\Traits\RoundingTrait,
      \Marando\Units\Traits\SetUnitTrait;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new distance instance from a number of meters.
   * @param float $meters The number of meters.
   */
  public function __construct($meters) {
    $this->m = $meters;
  }

  // // // Static

  /**
   * Creates a new distance instance from a number of millimeters.
   * @param float $mm The number of millimeters.
   * @return Distance
   */
  public static function mm($mm) {
    return new Distance($mm * 1e3);
  }

  /**
   * Creates a new distance instance from a number of centiimeters.
   * @param float $cm The number of centimeters.
   * @return Distance
   */
  public static function cm($cm) {
    return new Distance($cm * 1e2);
  }

  /**
   * Creates a new distance instance from a number of meters.
   * @param float $m The number of meters.
   * @return Distance
   */
  public static function m($m) {
    return new Distance($m);
  }

  /**
   * Creates a new distance instance from a number of kilometers.
   * @param float $km The number of kilometers.
   * @return Distance
   */
  public static function km($km) {
    return new Distance($km * 1e3);
  }

  /**
   * Creates a new distance instance from a number of miles.
   * @param float $mi The number of miles.
   * @return Distance
   */
  public static function mi($mi) {
    return new Distance($mi * 0.000621371192);
  }

  /**
   * Creates a new distance instance from a number of astronomical units.
   * @param float $au The number of astronomical units.
   * @return Distance
   */
  public static function au($au) {
    return new Distance($au * 149597870700);
  }

  /**
   * Creates a new distance instance from a number of parsecs.
   * @param float $pc The number of parsecs.
   * @return Distance
   */
  public static function pc($pc) {
    return new Distance($pc * 3.08567758e16);
  }

  /**
   * Creates a new distance instance from a number of light-years.
   * @param float $ly The number of light-years.
   * @return static
   */
  public static function ly($ly) {
    return new static($ly * 9460730472580800);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * Stores the properties of this instance.
   * @var array
   */
  protected $properties = [];

  public function __get($name) {
    switch ($name) {
      case 'mm':
        return $this->m * 1e3;

      case 'cm':
        return $this->m * 1e2;

      case 'm':
        return $this->properties[$name];

      case 'km':
        return $this->m * 1e-3;

      case 'mi':
        return $this->m * 0.000621371;

      case 'au':
        return $this->m / 149597870700;

      case 'pc':
        return $this->m / 3.08567758e16;

      case 'ly':
        return $this->m / 9460730472580800;
    }
  }

  public function __set($name, $value) {
    switch ($name) {
      case 'm':
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
   * Represents this instance as a string.
   * @return string
   */
  public function __toString() {
    $value = $this->m;
    $units = 'm';

    if (($this->unit == null && $this->pc > 1) || $this->unit == 'pc') {
      $value = $this->pc;
      $units = 'pc';
    }
    else if (($this->unit == null && $this->au > 1 ) || $this->unit == 'au') {
      $value = $this->au;
      $units = 'AU';
    }
    else if (($this->unit == null && $this->km > 1 ) || $this->unit == 'km') {
      $value = $this->km;
      $units = 'km';
    }
    else if (($this->unit == null && $this->mi > 1 ) || $this->unit == 'mi') {
      $value = $this->mi;
      $units = 'mi';
    }
    else if (($this->unit == null && $this->m > 1 ) || $this->unit == 'm') {
      $value = $this->m;
      $units = 'm';
    }
    else if (($this->unit == null && $this->cm > 1) || $this->unit == 'cm') {
      $value = $this->cm;
      $units = 'cm';
    }
    else if (($this->unit == null && $this->mm > 1) || $this->unit == 'mm') {
      $value = $this->mm;
      $units = 'mm';
    }

    $value = number_format($value, $this->decimalPlaces, '.', ',');
    return "{$value} {$units}";
  }

}
