<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Marando\Units;

/**
 * Represents a measure of temperature
 *
 * @property float $K Kelvin
 * @property float $C Celcius
 * @property float $F Fahrenheit
 */
class Temperature {

  use Traits\RoundingTrait,
      Traits\SetUnitTrait;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  public function __construct($kelvin = 0) {
    $this->K = $kelvin;
  }

  // // // Static

  /**
   *
   * @param type $celcius
   * @return static
   */
  public static function C($celcius) {
    return (new static($K = $celcius + 273.15))->setUnit('C');
  }

  /**
   *
   * @param type $fahrenheit
   * @return static
   */
  public static function F($fahrenheit) {
    return (new static($K = ($fahrenheit + 459.67) * (5 / 9)))->setUnit('F');
  }

  /**
   *
   * @param type $kelvin
   * @return static
   */
  public static function K($kelvin) {
    return (new static($kelvin))->setUnit('K');
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

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

  public function copy() {
    return clone $this;
  }

  // // // Overrides

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

  protected function getCelcius() {
    return $C = $this->K - 273.15;
  }

  protected function getFahrenheit() {
    return $F = $this->K * (9 / 5) - 459.67;
  }

}
