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
 * Represents a measure of pressure
 *
 * @property float $Pa   Pascals
 * @property float $mbar Millibars
 * @property float $inHg Inches of Mercury
 */
class Pressure {
  //----------------------------------------------------------------------------
  // Constants
  //----------------------------------------------------------------------------

  /**
   * The number of Pascals in one inch of mercury
   */
  const Pa_in_inHg = 3386;

  /**
   * The number of millibars in one Pascal
   */
  const mbar_in_Pa = 1e-2;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  /**
   * Creates a new pressure from Pascals
   * @param float $Pa
   */
  public function __construct($Pa) {
    $this->Pa = $Pa;
  }

  // // // Static

  /**
   * Creates a new pressure from Pascals
   *
   * @param  float  $Pa
   * @return static
   */
  public static function Pa($Pa) {
    return new static($Pa);
  }

  /**
   * Creates a new pressure from millibars
   * @param  float  $mbar
   * @return static
   */
  public static function mbar($mbar) {
    return new static($Pa = $mbar / static::mbar_in_Pa);
  }

  /**
   * Creates a new pressure from inches of Mercury
   * @param  float  $inHg
   * @return static
   */
  public static function inHg($inHg) {
    return new static($Pa = $inHg * static::Pa_in_inHg);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

  /**
   * Number of Pascals in this instance
   * @var float
   */
  protected $Pa;

  public function __get($name) {
    switch ($name) {
      // Pass through to property
      case 'Pa':
        return $this->{$name};

      case 'inHg':
        return $this->get_inHg();

      case 'mbar':
        return $this->get_mbar();

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

  // // // Protected

  /**
   * Gets the number of inches in mercury for this instance
   * @return float
   */
  protected function get_inHg() {
    return $this->Pa / static::Pa_in_inHg;
  }

  /**
   * Gets the number of millibars for this instance
   * @return float
   */
  protected function get_mbar() {
    return $this->Pa * static::mbar_in_Pa;
  }

}
