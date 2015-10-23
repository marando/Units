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

namespace Marando\Units\Traits;

/**
 * Adds the ability to set a rounding cutoff for a class
 */
trait RoundingTrait {

  /**
   * @var Float the number of decimal places to display string values at.
   */
  protected $decimalPlaces = 3;

  /**
   * Sets the precision of string values in this instance.
   * @param type $decimals The number of decimal places.
   * @return static
   */
  public function round($decimals = 3) {
    $this->decimalPlaces = $decimals;
    return $this;
  }

}
