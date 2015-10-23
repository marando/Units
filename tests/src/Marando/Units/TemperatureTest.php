<?php

namespace Marando\Units;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-22 at 23:13:16.
 */
class TemperatureTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers Marando\Units\Temperature::C
   */
  public function testC() {
    $temp = Temperature::C(100);

    $this->assertEquals(373.15, $temp->K, 'K', 1e-2);
    $this->assertEquals(212, $temp->F, 'F', 1e-2);
  }

  /**
   * @covers Marando\Units\Temperature::F
   */
  public function testF() {
    $temp = Temperature::F(32);

    $this->assertEquals(0, $temp->C, 'C', 1e-2);
    $this->assertEquals(273.15, $temp->K, 'F', 1e-2);
  }

  /**
   * @covers Marando\Units\Temperature::K
   */
  public function testK() {
    $temp = Temperature::K(0);

    $this->assertEquals(-273.15, $temp->C, 'C', 1e-2);
    $this->assertEquals(-459.67, $temp->F, 'F', 1e-2);
  }

}
