<?php

namespace Marando\Units;

use Marando\Units\Time;

/**
 * Represents a geometric angle measurement
 *
 * @property float $deg Angle expressed in degrees
 * @property float $rad Angle expressed in radians
 * @property int   $d   Integer degree segment of the angle
 * @property int   $m   Integer minute segment of the angle
 * @property float $s   Second segment of the angle with decimals
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Angle {

  use \Marando\Units\Traits\RoundingTrait;

  //----------------------------------------------------------------------------
  // Constants
  //----------------------------------------------------------------------------

  const Pi = 3.1415926535897932384626433832795028841971693993751058209749445923;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new angle instance
   */
  protected function __construct($deg = null, $rad = null) {
    $this->deg = $deg;
    $this->rad = $rad;
  }

  // // // Static

  /**
   * Creates a new angle from a number of degrees
   * @param float $deg
   * @return static
   */
  public static function fromDeg($deg) {
    return new static($deg, deg2rad($deg));
  }

  /**
   * Creates a new angle from a number of radians
   * @param float $rad
   * @return static
   */
  public static function fromRad($rad) {
    return new static(rad2deg($rad), $rad);
  }

  /**
   * Creates a new angle from degree, minute and second components
   *
   * @param int   $d Degree value
   * @param int   $m Minute value
   * @param float $s Second value
   *
   * @return static
   */
  public static function fromDMS($d, $m, $s) {
    if ($d < 0 || $m < 0 || $s < 0)
    // Negative angle
      return static::fromDeg($d - (abs($m) / 60) - (abs($s) / 3600));
    else
    // Positive angle
      return static::fromDeg($d + (abs($m) / 60) + (abs($s) / 3600));
  }

  /**
   * Creates a new angle from a time duration within a specified interval, the
   * default being the number of seconds in one day. This is usefun in
   * astronomy applications.
   *
   * @param Time  $time     Time duration
   * @param float $interval Time interval
   *
   * @return static
   */
  public static function fromTime($time, $interval = Time::SEC_IN_DAY) {
    return static::fromDeg($time->seconds / $interval * 360)->norm();
  }

  /**
   * Creates a new angle based on the value of π radians
   * @return static
   */
  public static function Pi() {
    return static::fromRad(static::Pi);
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
   * Represents this instance as a string
   * @return string
   */
  public function __toString() {
    // Figure out numeric sign of the instance
    $sign = $this->deg < 0 && $this->d == 0 ? '-' : '';

    // Obtain D M S
    $d = $this->d;
    $m = abs($this->m);
    $s = abs($this->s);

    // Split the seconds into integer and decimal components
    $sint = intval($s);
    $sdec = str_replace('0.', '', round(($s - $sint), $this->decimalPlaces));

    // Format the string depending on if the seconds has a decimal, value
    if ($this->decimalPlaces > 0)
      return "{$sign}{$d}°{$m}'{$sint}\".{$sdec}";
    else {
      return "{$sign}{$d}°{$m}'{$sint}\"";
    }
  }

  /**
   * Normalizes the degrees in this instance to a specified interval
   *
   * @param type $lBound Lower bound
   * @param type $uBound Upper bound
   *
   * @return Angle
   */
  public function norm($lBound = 0, $uBound = 360) {
    $sign = $this->deg < 0 ? -1 : 1;

    if ($this->deg <= $uBound && $this->deg >= $lBound) {
      return $this;
    }
    else {
      $revolutions = intval($this->deg / $uBound);
      $this->deg   = $this->deg - $revolutions * $uBound;

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
   * Converts this instance to a time duration for a specified interval of
   * seconds, the default being the number of seconds in one day.
   *
   * @return Time The time duration representative of this instance.
   */
  public function toTime($interval = Time::SEC_IN_DAY) {
    return new Time(($this->deg / 360) * $interval);
  }

  /**
   * Adds an angle to this instance
   *
   * @param Angle $angle
   * @return Angle The
   */
  public function add(Angle $angle) {
    return Angle::fromDeg($this->deg + $angle->deg);
  }

  /**
   * Multiplies an angle to this instance
   *
   * @param Angle $angle
   * @return Angle
   */
  public function multiply(Angle $angle) {
    return Angle::fromDeg($this->deg * $angle->deg);
  }

  /**
   * Subtracts an angle from this instance
   * @param Angle $angle
   * @return Angle
   */
  public function subtract(Angle $angle) {
    return Angle::fromDeg($this->deg - $angle->deg);
  }

  /**
   * Negates the value of this instance
   * @return Angle
   */
  public function negate() {
    return $this->multiply(Angle::fromDeg(-1));
  }

  /**
   * Returns a new angle from the arc tangent of two other angle instances or
   * float values expressed in radians
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
   * Calculates the integer minute segment of this instance
   * @return int
   */
  protected function calcMinutes() {
    return intval(($this->deg - $this->d) * 60);
  }

  /**
   * Calculates the integer second segment of this instance
   * @return int
   */
  protected function calcSeconds() {
    return ($this->deg - $this->d - $this->m / 60) * 3600;
  }

}
