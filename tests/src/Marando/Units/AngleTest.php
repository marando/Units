<?php

namespace Marando\Units;

use \Marando\Units\Angle;

class AngleTest extends \PHPUnit_Framework_TestCase
{

    private $tests;

    public function setUp()
    {
        parent::setUp();

        $tests = [
            //[270, 15, 45, 0.000, 270.2625],
          [-180, 0, -0, -0.003, -180.00000083333333333],
          [12, -34, 56, 0.1234, 12.5822565],
          [43, 7, 8, 0.4, 43.119000],
          [0, 0, 0, 0.00001, 0.000000002777778],
        ];

        foreach ($tests as $t) {
            $test        = [];
            $test['d']   = $t[0];
            $test['m']   = $t[1];
            $test['s']   = $t[2];
            $test['f']   = $t[3];
            $test['deg'] = $t[4];
            $test['rad'] = deg2rad($t[4]);

            $this->tests[] = $test;
        }
    }

    public function testDeg()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::deg($t['deg']);

            $this->assertAngle($angle, $t);
        }
    }

    public function testSetDeg()
    {
        foreach ($this->tests as $t) {
            $angle      = Angle::deg(0);
            $angle->deg = $t['deg'];

            $this->assertAngle($angle, $t);
        }
    }

    public function testToDeg()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::dms($t['d'], $t['m'], $t['s'], $t['f']);

            $this->assertEquals($t['deg'], $angle->deg);
        }
    }

    public function testRad()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::rad($t['rad']);

            $this->assertAngle($angle, $t);
        }
    }

    public function testSetRad()
    {
        foreach ($this->tests as $t) {
            $angle      = Angle::deg(0);
            $angle->rad = $t['rad'];

            $this->assertAngle($angle, $t);
        }
    }

    public function testToRad()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::dms($t['d'], $t['m'], $t['s'], $t['f']);

            $this->assertEquals($t['rad'], $angle->rad);
        }
    }

    public function testDMS()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::dms($t['d'], $t['m'], $t['s'], $t['f']);

            $this->assertAngle($angle, $t);
        }
    }

    public function testMAS()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::mas($t['deg'] * 3.6e6);

            $this->assertAngle($angle, $t);
        }
    }

    public function testSetMAS()
    {
        foreach ($this->tests as $t) {
            $angle      = Angle::deg(0);
            $angle->mas = $t['deg'] * 3.6e6;

            $this->assertAngle($angle, $t);
        }
    }

    public function testToMAS()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::deg($t['deg']);

            $this->assertEquals($t['deg'] * 3.6e6, $angle->mas);
        }
    }

    public function testAmin()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::amin($t['deg'] * 60);

            $this->assertAngle($angle, $t);
        }
    }

    public function testSetAmin()
    {
        foreach ($this->tests as $t) {
            $angle       = Angle::deg(0);
            $angle->amin = $t['deg'] * 60;

            $this->assertAngle($angle, $t);
        }
    }

    public function testToAmin()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::deg($t['deg']);

            $this->assertEquals($t['deg'] * 60, $angle->amin);
        }
    }

    public function testAsec()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::asec($t['deg'] * 3600);

            $this->assertAngle($angle, $t);
        }
    }

    public function testSetAsec()
    {
        foreach ($this->tests as $t) {
            $angle       = Angle::deg(0);
            $angle->asec = $t['deg'] * 3600;

            $this->assertAngle($angle, $t);
        }
    }

    public function testToAsec()
    {
        foreach ($this->tests as $t) {
            $angle = Angle::deg($t['deg']);

            $this->assertEquals($t['deg'] * 3600, $angle->asec);
        }
    }

    public function testNorm()
    {
        $tests = [
          [540, 0, 360, 180],
          [140, 0, 360, 140],
          [370, 0, 360, 10],
          [360, 0, 360, 0],
          [0, 0, 360, 0],
            //
          [-540, 0, 360, 180],
          [-140, 0, 360, 220],
          [-370, 0, 360, 350],
          [-360, 0, 360, 0],
          [0, 0, 360, 0],
            //
          [-540, 0, 180, 0],
          [-140, 0, 180, 40],
          [-370, 0, 180, 170],
          [-360, 0, 180, 0],
          [0, 0, 180, 0],
            //
          [540, -360, 360, 180],
          [140, -360, 360, 140],
          [370, -360, 360, 10],
          [360, -360, 360, 0],
          [0, -360, 360, 0],
            //
          [-540, -360, 360, -180],
          [-140, -360, 360, -140],
          [-370, -360, 360, -10],
          [-360, -360, 360, 0],
          [0, -360, 360, 0],
            //
          [-540, -180, 180, -0],
          [-140, -180, 180, -140],
          [-370, -180, 180, -10],
          [-360, -180, 180, 0],
          [0, -180, 180, 0],
        ];

        foreach ($tests as $test) {
            $lb    = $test[1];
            $ub    = $test[2];
            $angle = Angle::deg($test[0]);
            $deg   = $test[3];

            $angle->norm($lb, $ub);
            $this->assertEquals($deg, $angle->deg);
        }
    }

    public function testAdd()
    {
        $tests = [
          [Angle::deg(180), Angle::rad(pi()), 360],
          [Angle::deg(10), Angle::rad(pi()), 190],
          [Angle::dms(1, 1, 1), Angle::dms(1, 1, 1.1), 2.0391666667],
        ];

        /** @var Time[] $t */
        foreach ($tests as $t) {
            $a = $t[0];
            $b = $t[1];
            $this->assertEquals($t[2], $a->add($b)->deg, '', 1e-2);
        }
    }

    public function testSub()
    {
        $tests = [
          [Angle::deg(180), Angle::rad(pi()), 0],
          [Angle::deg(10), Angle::rad(pi()), -170],
          [Angle::dms(2, 2, 2), Angle::dms(1, 1, 1.1), 1.0169166667],
        ];

        /** @var Time[] $t */
        foreach ($tests as $t) {
            $a = $t[0];
            $b = $t[1];
            $this->assertEquals($t[2], $a->sub($b)->deg, '', 1e-2);
        }
    }

//    public function testMul()
//    {
//        $tests = [
//          [Angle::deg(180), Angle::deg(2), 360],
//          [Angle::deg(-10), Angle::deg(40), -400],
//          [Angle::rad(2), Angle::rad(0.01), 65.656127002],
//        ];
//
//        /** @var Time[] $t */
//        foreach ($tests as $t) {
//            $a = $t[0];
//            $b = $t[1];
//            $this->assertEquals($t[2], $a->mul($b)->deg, '', 1e-2);
//        }
//    }
//
//    public function testDiv()
//    {
//        $tests = [
//          [Angle::deg(180), Angle::deg(2), 90],
//          [Angle::deg(360), Angle::deg(3), 120],
//          [Angle::deg(180.5), Angle::deg(10), 18.05],
//        ];
//
//        /** @var Time[] $t */
//        foreach ($tests as $t) {
//            $a = $t[0];
//            $b = $t[1];
//            $this->assertEquals($t[2], $a->div($b)->deg, '', 1e-2);
//        }
//    }

    public function testNegate()
    {
        $this->assertEquals(-180, Angle::deg(180)->neg()->deg);
        $this->assertEquals(180, Angle::deg(-180)->neg()->deg);
    }

    public function testAtan2()
    {
        $this->assertEquals(atan2(40, 14), Angle::atan2(40, 14)->rad);
    }

    public function testTime()
    {
        $angle = Angle::time(Time::hours(12));

        $this->assertEquals(180, $angle->deg);
    }

    public function testToTime()
    {
        $angle = Angle::deg(90);

        $this->assertEquals(6, $angle->toTime()->hours);
    }

    public function testSign()
    {
        $this->assertEquals('-', Angle::deg(-10)->sign);
        $this->assertEquals('-', Angle::rad(-10)->sign);
        $this->assertEquals('-', Angle::asec(-10)->sign);
        $this->assertEquals('-', Angle::amin(-10)->sign);
        $this->assertEquals('-', Angle::mas(-10)->sign);

        $this->assertEquals('-', Angle::dms(-10, 0, 0, 0)->sign);
        $this->assertEquals('-', Angle::dms(0, -10, 0, 0)->sign);
        $this->assertEquals('-', Angle::dms(0, 0, -10, 0)->sign);
        $this->assertEquals('-', Angle::dms(0, 0, 0, -10)->sign);

        $this->assertEquals('+', Angle::dms(10, 0, 0, 0)->sign);
        $this->assertEquals('+', Angle::dms(1, -10, 0, 0)->sign);
        $this->assertEquals('+', Angle::dms(1, 0, -10, 0)->sign);
        $this->assertEquals('+', Angle::dms(1, 0, 0, -10)->sign);

        $this->assertEquals('+', Angle::deg(10)->sign);
        $this->assertEquals('+', Angle::rad(10)->sign);
        $this->assertEquals('+', Angle::asec(10)->sign);
        $this->assertEquals('+', Angle::amin(10)->sign);
        $this->assertEquals('+', Angle::mas(10)->sign);
    }

    public function testString()
    {
        $tests = [
          '0 0 1'            => Angle::dms(0, 0, 1),
          '0 0 0.1'          => Angle::dms(0, 0, 0, 1),
          '0 0 0.1'          => Angle::dms(0, 0, 0, 0.1),
          '0 0 0.01'         => Angle::dms(0, 0, 0, 0.01),
          '0 0 0.001'        => Angle::dms(0, 0, 0, 0.001),
          '0 0 0.0001'       => Angle::dms(0, 0, 0, 0.0001),
          '0 0 0.00001'      => Angle::dms(0, 0, 0, 0.00001),
          '0 0 0.000001'     => Angle::dms(0, 0, 0, 0.000001),
          '0 0 0.0000001'    => Angle::dms(0, 0, 0, 0.0000001),
          '0 0 0.00000001'   => Angle::dms(0, 0, 0, 0.00000001),
          '0 0 0.000000001'  => Angle::dms(0, 0, 0, 0.000000001),
          '-0 0 1'           => Angle::dms(0, 0, -1),
          '-0 0 0.1'         => Angle::dms(0, 0, 0, -1),
          '-0 0 0.1'         => Angle::dms(0, 0, 0, -0.1),
          '-0 0 0.01'        => Angle::dms(0, 0, 0, -0.01),
          '-0 0 0.001'       => Angle::dms(0, 0, 0, -0.001),
          '-0 0 0.0001'      => Angle::dms(0, 0, 0, -0.0001),
          '-0 0 0.00001'     => Angle::dms(0, 0, 0, -0.00001),
          '-0 0 0.000001'    => Angle::dms(0, 0, 0, -0.000001),
          '-0 0 0.0000001'   => Angle::dms(0, 0, 0, -0.0000001),
          '-0 0 0.00000001'  => Angle::dms(0, 0, 0, -0.00000001),
          '-0 0 0.000000001' => Angle::dms(0, 0, 0, -0.000000001),
        ];

        foreach ($tests as $string => $angle) {
            $this->assertEquals($string, $angle->format('d m s.9f'));
        }
    }

    // // //

    private function assertAngle(Angle $angle, $test)
    {
        $t = $test;
        $a = $angle;

        //dd($a->s);

        $this->assertEquals($t['deg'], $a->deg, 'deg' . $t['deg'], 1e-12);
        $this->assertEquals(abs($t['d']), $a->d, 'd' . $t['deg']);
        $this->assertEquals(abs($t['m']), $a->m, 'm' . $t['deg']);
        $this->assertEquals(abs($t['s']), $a->s, 's' . $t['deg']);
        $this->assertEquals(abs($t['f']), "0.$a->f", 'f' . $t['deg'], 1e-12);
    }

}
