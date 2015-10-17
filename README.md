Units
=====
Units is a PHP package consisting of classes representing units of measure.

The following units are provided:
* [Angle](https://github.com/marando/Units/blob/master/README.md#angle)
* [Distance](https://github.com/marando/Units/blob/master/README.md#distance)
* [Time](https://github.com/marando/Units/blob/master/README.md#time)
* [Velocity](https://github.com/marando/Units/blob/master/README.md#velocity)



Angle
-----

#### Creating Angles from Degrees and Radians
An `Angle` can easily be created from degree or radian values as such:
```php
Angle::fromDeg(180);  // create angle from degrees
Angle::fromRad(3);    // create angle from radians
```

#### Creating Angles from Degrees, Minutes and Seconds
You can also create an `Angle` from degree, minute and second components:
```php
echo Angle::fromDMS(180, 10, 4);  // create angle from ° ' "
echo Angle::fromDMS(0, 0, 4);     // create angle from just seconds
```

#### Getting the Properties of an Angle
The following properties can be obtained from `Angle` instances:
```php
$angle = Angle::fromDMS(180, 30, 0);

echo $angle->deg;  // degrees           Output: 180.5 
echo $angle->rad;  // radians           Output: 3.1503192998498
echo $angle->d;    // degree component  Output: 180
echo $angle->m;    // minute component  Output: 30
echo $angle->s;    // second component  Output: 0
```

#### Normalizing Angles
If you need to normalize an `Angle` to a specific interval you can do it as such:
```php
echo Angle::fromDeg(480)->norm(0, 360);      // normalize to [0, +2π]    Output: 120°0'0".0
echo Angle::fromDeg(700)->norm(0, 180);      // normalize to [0, +π]     Output: 160°0'0".0
echo Angle::fromDeg(-720)->norm(-360, 360);  // normalize to [-2π, +2π]  Output: -360°0'0".0
```

#### Mathematical Operations for Angles
The `Angle` object supports the mathematical operations addition, subtraction and multiplication as well as negation. Please note that the `Angle` instance will not be altered, but instead a new `Angle` instance will be returned with the new angular value.
```php
echo Angle::fromDeg(90)->add(Angle::fromDeg(90));       // addition        Output: 180°0'0".0
echo Angle::fromDeg(90)->subtract(Angle::fromDeg(90));  // subtraction     Output: 180°0'0".0
echo Angle::fromDeg(90)->multiply(Angle::fromDeg(3));   // multiplication  Output: 270°0'0".0
echo Angle::fromDeg(90)->negate();                      // negation        Output: -90°0'0".0
```

#### String Value of an Angle
By default the string value of an `Angle` is expressed in the format `180°0'0".0` up to three decimal places. You can use the round method to specify the number of decimal places to display:
```php
echo Angle::fromRad(3)->round(0);   // round to nearest second  Output: 171°53'14"
echo Angle::fromRad(3)->round(3);   // round to 3 places        Output: 171°53'14".419
echo Angle::fromRad(3)->round(10);  // round to 10 places       Output: 171°53'14".4187412891
```


Distance
--------
#### Creating Distances
```php
// Metric
echo Distance::mm(1);  // create distance from millimeters         Output: 1.000 mm
echo Distance::cm(1);  // create distance from centimeters         Output: 1.000 cm
echo Distance::m(1);   // create distance from meters              Output: 1.000 m
echo Distance::km(1);  // create distance from kilometers          Output: 1.000 km

// Imperial
echo Distance::mi(1);  // create distance from miles               Output: 1.000 mi

// Astronomy
echo Distance::au(1);  // create distance from astronomical units  Output: 1.000 AU
echo Distance::pc(1);  // create distance from parsecs             Output: 1.000 pc
echo Distance::ly(1);  // create distance from light-years         Output: 1.000 ly
```

#### String Value of a Distance
By default the string value of a `Distance` is expressed in the format `1.000 km` using three decimal places. You can use the round method to specify the number of decimal places to display:
```php
echo Distance::km(2/3)->round(0);   // round to nearest unit  Output: 1 km
echo Distance::km(2/3)->round(3);   // round to 3 places      Output: 0.667 km
echo Distance::km(2/3)->round(10);  // round to 10 places     Output: 0.6666666667 km
```

#### Conversion between Units
Once you have created a `Distance` instance, conversion between different units is simple:
```php
echo Distance::mi(1)->km              // get the raw kilometer value  Output: 1.609344
echo Distance::mi(1)->setUnit('km');  // set units to kilometers      Output: 1.609 km
```
Valid values for the `setUnit()` are as follows: `mm`, `cm`, `m`, `km`, `mi`, `au`, `pc`, and `ly`


