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

/**
 * Represents a measure of temperature
 *
 * @property float $K Kelvin
 * @property float $C Celcius
 * @property float $F Fahrenheit
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Temperature {

  use Traits\RoundingTrait,
      Traits\SetUnitTrait;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new temperature from Kelvins
   * @param float $kelvin
   */
  public function __construct($kelvin = 0) {
    $this->K = $kelvin;
  }

  // // // Static

  /**
   * Creates a new temperature from degrees Celcius
   * @param  float  $celcius
   * @return static
   */
  public static function C($celcius) {
    return (new static($K = $celcius + 273.15))->setUnit('C');
  }

  /**
   * Creates a new temperature from degrees Fahrenheit
   * @param  float  $fahrenheit
   * @return static
   */
  public static function F($fahrenheit) {
    return (new static($K = ($fahrenheit + 459.67) * (5 / 9)))->setUnit('F');
  }

  /**
   * Creates a new temperature from Kelvins
   * @param  float  $kelvin
   * @return static
   */
  public static function K($kelvin) {
    return (new static($kelvin))->setUnit('K');
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * Kelvins of this instance
   * @var float
   */
  protected $K;

  public function __get($name) {
    switch ($name) {
      // Pass through to property
      case 'K':
        return $this->{$name};

      case 'C':
        return $this->getCelcius();

      case 'F':
        return $this->getFahrenheit();

      default:
        throw new Exception("{$name} is not a valid property");
    }
  }

  //----------------------------------------------------------------------------
  // Functions
  //----------------------------------------------------------------------------

  /**
   * Copies this instance
   * @return static
   */
  public function copy() {
    return clone $this;
  }

  // // // Overrides

  /**
   * Represents this instance as a string
   * @return string
   */
  public function __toString() {
    switch (strtolower($this->unit)) {
      case 'k':
      case 'kelvin':
      case 'kelvins':
        $K = round($this->K, $this->decimalPlaces);
        return "{$K} K";

      case 'f':
      case 'fahrenheit':
        $F = round($this->F, $this->decimalPlaces);
        return "{$F}°F";

      default:
      case 'c':
      case 'celcius':
        $C = round($this->C, $this->decimalPlaces);
        return "{$C}°C";
    }
  }

  // // // Protected

  /**
   * Converts the kelvins of this instance to Celcius
   * @return float
   */
  protected function getCelcius() {
    return $C = $this->K - 273.15;
  }

  /**
   * Converts the kelvins of this instance to Fahrenheit
   * @return float
   */
  protected function getFahrenheit() {
    return $F = $this->K * (9 / 5) - 459.67;
  }

}
