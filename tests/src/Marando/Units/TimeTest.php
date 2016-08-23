<?php

namespace Marando\Units;

use Litipk\BigNumbers\Decimal;
use SebastianBergmann\CodeCoverage\Report\PHP;

class TimeTest extends \PHPUnit_Framework_TestCase
{

    private $tests;

    public function setUp()
    {
        parent::setUp();

        $tests = [
          [12, 30, 30, 0.5, 45030.5],
          [1, 1, 1, 0.1, 3661.1],
        ];

        foreach ($tests as $t) {
            $test        = [];
            $test['h']   = $t[0];
            $test['m']   = $t[1];
            $test['s']   = $t[2];
            $test['f']   = $t[3];
            $test['sec'] = $t[4];

            $this->tests[] = $test;
        }
    }

    public function testSec()
    {
        foreach ($this->tests as $test) {
            $time = Time::sec($test['sec']);
            $this->assertTime($time, $test);
        }
    }

    public function testSetSec()
    {
        foreach ($this->tests as $test) {
            $time      = Time::sec(0);
            $time->sec = $test['sec'];
            $this->assertTime($time, $test);
        }
    }

    public function testToSec()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertEquals($test['sec'], $time->sec);
        }
    }

    //

    public function testMin()
    {
        foreach ($this->tests as $test) {
            $time = Time::min($test['sec'] / 60);
            $this->assertTime($time, $test);
        }
    }

    public function testSetMin()
    {
        foreach ($this->tests as $test) {
            $time      = Time::min(0);
            $time->min = $test['sec'] / 60;
            $this->assertTime($time, $test);
        }
    }

    public function testToMin()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertEquals($test['sec'] / 60, $time->min);
        }
    }

    // 

    public function testHours()
    {
        foreach ($this->tests as $test) {
            $time = Time::hours($test['sec'] / 3600);
            $this->assertTime($time, $test);
        }
    }

    public function testSetHours()
    {
        foreach ($this->tests as $test) {
            $time        = Time::hours(0);
            $time->hours = $test['sec'] / 3600;
            $this->assertTime($time, $test);
        }
    }

    public function testToHours()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertEquals($test['sec'] / 3600, $time->hours);
        }
    }

    //

    public function testDays()
    {
        foreach ($this->tests as $test) {
            $time = Time::days($test['sec'] / 86400);
            $this->assertTime($time, $test);
        }
    }

    public function testSetDays()
    {
        foreach ($this->tests as $test) {
            $time       = Time::days(0);
            $time->days = $test['sec'] / 86400;
            $this->assertTime($time, $test);
        }
    }

    public function testToDays()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertEquals($test['sec'] / 86400, $time->days);
        }
    }

    //

    public function testWeeks()
    {
        foreach ($this->tests as $test) {
            $time = Time::weeks($test['sec'] / 86400 / 7);
            $this->assertTime($time, $test);
        }
    }

    public function testToWeeks()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertEquals($test['sec'] / 86400 / 7, $time->weeks);
        }
    }

    //

    public function testYears()
    {
        foreach ($this->tests as $test) {
            $time = Time::years($test['sec'] / 86400 / 365.25);
            $this->assertTime($time, $test);
        }
    }

    public function testToYears()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertEquals($test['sec'] / 86400 / 365.25, $time->years);
        }
    }

    //

    public function testHMS()
    {
        foreach ($this->tests as $test) {
            $time = Time::hms($test['h'], $test['m'], $test['s'], $test['f']);
            $this->assertTime($time, $test);
        }
    }

    public function testToHMS()
    {
        foreach ($this->tests as $test) {
            $time = Time::sec($test['sec']);
            $this->assertTime($time, $test);
        }
    }

    //

    public function testAdd()
    {
        $tests = [
          [Time::sec(10356), Time::sec(103), 10459],
          [Time::hms(10, 4, 32, 0.5), Time::hours(4), 50672.5],
        ];

        /** @var Time[] $test */
        foreach ($tests as $test) {
            $a = $test[0];
            $b = $test[1];

            $this->assertEquals($test[2], $a->add($b)->sec);
        }
    }

    public function testSub()
    {
        $tests = [
          [Time::sec(10356), Time::sec(103), 10253],
          [Time::hms(10, 4, 32, 0.5), Time::hours(4), 21872.5],
        ];

        /** @var Time[] $test */
        foreach ($tests as $test) {
            $a = $test[0];
            $b = $test[1];

            $this->assertEquals($test[2], $a->sub($b)->sec);
        }
    }

    public function testMul()
    {
        $tests = [
          [Time::sec(10356), Time::sec(2), 20712],
          [Time::hms(10, 4, 32, 0.5), Time::sec(0.5), 18136.25],
        ];

        /** @var Time[] $test */
        foreach ($tests as $test) {
            $a = $test[0];
            $b = $test[1];

            $this->assertEquals($test[2], $a->mul($b)->sec);
        }
    }

    public function testDiv()
    {
        $tests = [
          [Time::sec(10356), Time::sec(2), 5178],
          [Time::hms(10, 4, 32, 0.5), Time::sec(0.5), 72545],
        ];

        /** @var Time[] $test */
        foreach ($tests as $test) {
            $a = $test[0];
            $b = $test[1];

            $this->assertEquals($test[2], $a->div($b)->sec);
        }
    }

    public function testNeg()
    {
        $this->assertEquals(-1023, Time::sec(1023)->neg()->sec);
    }

    public function testSign()
    {
        $this->assertEquals('-', Time::sec(-10)->sign);
        $this->assertEquals('-', Time::min(-10)->sign);
        $this->assertEquals('-', Time::hours(-10)->sign);
        $this->assertEquals('-', Time::days(-10)->sign);
        $this->assertEquals('-', Time::weeks(-10)->sign);

        $this->assertEquals('-', Time::hms(-10, 0, 0, 0)->sign);
        $this->assertEquals('-', Time::hms(0, -10, 0, 0)->sign);
        $this->assertEquals('-', Time::hms(0, 0, -10, 0)->sign);
        $this->assertEquals('-', Time::hms(0, 0, 0, -10)->sign);

        $this->assertEquals('+', Time::hms(10, 0, 0, 0)->sign);
        $this->assertEquals('+', Time::hms(1, -10, 0, 0)->sign);
        $this->assertEquals('+', Time::hms(1, 0, -10, 0)->sign);
        $this->assertEquals('+', Time::hms(1, 0, 0, -10)->sign);

        $this->assertEquals('+', Time::sec(10)->sign);
        $this->assertEquals('+', Time::min(10)->sign);
        $this->assertEquals('+', Time::hours(10)->sign);
        $this->assertEquals('+', Time::days(10)->sign);
        $this->assertEquals('+', Time::weeks(10)->sign);
    }

    // // //

    private function assertTime(Time $time, $test)
    {
        $t = $test;
        $a = $time;

        $this->assertEquals(abs($t['sec']), $a->sec, 'sec' . $t['sec']);
        $this->assertEquals(abs($t['h']), $a->h, 'h' . $t['sec']);
        $this->assertEquals(abs($t['m']), $a->m, 'm' . $t['sec']);
        $this->assertEquals(abs($t['s']), $a->s, 's' . $t['sec']);
        $this->assertEquals(abs($t['f']), "0.$a->f", 'f' . $t['sec']);
    }


    public function test()
    {
        return;
        echo PHP_EOL . Time::days(0.3245)->format(Time::FORMAT_DEFAULT);
        echo PHP_EOL . Time::days(0.3245)->format(Time::FORMAT_HMS);
        echo PHP_EOL . Time::days(0.3245)->format(Time::FORMAT_SPACED);
        echo PHP_EOL . Time::days(0.001)->format(Time::FORMAT_SEC);
        echo PHP_EOL . Time::days(0.3245)->format(Time::FORMAT_MIN);
        echo PHP_EOL . Time::days(0.3245)->format(Time::FORMAT_HOURS);
        echo PHP_EOL . Time::days(1.3245)->format(Time::FORMAT_DAYS);
        echo PHP_EOL . Time::days(14.3245)->format(Time::FORMAT_WEEKS);
        echo PHP_EOL . Time::days(645.3245)->format(Time::FORMAT_YEARS);


        dd(1);
        $a = Time::years(4);
        $b = Time::years(89);

        //dd(PHP_EOL, $a->sec, $b->sec);

        $ra = Angle::deg(129.543)->toTime();
        $ra->format(Time::FORMAT_HMS);
        $dec = Angle::deg(-12.3545);


        echo PHP_EOL;
        echo PHP_EOL;
        echo "{$ra} {$dec}";
        echo PHP_EOL;
        echo PHP_EOL;


        $ra = Time::hms(10, 4, 23.542);
        $ra->format(Time::FORMAT_HMS);
        $dec = Angle::dms(30, 23, 12.432);


        echo PHP_EOL;
        echo PHP_EOL;
        echo "{$ra} {$dec}";
        echo PHP_EOL;
        echo PHP_EOL;


        echo PHP_EOL;
        echo Time::weeks(1);
        echo PHP_EOL;
        echo Time::days(12)->format('3W week\s');
        echo PHP_EOL;

        echo Time::days(502)->format('3Y year\s');
        echo PHP_EOL;


        echo Time::years(20)->format('9M \min');
        echo PHP_EOL;
        echo Time::years(29)->format('9M \min');
        echo PHP_EOL;

        echo Time::years(1)->format(Time::FORMAT_DEFAULT);
        echo PHP_EOL;
    }


}






