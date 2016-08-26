<?php

namespace Marando\Units;

use SebastianBergmann\CodeCoverage\Report\PHP;

class VelocityTest extends \PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $d = Distance::m(10);
        $t = Time::sec(1);
        $v = new Velocity($d, $t);

        $this->assertEquals(10, $v->ms, 'm/s');
        $this->assertEquals(10, $v->dist->m, 'dist');
        $this->assertEquals(1, $v->time->sec, 'time');
    }

    public function testCreate()
    {
        $tests = [
          [Velocity::create('120', 'mph'), 53.6448],
          [Velocity::create('10', 'km/d'), 0.115741],
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $test[0]->ms, $test[0], 1e-6);
        }
    }

    public function test_kms()
    {
        $v = Velocity::kms(1);
        $this->assertEquals(1000, $v->ms);
    }

    public function test_kmh()
    {
        $v = Velocity::kmh(1);
        $this->assertEquals(0.27777777777, $v->ms);
    }

    public function test_kmd()
    {
        $v = Velocity::kmd(10);
        $this->assertEquals(0.115741, $v->ms, '', 1e-6);
    }

    public function test_ms()
    {
        $v = Velocity::ms(10);
        $this->assertEquals(10, $v->ms);
    }

    public function test_mph()
    {
        $v = Velocity::mph(120);
        $this->assertEquals(53.6448, $v->ms);
    }

    public function test_fts()
    {
        $v = Velocity::fts(1);
        $this->assertEquals(0.3048, $v->ms);
    }

    public function test_aud()
    {
        $v = Velocity::aud(1);
        $this->assertEquals(149597870.700, $v->kmd);
    }

    public function test_pcy()
    {
        // Not sure how to test this yet...
        ;
    }

    public function test_c()
    {
        $this->assertEquals(299792458, Velocity::c()->ms);
    }

    public function testSetDist()
    {
        $v       = Velocity::ms(10);
        $v->dist = Distance::m(4);

        $this->assertEquals(4, $v->ms);
    }

    public function testSetTime()
    {
        $v       = Velocity::kms(6);
        $v->time = Time::hours(1);

        $this->assertEquals(6, $v->kmh);
    }


    public function testUnits()
    {
        $this->assertEquals(1, Velocity::ms(1000)->units('km/s'));
    }

    public function testFormat()
    {
        $this->assertContains('mph', Velocity::ms(1000)->format('%1.3f mph'));
        $this->assertContains('au/d', Velocity::ms(10)->format('%1.3f au/d'));
    }

    public function testAdd()
    {
        $a = Velocity::kmd(1);
        $b = Velocity::kmd(1);

        $this->assertEquals(2, $a->add($b)->kmd);
    }

    public function testSub()
    {
        $a = Velocity::kmd(1);
        $b = Velocity::kmd(1);

        $this->assertEquals(0, $a->sub($b)->kmd);
    }

    public function testTime()
    {
        $v = Velocity::mph(70);

        $this->assertEquals(8.5714285714, $v->time(Distance::mi(600))->hours);
    }

    public function testDist()
    {
        $v = Velocity::mph(70);

        $this->assertEquals(35, $v->dist(Time::min(30))->mi);
    }

}