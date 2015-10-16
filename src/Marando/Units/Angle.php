<?php

namespace Marando\Units;

use Marando\Units\Time;

/**
 * Represents a gemoetric Angle.
 *
 * @property float $deg The angle expressed in degrees.
 * @property float $rad The angle expressed in radians.
 * @property int   $d   The integer degree value of the angle.
 * @property int   $m   The integer minute value of the angle.
 * @property float $s   The second value of the angle.
 */
class Angle {

  use \Marando\Units\Traits\RoundingTrait;

  //----------------------------------------------------------------------------
  // Constants
  //----------------------------------------------------------------------------

  /**
   * Represents the cosine of a small angle, which is the angle at which
   * trigonometric functions lose accuracy.
   */
  const SmallAngleCosine = 0.9999957692;

  /**
   * Represents a small angle in degrees, which is the angle at which
   * trigonometric functions lose accuracy.
   */
  const SmallAngleDec = 0.1666666667;

  /**
   * Represents a small angle in radians, which is the angle at which
   * trigonometric functions lose accuracy.
   */
  const SmallAngleRad = 0.002908882087;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new Angle instance.
   */
  protected function __construct() {

  }

  // // // Static

  /**
   * Creates a new angle from a number of degrees.
   * @param float $deg The number of degrees.
   * @return static
   */
  public static function fromDeg($deg) {
    $angle      = new static();
    $angle->deg = $deg;
    $angle->rad = deg2rad($deg);

    return $angle;
  }

  /**
   * Creates a new angle from a number of radians.
   * @param float $rad The number of radians
   * @return static
   */
  public static function fromRad($rad) {
    $angle      = new static();
    $angle->rad = $rad;
    $angle->deg = rad2deg($rad);

    return $angle;
  }

  /**
   * Creates a new angle from degree, minute and second components.
   * @param int   $d The integer degree value of the angle.
   * @param int   $m The integer minute value of the angle.
   * @param float $s The second value of the angle.
   * @return static
   */
  public static function fromDMS($d, $m, $s) {
    if ($d < 0 || $m < 0 || $s < 0) {
      $deg = $d - (abs($m) / 60) - (abs($s) / 3600);
      return static::fromDeg($deg);
    }
    else {
      $deg = $d + (abs($m) / 60) + (abs($s) / 3600);
      return static::fromDeg($deg);
    }
  }

  /**
   * Creates a new angle from a duration of time within an interval, the default
   * being the number of seconds in one day.
   * @param Time  $time     The time duration.
   * @param float $interval The interval in seconds.
   * @return static
   */
  public static function fromTime($time, $interval = Time::SEC_IN_DAY) {
    return static::fromDeg($time->seconds / $interval * 360)->norm();
  }

  public static function Pi() {
    return Angle::fromDeg(180);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * Holds the the properties of this instance.
   * @var array
   */
  protected $properties = [];

  public function __get($name) {
    switch ($name) {
      case 'deg':
      case 'rad':
        return $this->properties[$name];

      case 'd':
        return intval($this->deg);

      case 'm':
        return $this->calcMinutes();

      case 's':
        return $this->calcSeconds();

      default:
        throw new Exception("{$name} is not a valid property.");
    }
  }

  public function __set($name, $value) {
    switch ($name) {
      case 'deg':
      case 'rad':
        $this->properties[$name] = $value;
        break;

      default:
        throw new Exception("{$name} is not a valid property.");
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
    $sign = $this->deg < 0 && $this->d == 0 ? '-' : '';

    $d = $this->d;
    $m = abs($this->m);
    $s = abs($this->s);

    $sI = intval($s);
    $sD = str_replace('0.', '', round(($s - $sI), $this->decimalPlaces));

    if ($this->decimalPlaces > 0)
      return "{$sign}{$d}°{$m}'{$sI}\".{$sD}";
    else {
      return "{$sign}{$d}°{$m}'{$sI}\"";
    }
  }

  /**
   * Normalizes the degrees in this instance.
   * @param type $lBound The lower bound.
   * @param type $uBound The upper bound.
   * @return Angle This instance normalized.
   */
  public function norm($lBound = 0, $uBound = 360) {
    $sign = $this->deg < 0 ? -1 : 1;

    if ($this->deg <= $uBound && $this->deg >= $lBound) {
      return $this;
    }
    else {
      $rev       = intval($this->deg / $uBound);
      $this->deg = $this->deg - $rev * $uBound;

      while ($this->deg > $uBound) {
        $this->deg -= $uBound;
      }

      while ($this->deg < $lBound) {
        $this->deg += $uBound;
      }

      $this->deg = $this->deg == 0 ? 360 * $sign : $this->deg;
      $this->rad = deg2rad($this->deg);

      return $this;
    }
  }

  /**
   * Converts this instance to a duration of time for a specific interval in
   * seconds, the default being the number of seconds in one day.
   * @return Time The time duration representative of this instance.
   */
  public function toTime($interval = Time::SEC_IN_DAY) {
    return new Time(($this->deg / 360) * $interval);
  }

  /**
   * Adds an angle to this instance
   * @param Angle $angle The angle to add.
   * @return Angle The resulting angle.
   */
  public function add(Angle $angle) {
    return Angle::fromDeg($this->deg + $angle->deg);
  }

  /**
   * Multiplies an angle to this instance
   * @param Angle $angle The angle to multiplu
   * @return Angle The resulting angle
   */
  public function multiply(Angle $angle) {
    return Angle::fromDeg($this->deg * $angle->deg);
  }

  /**
   * Subtracts an angle from this instance.
   * @param Angle $angle The angle to subtract.
   * @return Angle The resulting angle.
   */
  public function subtract(Angle $angle) {
    return Angle::fromDeg($this->deg - $angle->deg);
  }

  public function negate() {
    return $this->multiply(Angle::fromDeg(-1));
  }

  /**
   * Returns if the angle is small, such that the accuracy of trignonometric
   * functions is questionable.
   *
   * @return bool
   */
  public function isSmall() {
    return $this->rad < static::SmallAngleRad;
  }

  /**
   * Arc tangent of two variables
   *
   * @param float|Angle $y Dividend parameter
   * @param float|Angle $x Divisor parameter
   * @return Angle
   */
  public static function atan2($y, $x) {
    $y = $y instanceof Angle ? $y->rad : $y;
    $x = $x instanceof Angle ? $x->rad : $x;

    return Angle::fromRad(atan2($y, $x));
  }

  // // // Protected

  /**
   * Calculates the integer value of minutes in this instance.
   * @return int The number of minutes.
   */
  protected function calcMinutes() {
    return intval(($this->deg - $this->d) * 60);
  }

  /**
   * Calculates the integer value of seconds in this instance.
   * @return int The number of seconds.
   */
  protected function calcSeconds() {
    return ($this->deg - $this->d - $this->m / 60) * 3600;
  }

}
