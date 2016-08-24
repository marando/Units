<?php

namespace Marando\Units;

class Distance2Test extends \PHPUnit_Framework_TestCase
{

    private $tests;

    protected function setUp()
    {
        parent::setUp();

        $this->tests = [
          'km'  => 23,
          'hm'  => 230,
          'dam' => 2300,
          'm'   => 23000,
          'dm'  => 230000,
          'cm'  => 2.3e+6,
          'mm'  => 2.3e+7,
          'μm'  => 2.3e+10,
          'nm'  => 2.3e+13,
          'pm'  => 2.3e+16,
          'mi'  => 14.291537421458681301200986240356319096476576791537421458681,
          'yd'  => 25153.105861767279090113735783027121609798775153105861767279,
          'ft'  => 75459.317585301837270341207349081364829396325459317585301837,
          'in'  => 905511.81102362204724409448818897637795275590551181102362204,
          'au'  => 0.0000001537455038121742464079069275148446347505399353254297,
          'ly'  => 0.0000000000024311019182566155665317592063157926367117191917,
          'pc'  => 0.0000000000007453792230103343677862788495761571484274992681,
        ];

        /*
        'mi'  => bcdiv(23000, 1609.344, Distance2::P),
        'yd'  => bcdiv(23000, 0.9144, Distance2::P),
        'ft'  => bcdiv(23000, 0.3048, Distance2::P),
        'in'  => bcdiv(23000, 0.0254, Distance2::P),
        'au'  => bcdiv(23000, 149597870700, Distance2::P),
        'ly'  => bcdiv(23000, 9460730472580800, Distance2::P),
        'pc'  => bcdiv(bcdiv(23000, 149597870700, 999), 206264.81, 999)
          */
    }

    public function testAll()
    {
        foreach ($this->tests as $from => $test) {
            $dist = call_user_func_array(
              '\Marando\Units\Distance2::' . $from, [$test]);

            //echo PHP_EOL . $dist;

            foreach ($this->tests as $to => $t) {
                $this->assertUnit($from, $to, $dist);
                //echo $from . ' -> ' . $to . ', ';
            }
        }
    }

    public function testParallax()
    {
        $stars = [
          'Proxima Centauri' => [Angle::mas(768.13), 1.302, 1e-3],
          'Luhman 16'        => [Angle::mas(500.51), 1.9980, 1e-4],
          'Sirius'           => [Angle::mas(379.21), 2.64, 1e-2],
          'WISE 0855-0710'   => [Angle::mas(449), 2.23, 1e-2],
        ];

        foreach ($stars as $star => $data) {
            $parallax = $data[0];
            $parsecs  = $data[1];
            $accuracy = $data[2];

            $dist = Distance2::parallax($parallax);

            $this->assertEquals($parsecs, $dist->pc, $star, $accuracy);
        }
    }

    public function testToParallax()
    {
        $stars = [
          'Proxima Centauri' => [Angle::mas(768.13), 1.30186, 1e-2],
          'Luhman 16'        => [Angle::mas(500.51), 1.9980, 1e-2],
          'Sirius'           => [Angle::mas(379.21), 2.6370, 1e-2],
          'WISE 0855-0710'   => [Angle::mas(449), 2.23, 1],
        ];

        foreach ($stars as $star => $data) {
            $parallax = $data[0];
            $parsecs  = $data[1];
            $accuracy = $data[2];

            $dist = Distance2::pc($parsecs);

            $this->assertEquals($parallax->mas, $dist->parallax->mas, $star,
              $accuracy);
        }
    }

    public function testAdd()
    {
        $tests = [
          [Distance2::m(1000)->add(Distance2::km(1))->m, 2000],
          [Distance2::km(10)->add(Distance2::m(200))->m, 10200],
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $test[0]);
        }
    }

    public function testSub()
    {
        $tests = [
          [Distance2::m(1000)->sub(Distance2::km(1))->m, 0],
          [Distance2::km(10)->sub(Distance2::m(200))->m, 9800],
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $test[0]);
        }
    }

    public function testNegate()
    {
        $this->assertEquals(Distance2::m(10)->neg()->m, -10);
    }

    // // //

    private function assertUnit($from, $to, $test)
    {
        $expected = $this->tests[$to];
        $actual   = (double)$test->{$to};
        $desc     = $from . ' -> ' . $to;

        $this->assertEquals($expected, $actual, $desc, 1e-20);
    }

}
