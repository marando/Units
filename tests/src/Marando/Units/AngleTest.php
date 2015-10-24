<?php

namespace Marando\Units;

use \Marando\Units\Angle;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-16 at 01:50:47.
 */
class AngleTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers Marando\Units\Angle::fromDeg
   */
  public function testFromDeg() {
    $this->assertEquals(180, Angle::deg(180)->deg);
  }

  /**
   * @covers Marando\Units\Angle::fromRad
   */
  public function testFromRad() {
    $this->assertEquals(2, Angle::rad(2)->rad);
  }

  /**
   * @covers Marando\Units\Angle::fromDMS
   */
  public function testFromDMS() {
    $this->assertEquals(180.0685277778, Angle::dms(180, 4, 6.7)->deg);
  }

  /**
   * @covers Marando\Units\Angle::time
   */
  public function testTime() {
    $tests = [
        [21600, 90, time::SEC_IN_DAY],
        [2700, 270, time::SEC_IN_HOUR],
    ];

    foreach ($tests as $t) {
      $time = Time::sec($t[0]);
      $this->assertEquals($t[1], Angle::time($time, $t[2])->deg);

      // Test backwards compatibility
      $this->assertEquals($t[1], Angle::fromTime($time, $t[2])->deg);
    }
  }

  /**
   * @covers Marando\Units\Angle::Pi
   */
  public function testPi() {
    $this->assertEquals(pi(), Angle::Pi()->rad);
  }

  /**
   * @covers Marando\Units\Angle::norm
   */
  public function testNorm() {
    $tests = [
        [480, 120, 0, 360],
        [500, 140, 0, 180],
            //[480, -240, -360, 0],
    ];

    foreach ($tests as $t)
      $this->assertEquals($t[1], Angle::deg($t[0])->norm($t[2], $t[3])->deg);
  }

  /**
   * @covers Marando\Units\Angle::add
   */
  public function testAdd() {
    $tests = [
        [180, 40, 220],
        [180, -40, 140],
        [-10, 600, 590],
    ];

    foreach ($tests as $t)
      $this->assertEquals($t[2], Angle::deg($t[0])->add(Angle::deg($t[1]))->deg);
  }

  /**
   * @covers Marando\Units\Angle::multiply
   */
  public function testMultiply() {
    $tests = [
        [180, 2, 360],
        [180, -3, -540],
        [-10, 6, -60],
    ];

    foreach ($tests as $t)
      $this->assertEquals($t[2],
              Angle::deg($t[0])->multiply(Angle::deg($t[1]))->deg);
  }

  /**
   * @covers Marando\Units\Angle::subtract
   */
  public function testSubtract() {
    $tests = [
        [180, 40, 140],
        [180, -40, 220],
        [-10, 600, -610],
    ];

    foreach ($tests as $t)
      $this->assertEquals($t[2],
              Angle::deg($t[0])->subtract(Angle::deg($t[1]))->deg);
  }

  /**
   * @covers Marando\Units\Angle::negate
   */
  public function testNegate() {
    $this->assertEquals(-15, Angle::deg(15)->negate()->deg);
  }

  /**
   * @covers Marando\Units\Angle::atan2
   */
  public function testAtan2() {
    $this->assertEquals(atan2(40, 14), Angle::atan2(40, 14)->rad);
  }

}
