<?php

namespace Marando\Units;

use Marando\Units\Angle;

/**
 * Represents a duration of time
 *
 * @property float $h       The integer value of Hours
 * @property float $m       The integer value of Minutes
 * @property float $s       The integer value of Seconds
 * @property float $micro   Micro
 * @property float $seconds The total number of seconds
 * @property float $minutes The total number of minutes
 * @property float $day     The fraction of a day.
 */
class Time {

  //----------------------------------------------------------------------------
  // Constants
  //----------------------------------------------------------------------------

  const SEC_IN_MIN  = 60;
  const SEC_IN_HOUR = 3600;
  const SEC_IN_DAY  = 86400;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new Time instance from a number of seconds
   * @param type $seconds The number of seconds
   */
  public function __construct($seconds) {
    $this->seconds = $seconds;
  }

  /**
   * Creates a new Time instance from a number of seconds
   * @param type $seconds The number of seconds
   */
  public static function fromSeconds($seconds) {
    return new Time($seconds);
  }

  public static function fromDay($day) {
    return new Time($day * static::SEC_IN_DAY);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  protected $seconds;

  public function __get($name) {
    switch ($name) {
      case 'seconds':
        return $this->seconds;

      case 'minutes':
        return $this->seconds / 60;

      case 'day':
        return $this->seconds / 86400;

      case 'h':
        return intval($this->seconds / 3600);

      case 'm':
        return intval($this->seconds % 3600 / 60);

      case 's':
        return intval($this->seconds % 3600 % 60);

      case 'micro':
        return $this->seconds -
                ($this->h * 3600 + $this->m * 60 + $this->s);
    }
  }

  //----------------------------------------------------------------------------
  // Functions
  //----------------------------------------------------------------------------

  public function __toString() {
    $decimals = 4;
    $micro    = substr(round($this->micro, $decimals), 1, $decimals);

    $h = abs($this->h);
    $m = abs($this->m);
    $s = abs($this->s);

    $sign = $this->seconds < 0 ? '-' : '';

    if ($micro)
      return "{$sign}{$h}ʰ{$m}ᵐ{$s}ˢ{$micro}";
    else
      return "{$sign}{$h}ʰ{$m}ᵐ{$s}ˢ";
  }

  /**
   *
   * @param int|float|Time $time
   */
  public function subtract($time, $unit = TimeUnit::Seconds) {
    if ($time instanceof Time) {
      return new Time($this->seconds - $time->seconds);
    }
    else {
      return new Time($this->seconds - ($time / $unit));
    }
  }

  /**
   *
   * @param int|float|Time $time
   */
  public function add($time, $unit = TimeUnit::Seconds) {
    if ($time instanceof Time) {
      return new Time($this->seconds + $time->seconds);
    }
    else {
      return new Time($this->seconds + ($time / $unit));
    }
  }

  /**
   *
   * @return Angle
   */
  public function toAngle($interval = Time::SEC_IN_DAY) {
    return Angle::fromTime($this, $interval);
  }

}
