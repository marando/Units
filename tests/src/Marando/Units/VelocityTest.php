<?php

namespace Marando\Units;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-16 at 20:32:09.
 */
class VelocityTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers Marando\Units\Velocity::ms
   */
  public function test_ms() {
    $v = Velocity::ms(100);

    $this->assertEquals(100, $v->ms, 'm/s');
    $this->assertEquals(0.1, $v->kms, 'km/s');
    $this->assertEquals(360, $v->kmh, 'km/h');
    $this->assertEquals(8640, $v->kmd, 'km/d');
    $this->assertEquals(223.69362920544023, $v->mph, 'mph');
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d');
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity::kms
   */
  public function test_kms() {
    $v = Velocity::kms(0.1);

    $this->assertEquals(100, $v->ms, 'm/s');
    $this->assertEquals(0.1, $v->kms, 'km/s');
    $this->assertEquals(360, $v->kmh, 'km/h');
    $this->assertEquals(8640, $v->kmd, 'km/d');
    $this->assertEquals(223.69362920544023, $v->mph, 'mph');
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d');
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity::kmh
   */
  public function test_kmh() {
    $v = Velocity::kmh(360);

    $this->assertEquals(100, $v->ms, 'm/s');
    $this->assertEquals(0.1, $v->kms, 'km/s');
    $this->assertEquals(360, $v->kmh, 'km/h');
    $this->assertEquals(8640, $v->kmd, 'km/d');
    $this->assertEquals(223.69362920544023, $v->mph, 'mph');
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d');
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity::kmd
   */
  public function test_kmd() {
    $v = Velocity::kmd(8640);

    $this->assertEquals(100, $v->ms, 'm/s');
    $this->assertEquals(0.1, $v->kms, 'km/s');
    $this->assertEquals(360, $v->kmh, 'km/h');
    $this->assertEquals(8640, $v->kmd, 'km/d');
    $this->assertEquals(223.69362920544023, $v->mph, 'mph');
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d');
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity::mph
   */
  public function test_mph() {
    $v = Velocity::mph(223.69362920544023);

    $this->assertEquals(100, $v->ms, 'm/s');
    $this->assertEquals(0.1, $v->kms, 'km/s');
    $this->assertEquals(360, $v->kmh, 'km/h');
    $this->assertEquals(8640, $v->kmd, 'km/d');
    $this->assertEquals(223.69362920544023, $v->mph, 'mph');
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d');
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity::aud
   */
  public function test_aud() {
    $v = Velocity::aud(5.77548e-5);

    $this->assertEquals(100, $v->ms, 'm/s', 1e-4);
    $this->assertEquals(0.1, $v->kms, 'km/s', 1e-7);
    $this->assertEquals(360, $v->kmh, 'km/h', 1e-3);
    $this->assertEquals(8640, $v->kmd, 'km/d', 1e-2);
    $this->assertEquals(223.69362920544023, $v->mph, 'mph', 1e-3);
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d');
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity::pcy
   */
  public function test_pcy() {
    $v = Velocity::pcy(1.02269032e-7);

    $this->assertEquals(100, $v->ms, 'm/s', 1e-2);
    $this->assertEquals(0.1, $v->kms, 'km/s', 1e-4);
    $this->assertEquals(360, $v->kmh, 'km/h', 1e-2);
    $this->assertEquals(8640, $v->kmd, 'km/d', 1);
    $this->assertEquals(223.69362920544023, $v->mph, 'mph', 1e-2);
    $this->assertEquals(5.77548e-5, $v->aud, 'AU/d', 1e-8);
    $this->assertEquals(1.02269032e-7, $v->pcy, 'pc/y');
  }

  /**
   * @covers Marando\Units\Velocity->dist
   */
  public function testDistance() {
    $v = Velocity::ms(100);
    $this->assertEquals(100, $v->dist->m);
  }

  /**
   * @covers Marando\Units\Velocity->time
   */
  public function testTime() {
    $v = Velocity::mph(100);
    $this->assertEquals(1, $v->time->hours);
  }

  /**
   * @covers Marando\Units\Velocity::time
   */
  public function testTimeCalc() {
    $v    = Velocity::mph(100);
    $time = $v->time(Distance::mi(50));

    $this->assertEquals(30, $time->min);
  }

  /**
   * @covers Marando\Units\Velocity::dist
   */
  public function testDistCalc() {
    $v    = Velocity::mph(60);
    $dist = $v->dist(Time::min(30));
    
    $this->assertEquals(30, $dist->mi);
  }

}
