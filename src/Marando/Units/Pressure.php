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

  const Pa_in_inHg = 3386;
  const mbar_in_Pa = 1e-2;

  //----------------------------------------------------------------------------
  // Constructors
  //----------------------------------------------------------------------------

  public function __construct($Pa) {
    $this->Pa = $Pa;
  }

  // // // Static

  public static function Pa($Pa) {
    return new static($Pa);
  }

  public static function mbar($mbar) {
    return new static($Pa = $mbar / static::mbar_in_Pa);
  }

  public static function inHg($inHg) {
    return new static($Pa = $inHg * static::Pa_in_inHg);
  }

  //----------------------------------------------------------------------------
  // Properties
  //----------------------------------------------------------------------------

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

  protected function get_inHg() {
    return $this->Pa / static::Pa_in_inHg;
  }

  protected function get_mbar() {
    return $this->Pa * static::mbar_in_Pa;
  }

}
