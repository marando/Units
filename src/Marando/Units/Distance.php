<?php

namespace Marando\Units;

/**
 * Represents a measure of distance
 *
 * @property float $mm Distance in millimeters
 * @property float $cm Distance in centimeters
 * @property float $m  Distance in meters
 * @property float $km Distance in kilometers
 * @property float $au Distance in astronomical units
 * @property float $pc Distance in parsecs
 * @property float $ly Distance in light-years
 * @property float $mi Distance in miles
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Distance {

  use \Marando\Units\Traits\RoundingTrait,
      \Marando\Units\Traits\SetUnitTrait;

  //----------------------------------------------------------------------------
  // Constants
  //----------------------------------------------------------------------------

  /**
   * Number of centimeters in one meter
   */
  const cm_in_m = 1e2;

  /**
   * Number of meters in one astronomical unit
   */
  const m_in_AU = 149597870700;

  /**
   * Number of meters in one mile
   */
  const m_in_mi = 1609.344;

  /**
   * Number of meters in one kilometer
   */
  const m_in_km = 1e3;

  /**
   * Number of milimeters in one meter
   */
  const mm_in_m = 1e3;

  /**
   * Number of meters in one light year
   */
  const m_in_ly = 9.4607304725808E15;

  /**
   * Number of meters in one parsec
   */
  const m_in_pc = 3.085677582E16;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new distance instance from a number of meters
   * @param float $meters The number of meters.
   */
  protected function __construct($meters) {
    $this->m = $meters;

    // Store base definitions for properties that can be overriden
    $this->def = [
        'm/AU' => static::m_in_AU,
        'm/ly' => static::m_in_ly,
        'm/pc' => static::m_in_pc,
    ];
  }

  // // // Static

  /**
   * Creates a new distance instance from a number of millimeters
   * @param float $mm
   * @return static
   */
  public static function mm($mm) {
    return (new Distance($mm / static::mm_in_m))->setUnit('mm');
  }

  /**
   * Creates a new distance instance from a number of centimeters
   * @param float $cm
   * @return static
   */
  public static function cm($cm) {
    return (new Distance($cm / static::cm_in_m))->setUnit('cm');
  }

  /**
   * Creates a new distance instance from a number of meters
   * @param float $m
   * @return static
   */
  public static function m($m) {
    return (new Distance($m))->setUnit('m');
  }

  /**
   * Creates a new distance instance from a number of kilometers
   * @param float $km
   * @return static
   */
  public static function km($km) {
    return (new Distance($km * static::m_in_km))->setUnit('km');
  }

  /**
   * Creates a new distance instance from a number of miles
   * @param float $mi
   * @return static
   */
  public static function mi($mi) {
    return (new Distance($mi * static::m_in_mi))->setUnit('mi');
  }

  /**
   * Creates a distance instance from a number of astronomical units. Optionally
   * the definition of the astronomical unit can be overridden.
   *
   * @param float $au  The number of astronomical units
   * @param flost $def Optional definition of an astronomical unit
   *
   * @return static
   */
  public static function au($au, Distance $def = null) {
    $dist = new Distance(0);
    $dist->setUnit('au');

    if ($def)
      $dist->def['m/AU'] = $def->m;
    else
      $dist->def['m/AU'] = static::m_in_AU;

    $dist->m = $au * $dist->def['m/AU'];
    return $dist;
  }

  /**
   * Creates a new distance instance from a number of parsecs
   * @param float $pc
   * @return static
   */
  public static function pc($pc, Distance $au = null) {
    $dist = new Distance(0);
    $dist->setUnit('pc');

    if ($au)
      $dist->def['m/AU'] = $au->m;
    else
      $dist->def['m/AU'] = static::m_in_AU;

    // Find the number of meters in one parsec
    $au_in_pc          = 6.48e5 / Angle::Pi;
    $dist->def['m/pc'] = $au_in_pc * $dist->def['m/AU'];

    $dist->m = $pc * $dist->def['m/pc'];
    return $dist;
  }

  /**
   * Creates a new distance instance from a number of light-years. Optionally
   * the velocity of the speed of light in a vacuum and the number of days in a
   * year can be overridden.
   *
   * @param float    $ly   Number of light years to store
   * @param Velocity $c    Speed of light in vacuum in m/s
   * @param float    $year Number of days per year, default is julian year
   *
   * @return static
   */
  public static function ly($ly, Velocity $c = null, $year = 365.25) {
    $dist = new Distance(0);
    $dist->setUnit('ly');

    if ($c)
      $dist->def['c'] = $c->ms;
    else
      $dist->def['c'] = Velocity::c_ms;

    // Find the number of meters in one light-year
    $secInYear         = Time::SEC_IN_DAY * $year;
    $dist->def['m/ly'] = $secInYear * $dist->def['c'];

    $dist->m = $ly * $dist->def['m/ly'];
    return $dist;
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * Holds overriden unit definitions for this instance
   * @var array
   */
  private $def = [];

  /**
   * Stores the properties of this instance.
   * @var array
   */
  protected $properties = [];

  public function __get($name) {
    switch ($name) {
      case 'mm':
        return $this->m * static::mm_in_m;

      case 'cm':
        return $this->m * static::cm_in_m;

      case 'm':
        return $this->properties[$name];

      case 'km':
        return $this->m / static::m_in_km;

      case 'mi':
        return $this->m / static::m_in_mi;

      case 'au':
        return $this->m / $this->def['m/AU'];

      case 'pc':
        return $this->m / $this->def['m/pc'];

      case 'ly':
        return $this->m / $this->def['m/ly'];
    }
  }

  public function __set($name, $value) {
    switch ($name) {
      case 'm':
        $this->properties[$name] = $value;
        break;

      default:
        throw new \Exception("{$name} is not a valid or writable property.");
    }
  }

  //----------------------------------------------------------------------------
  // Functions
  //----------------------------------------------------------------------------
  // // // Overrides

  /**
   * Represents this instance as a string
   * @return string
   */
  public function __toString() {
    $value = $this->m;
    $units = 'm';

    $unit = strtolower($this->unit);
    if (($unit == null && $this->pc > 1) || $unit == 'pc') {
      $value = $this->pc;
      $units = 'pc';
    }
    else if (($unit == null && $this->au > 1 ) || $unit == 'au') {
      $value = $this->au;
      $units = 'AU';
    }
    else if (($unit == null && $this->km > 1 ) || $unit == 'km') {
      $value = $this->km;
      $units = 'km';
    }
    else if (($unit == null && $this->mi > 1 ) || $unit == 'mi') {
      $value = $this->mi;
      $units = 'mi';
    }
    else if (($unit == null && $this->m > 1 ) || $unit == 'm') {
      $value = $this->m;
      $units = 'm';
    }
    else if (($unit == null && $this->cm > 1) || $unit == 'cm') {
      $value = $this->cm;
      $units = 'cm';
    }
    else if (($unit == null && $this->mm > 1) || $unit == 'mm') {
      $value = $this->mm;
      $units = 'mm';
    }

    $value = number_format($value, $this->decimalPlaces, '.', ',');
    return "{$value} {$units}";
  }

}
