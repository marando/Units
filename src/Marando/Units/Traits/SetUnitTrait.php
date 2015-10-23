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
 * Adds the ability for a class to keep track of the type of units to display as
 */
trait SetUnitTrait {

  /**
   * @var string Default unit of this instance.
   */
  public $unit = null;

  /**
   * Sets the units of this instance.
   * @param string $unit A string representing the units
   * @return static
   */
  public function setUnit($unit = 'm') {
    $this->unit = $unit;
    return $this;
  }

}
