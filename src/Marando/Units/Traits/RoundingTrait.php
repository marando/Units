<?php

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
