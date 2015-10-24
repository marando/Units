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

use \Exception;
use \Marando\Units\Time;

/**
 * Represents a geometric angle measurement
 *
 * @property float $deg    Angle expressed in degrees
 * @property float $rad    Angle expressed in radians
 * @property int   $d      Integer degree segment of the angle
 * @property int   $m      Integer minute segment of the angle
 * @property float $s      Second segment of the angle with decimals
 * @property float $arcmin Angle expressed in arcminutes
 * @property float $arcsec Angle expressed in arcseconds
 * @property float $mas    Angle expressed in milliarcseconds
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
   * @param  float  $deg
   * @return static
   */
  public static function deg($deg) {
    return new static($deg, deg2rad($deg));
  }

  /**
   * Creates a new angle from a number of radians
   * @param  float  $rad
   * @return static
   */
  public static function rad($rad) {
    return new static(rad2deg($rad), $rad);
  }

  /**
   * Creates a new angle from degree, minute and second components
   *
   * @param  int    $d Degree value
   * @param  int    $m Minute value
   * @param  float  $s Second value
   * @return static
   */
  public static function dms($d, $m, $s) {
    if ($d < 0 || $m < 0 || $s < 0)
    // Negative angle
      return static::deg($d - (abs($m) / 60) - (abs($s) / 3600));
    else
    // Positive angle
      return static::deg($d + (abs($m) / 60) + (abs($s) / 3600));
  }

  /**
   * Creates a new angle from milliarcseconds
   *
   * @param  float  $mas
   * @return static
   */
  public static function mas($mas) {
    return static::deg($mas / 3.6e6);
  }

  /**
   * Creates a new angle from arcminutes
   *
   * @param  float  $arcmin
   * @return static
   */
  public static function arcmin($arcmin) {
    return static::deg($arcmin / 60);
  }

  /**
   * Creates a new angle from arcseconds
   *
   * @param  float  $arcsec
   * @return static
   */
  public static function arcsec($arcsec) {
    return static::deg($arcsec / 3600);
  }

  /**
   * Creates a new angle from a time duration within a specified interval, the
   * default being the number of seconds in one day. This is usefun in
   * astronomy applications.
   *
   * @param  Time   $time     Time duration
   * @param  float  $interval Time interval
   * @return static
   */
  public static function time($time, $interval = Time::SEC_IN_DAY) {
    return static::deg($time->sec / $interval * 360)->norm();
  }

  /**
   * Creates a new angle based on the value of π radians
   * @return static
   */
  public static function Pi() {
    return static::rad(static::Pi);
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

      case 'mas':
        return $this->calcMAS();

      case 'arcsec':
        return $this->deg * 3600;

      case 'arcmin':
        return $this->deg * 60;

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
   * Normalizes the degrees in this instance to a specified interval
   *
   * @param  float $lBound Lower bound
   * @param  float $uBound Upper bound
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
   * @param  Angle  $angle
   * @return static
   */
  public function add(Angle $angle) {
    $this->deg = $this->deg + $angle->deg;
    $this->rad = $this->rad + $angle->rad;

    return $this;
  }

  /**
   * Multiplies an angle to this instance
   *
   * @param  Angle  $angle
   * @return static
   */
  public function multiply(Angle $angle) {
    $this->deg = $this->deg * $angle->deg;
    $this->rad = $this->rad * $angle->rad;

    return $this;
  }

  /**
   * Subtracts an angle from this instance
   * @param  Angle  $angle
   * @return static
   */
  public function subtract(Angle $angle) {
    $this->deg = $this->deg - $angle->deg;
    $this->rad = $this->rad - $angle->rad;

    return $this;
  }

  /**
   * Negates the value of this instance
   * @return Angle
   */
  public function negate() {
    $this->deg = $this->deg * -1;
    $this->rad = $this->rad * -1;

    return $this;
  }

  /**
   * Copies this instance
   * @return static
   */
  public function copy() {
    return clone $this;
  }

  /**
   * Returns a new angle from the arc tangent of two other angle instances or
   * float values expressed in radians
   *
   * @param  float|Angle $y Dividend parameter
   * @param  float|Angle $x Divisor parameter
   * @return Angle
   */
  public static function atan2($y, $x) {
    $y = $y instanceof Angle ? $y->rad : $y;
    $x = $x instanceof Angle ? $x->rad : $x;

    return Angle::rad(atan2($y, $x));
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

  /**
   * Calculates the angle as expressed in milliarcseconds
   * @return float
   */
  protected function calcMAS() {
    return $this->deg * 3.6e6;
  }

  // // // Overrides

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
   * For backwards compatibility
   */
  public static function __callStatic($name, $arguments) {
    switch ($name) {
      case 'fromTime':
        return call_user_func_array([static::class, 'time'], $arguments);
    }
  }

}
