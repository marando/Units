<?php

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
