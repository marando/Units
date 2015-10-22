<?php

namespace Marando\Units;

use Marando\Units\Angle;

/**
 * Represents a measurement of time duration
 *
 * @property float $h     Integer hour segment
 * @property float $m     Integer minute segment
 * @property float $s     Integer second segment
 * @property float $micro Integer micro (seconds decimal) segment
 * @property float $sec   Total number of seconds
 * @property float $min   Total number of minutes
 * @property float $hours Total number of hours
 * @property float $days  Total number of days
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Time {

  use \Marando\Units\Traits\SetUnitTrait,
      \Marando\Units\Traits\RoundingTrait;

  //----------------------------------------------------------------------------
  // Constants
  //----------------------------------------------------------------------------

  /**
   * The number of seconds in one minute
   */
  const SEC_IN_MIN = 60;

  /**
   * The number of seconds in one hour
   */
  const SEC_IN_HOUR = 3600;

  /**
   * The number of seconds in one day
   */
  const SEC_IN_DAY    = 86400;
  const JulianYear    = 365.25;
  const JulianCentury = 36525;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new Time instance from a number of seconds
   * @param float $sec
   */
  public function __construct($sec) {
    $this->sec = $sec;
  }

  /**
   * Creates a new Time instance from a number of seconds
   * @param float $sec
   * @return static
   */
  public static function sec($sec) {
    return (new static($sec))->setUnit('s');
  }

  /**
   * Creates a new Time instance from a number of minutes
   * @param float $min
   * @return static
   */
  public static function min($min) {
    return (new static($min * static::SEC_IN_MIN))->setUnit('m');
  }

  /**
   * Creates a new Time instance from a number of hours
   * @param float $hours
   * @return static
   */
  public static function hours($hours) {
    return (new static($hours * static::SEC_IN_HOUR))->setUnit('h');
  }

  /**
   * Creates a new Time instance from a number of days
   * @param float $days
   * @return static
   */
  public static function days($days) {
    return (new static($days * static::SEC_IN_DAY))->setUnit('d');
  }

  /**
   * Creates a new Time instance from hour minute and second components
   * @param int   $h
   * @param int   $m
   * @param float $s
   * @return static
   */
  public static function hms($h, $m, $s) {
    return new static($h * static::SEC_IN_HOUR + $m * static::SEC_IN_MIN + $s);
  }

  /**
   * Copies this instance
   * @return static
   */
  public function copy() {
    return clone $this;
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  public function __get($name) {
    switch ($name) {
      case 'sec':
        return $this->sec;

      case 'min':
        return $this->sec / Time::SEC_IN_MIN;

      case 'hours':
        return $this->sec / Time::SEC_IN_HOUR;

      case 'days':
        return $this->sec / Time::SEC_IN_DAY;

      case 'h':
        return intval($this->sec / Time::SEC_IN_HOUR);

      case 'm':
        return intval($this->sec % Time::SEC_IN_HOUR / Time::SEC_IN_MIN);

      case 's':
        return intval($this->sec % Time::SEC_IN_HOUR % Time::SEC_IN_MIN);

      case 'micro':
        return $this->sec - ($this->h * Time::SEC_IN_HOUR + $this->m *
                Time::SEC_IN_MIN + $this->s);
    }
  }

  //----------------------------------------------------------------------------
  // Functions
  //----------------------------------------------------------------------------

  /**
   * Subtracts from this instance another Time instance
   *
   * @param Time $time
   * @return static
   */
  public function subtract(Time $time) {
    $this->sec = $this->sec - $time->sec;
    return $this;
  }

  /**
   * Adds to this instance another Time instance
   *
   * @param Time $time
   * @return static
   */
  public function add(Time $time) {
    $this->sec = $this->sec + $time->sec;
    return $this;
  }

  /**
   * Converts this instance to an angle within a specified time interval, the
   * default being the number of seconds in one day. This is usefun in
   * astronomy applications.
   *
   * @return Angle
   */
  public function toAngle($interval = Time::SEC_IN_DAY) {
    return Angle::fromTime($this, $interval);
  }

  // // // Overrides

  /**
   * Represents this instance as a string
   * @return string
   */
  public function __toString() {
    switch (strtolower($this->unit)) {
      case 's':
      case 'sec':
      case 'second':
      case 'seconds':
        return round($this->sec, $this->decimalPlaces) . " sec";

      case 'm':
      case 'min':
      case 'minutes':
        return round($this->min, $this->decimalPlaces) . " min";

      case 'h':
      case 'hour':
      case 'hours':
        $hours = round($this->hours, $this->decimalPlaces);
        return $hours == 1 ? "{$hours} hour" : "{$hours} hours";

      case 'd':
      case 'day':
      case 'days':
        $days = round($this->days, $this->decimalPlaces);
        return $days == 1 ? "{$days} day" : "{$days} days";
    }

    $decimals = 4;
    $micro    = substr(round($this->micro, $decimals), 1, $decimals);

    $h = abs($this->h);
    $m = abs($this->m);
    $s = abs($this->s);

    $sign = $this->sec < 0 ? '-' : '';

    if ($micro)
      return "{$sign}{$h}ʰ{$m}ᵐ{$s}ˢ{$micro}";
    else
      return "{$sign}{$h}ʰ{$m}ᵐ{$s}ˢ";
  }

}
