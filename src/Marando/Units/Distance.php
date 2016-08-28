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

namespace Marando\Units;

use Exception;
use Marando\Units\Traits\CopyTrait;

/**
 * Represents a distance.
 *
 * @property double $km  Kilometers
 * @property double $hm  Hectometers
 * @property double $dam Decameters
 * @property double $m   Meters
 * @property double $dm  Decimeters
 * @property double $cm  Centimeters
 * @property double $mm  Millimeters
 * @property double $μm  Micrometers
 * @property double $nm  Nanometers
 * @property double $pm  Picometers
 * @property double $mi  Miles
 * @property double $yd  Yards
 * @property double $ft  Feet
 * @property double $in  Inches
 * @property double $au  Astronomical Units
 * @property double $ly  Light-years
 * @property double $pc  Parsecs
 *
 * @author Ashley Marando <a.marando@me.com>
 */
class Distance
{

    use CopyTrait;

    //--------------------------------------------------------------------------
    // Constants
    //--------------------------------------------------------------------------

    /**
     * Default number format for string values.
     */
    const FORMAT_DEFAULT = "%3.3f ";

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Creates a new distance from a number of meters.
     *
     * @param double|string $m Meters
     */
    private function __construct($m)
    {
        // Trim excessive zeros if meters was passed as string.
        $this->m = static::removeTrailingZeros(substr($m, 0, 999));
    }

    // // // Static

    /**
     * Creates a new distance from a value and symbol.
     *
     * @param double|string $value  Value of the distance
     * @param string        $symbol Symbol of the distance, e.g. m or km
     *
     * @return static
     */
    public static function create($value, $symbol)
    {
        // Find the conversion factor to meters
        $factor = static::factorToMeters($symbol);

        // Ensure proper formatting of value and factor for bc math
        $value  = number_format($value, static::$s, '.', '');
        $factor = number_format($factor, static::$s, '.', '');

        // Convert to meters
        $meters = bcmul($value, $factor, static::$s);

        // Create new distance.
        $dist = new static($meters);
        $dist->format(static::FORMAT_DEFAULT . $symbol);

        return $dist;
    }

    /**
     * Creates a new distance from a number of kilometers, km
     *
     * @param double|string $km Kilometers. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function km($km)
    {
        return static::create($km, 'km');
    }

    /**
     * Creates a new distance from a number of hectometers, hm
     *
     * @param double|string $hm Hectometers. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function hm($hm)
    {
        return static::create($hm, 'hm');
    }

    /**
     * Creates a new distance from a number of decameters, dam
     *
     * @param double|string $dam Decameters. May be provided as a string for
     *                           higher precision.
     *
     * @return static
     */
    public static function dam($dam)
    {
        return static::create($dam, 'dam');
    }

    /**
     * Creates a new distance from a number of meters, m
     *
     * @param double|string $m Meters. May be provided as a string for higher
     *                         precision.
     *
     * @return static
     */
    public static function m($m)
    {
        return static::create($m, 'm');
    }

    /**
     * Creates a new distance from a number of decimeters, dm
     *
     * @param double|string $dm Decimeters. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function dm($dm)
    {
        return static::create($dm, 'dm');
    }

    /**
     * Creates a new distance from a number of centimeters, cm
     *
     * @param double|string $cm Centimeters. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function cm($cm)
    {
        return static::create($cm, 'cm');
    }

    /**
     * Creates a new distance from a number of millimeters, mm
     *
     * @param double|string $mm Millimeters. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function mm($mm)
    {
        return static::create($mm, 'mm');
    }

    /**
     * Creates a new distance from a number of micrometers, μm
     *
     * @param double|string $μm Micrometers. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function μm($μm)
    {
        return static::create($μm, 'μm');
    }

    /**
     * Creates a new distance from a number of nanometers, nm
     *
     * @param double|string $nm Nanometers. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function nm($nm)
    {
        return static::create($nm, 'nm');
    }

    /**
     * Creates a new distance from a number of picometers, pm
     *
     * @param double|string $pm Picometers. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function pm($pm)
    {
        return static::create($pm, 'pm');
    }

    /**
     * Creates a new distance from a number of miles, mi
     *
     * @param double|string $mi Miles. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function mi($mi)
    {
        return static::create($mi, 'mi');
    }

    /**
     * Creates a new distance from a number of yards, yd
     *
     * @param double|string $yd Yards. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function yd($yd)
    {
        return static::create($yd, 'yd');
    }

    /**
     * Creates a new distance from a number of feet, ft
     *
     * @param double|string $ft Feet. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function ft($ft)
    {
        return static::create($ft, 'ft');
    }

    /**
     * Creates a new distance from a number of inches, in
     *
     * @param double|string $in Inches. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function in($in)
    {
        return static::create($in, 'in');
    }

    /**
     * Creates a new distance from a number of astronomical units, au
     *
     * @param double|string $au Astronomical units. May be provided as a string
     *                          for higher precision.
     *
     * @return static
     */
    public static function au($au)
    {
        return static::create($au, 'au');
    }

    /**
     * Creates a new distance from a number of light-years, ly
     *
     * @param double|string $ly Light-years. May be provided as a string for
     *                          higher precision.
     *
     * @return static
     */
    public static function ly($ly)
    {
        return static::create($ly, 'ly');
    }

    /**
     * Creates a new distance from a number of parsecs, pc
     *
     * @param double|string $pc Parsecs. May be provided as a string for higher
     *                          precision.
     *
     * @return static
     */
    public static function pc($pc)
    {
        return static::create($pc, 'pc');
    }

    /**
     * Creates a new distance instance from an angular measurement of
     * astronomical parallax.
     *
     * @param Angle $parallax Angular parallax measurement
     *
     * @return static
     */
    public static function parallax(Angle $parallax)
    {
        // 1 parsec = reciprocal of parallax in arcseconds.
        $parsecs = 1 / $parallax->asec;

        return static::pc($parsecs);
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    /**
     * Value of this instance expressed in meters and stored as a string value
     * to self::$p level scale for bc math operations.
     *
     * @var string
     */
    private $m;

    /**
     * String format of this instance.
     *
     * @var string
     */
    private $format;

    /**
     * Scale of this instance to be used for bc math operations.
     *
     * @var int
     */
    private static $s = 999;

    public function __get($name)
    {
        switch ($name) {
            case 'parallax':
                return $this->toParallax();

            // SI
            case 'km':
            case 'hm':
            case 'dam':
            case 'm':
            case 'dm':
            case 'cm':
            case 'mm':
            case 'μm':
            case 'nm':
            case 'pm':
                // Imperial
            case 'mi':
            case 'yd':
            case 'ft':
            case 'in':
                // Astronomy
            case 'au':
            case 'ly':
            case 'pc':
                return $this->unit($name);

            //default:
            //    throw new Exception("{$name} is not a valid property.");
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'parallax':
                return $this->toParallax();

            // SI
            case 'km':
            case 'hm':
            case 'dam':
            case 'm':
            case 'dm':
            case 'cm':
            case 'mm':
            case 'μm':
            case 'nm':
            case 'pm':
                // Imperial
            case 'mi':
            case 'yd':
            case 'ft':
            case 'in':
                // Astronomy
            case 'au':
            case 'ly':
            case 'pc':
                $this->m = static::factorToMeters($name) * $value;

                //default:
                //    throw new Exception("{$name} is not a valid property.");
        }
    }

    //
    // Functions
    //

    /**
     * Returns the value of this instance at the specified unit optionally as
     * a string with higher precision.
     *
     * @param string $symbol Symbol of the unit to obtain
     * @param bool   $string True to return as string, false for double
     *
     * @return double|string
     */
    public function unit($symbol, $string = false)
    {
        // Format instance's meter value and factor to the specified unit.
        $m    = number_format($this->m, static::$s, '.', '');
        $fact = number_format(static::factorToMeters($symbol), static::$s, '.',
          '');

        // Divide the meters by the factor and trim end of zeros
        $unit = bcdiv($m, $fact, static::$s);
        $unit = static::removeTrailingZeros($unit);

        // Return rounded double value, or string value.
        return $string ? $unit : round($unit, 20);
    }

    /**
     * Removes trailing zeros for a numeric value expressed as a string.
     *
     * @param $num
     *
     * @return string
     */
    private static function removeTrailingZeros($num)
    {
        // Only do this if number has a decimal value.
        if (strstr($num, '.')) {
            $num  = rtrim($num, '0');
            $last = substr($num, strlen($num) - 1, 1) == '.';

            // If last char is period, add another zero
            //$unit = $last ? "{$unit}0" : $unit;
            // If last char is period, remove the period.
            $num = $last ? substr($num, 0, strlen($num) - 1) : $num;

            return $num;
        } else {
            // No decimal value, don't modify number
            return $num;
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    /**
     * Adds another distance to this instance and returns a new instance with
     * the sum.
     *
     * @param Distance $b Distance to add
     *
     * @return static Sum of the two distances
     */
    public function add(Distance $b)
    {
        return Distance::m(bcadd($this->m, $b->m), static::$s);
    }

    /**
     * Subtracts another distance from this instance and returns a new instance
     * with the difference.
     *
     * @param Distance $b Distance to subtract
     *
     * @return static Difference of the two distances
     */
    public function sub(Distance $b)
    {
        return Distance::m(bcsub($this->m, $b->m), static::$s);
    }

//    /**
//     * Multiplies another distance with this instance and returns a new
//     * instance with the product.
//     *
//     * @param Distance2 $b Distance to add
//     *
//     * @return static Sum of the two distances
//     */
//    public function mul(Distance2 $b)
//    {
//        return Distance2::m(bcmul($this->m, $b->m), static::$s);
//    }
//
//    /**
//     * Divides this instance by another distance and returns a new instance with
//     * the quotient.
//     *
//     * @param Distance2 $b Divisor distance
//     *
//     * @return static Quotient of the two distances
//     */
//    public function div(Distance2 $b)
//    {
//        return Distance2::m(bcdiv($this->m / $b->m), static::$s);
//    }

    /**
     * Returns a new distance with the negation of this instance.
     *
     * @return static
     */
    public function neg()
    {
        return Distance::m(bcmul($this->m, -1), static::$s);
    }

    /**
     * Formats this instance in the specified strign format.
     *
     * @param string $format Formatter string, e.g. %01.3f au
     *
     * @return string
     */
    public function format($format)
    {
        // Sprintf and unit regex
        $pattern = '/(%(?:\d+\$)?[+-]?(?:[ 0]|\'.{1})?-?\d*(?:\.\d+)?[bcdeEufFgGosxX])(.*)/';

        // Check if string has sprintf and unit...
        if (preg_match_all($pattern, $format, $m)) {
            // Valid format, save to instance.
            $this->format = $format;

            // Get sprintf and units
            $sprintf = $m[1][0];
            $unit    = $m[2][0];

            // Try to find value of instance at specified units.
            $value = $this->{trim($unit)};
            if ($value == 0) {
                $value = $this->{strtolower(trim($unit))};
                if ($value == 0) {
                    $value = $this->{strtoupper(trim($unit))};
                }
            }

            // Should just display as scientific notation?
            if (sprintf($sprintf, $value) == 0 || $value > 10e9) {
                // Change sprintf to use e for scientific notation
                $sprintf = preg_replace('/[bcdeEufFgGosxX]/', 'e', $sprintf);
            }

            // Return value with format and unit suffix.
            return sprintf($sprintf, $value) . $unit;
        } else {
            // No valid format found, use old format.
            if (preg_match_all($pattern, $this->format, $m)) {
                return $this->format(
                  str_replace(trim($m[2][0]), '', $m[0][0]) . $format);
            }
        }
    }

    // // // Private

    /**
     * Converts this distance to a parallax measurement of astronomical
     * parallax.
     *
     * @return Angle
     */
    private function toParallax()
    {
        return Angle::asec(1 / $this->pc);
    }

    /**
     * Finds the conversion factor of a unit by symbol to meters.
     *
     * @param string $symbol Symbol of the unit to convert to meters.
     *
     * @return double|string
     */
    private static function factorToMeters($symbol)
    {
        $units = [
            // SI
          'km'  => 1e3,
          'hm'  => 1e2,
          'dam' => 1e1,
          'm'   => 1,
          'dm'  => 1e-1,
          'cm'  => 1e-2,
          'mm'  => 1e-3,
          'μm'  => 1e-6,
          'nm'  => 1e-9,
          'pm'  => 1e-12,
            // Imperial
          'mi'  => 1609.344,
          'yd'  => 0.9144,
          'ft'  => 0.3048,
          'in'  => 0.0254,
            // Astronomy
          'au'  => '149597870700',
          'ly'  => '9460730472580800',
          'pc'  => '30856776376340067',
        ];

        return $units[$symbol];
    }

    // // // Overrides

    /**
     * Represents this instance as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format($this->format);
    }

}
