<?php

namespace Marando\Units;

use Marando\Units\Angle;

/**
 * Represents a measurement of time duration
 *
 * @property float $h       Integer hour segment
 * @property float $m       Integer minute segment
 * @property float $s       Integer second segment
 * @property float $micro   Integer micro (seconds decimal) segment
 * @property float $seconds Total number of seconds
 * @property float $minutes Total number of minutes
 * @property float $hours   Total number of hours
 * @property float $day     Time interval as fraction of a day
 */
class Time {
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
  const SEC_IN_DAY = 86400;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new Time instance from a number of seconds
   * @param float $seconds
   */
  public function __construct($seconds) {
    $this->seconds = $seconds;
  }

  /**
   * Creates a new Time instance from a number of seconds
   * @param float $seconds
   * @return static
   */
  public static function fromSeconds($seconds) {
    return new static($seconds);
  }

  /**
   * Creates a new Time instance from a number of minutes
   * @param float $minutes
   * @return static
   */
  public static function fromMinutes($minutes) {
    return new static($minutes * static::SEC_IN_MIN);
  }

  /**
   * Creates a new Time instance from a number of hours
   * @param float $hours
   * @return static
   */
  public static function fromHours($hours) {
    return new static($hours * static::SEC_IN_HOUR);
  }

  /**
   * Creates a new Time instance from a fraction of a day
   * @param type $day
   * @return static
   */
  public static function fromDayFrac($day) {
    return new static($day * static::SEC_IN_DAY);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * This instance represented as seconds
   * @var float
   */
  protected $seconds;

  public function __get($name) {
    switch ($name) {
      case 'seconds':
        return $this->seconds;

      case 'minutes':
        return $this->seconds / 60;

      case 'hours':
        return $this->seconds / 3600;

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

  /**
   * Represents this instance as a string
   * @return string
   */
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
   * Subtracts from this instance another Time instance or a duration of a
   * specified time unit
   *
   * @param Time     $time
   * @param TimeUnit $unit
   * @return static
   */
  public function subtract($time, $unit = TimeUnit::Seconds) {
    if ($time instanceof Time)
      return new Time($this->seconds - $time->seconds);
    else
      return new Time($this->seconds - ($time / $unit));
  }

  /**
   * Adds to this instance another Time instance or a duration of a specified
   * time unit
   *
   * @param Time     $time
   * @param TimeUnit $unit
   * @return static
   */
  public function add($time, $unit = TimeUnit::Seconds) {
    if ($time instanceof Time)
      return new Time($this->seconds + $time->seconds);
    else
      return new Time($this->seconds + ($time / $unit));
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

}
