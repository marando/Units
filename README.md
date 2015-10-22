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
Angle::deg(180);  // create angle from degrees
Angle::rad(3);    // create angle from radians
```

#### Creating Angles from Degrees, Minutes and Seconds
You can also create an `Angle` from degree, minute and second components:
```php
echo Angle::dms(180, 10, 4);  // create angle from ° ' "
echo Angle::dms(0, 0, 4);     // create angle from just seconds
```

#### Getting the Properties of an Angle
The following properties can be obtained from `Angle` instances:
```php
$angle = Angle::dms(180, 30, 0);

echo $angle->deg;  // degrees           Output: 180.5 
echo $angle->rad;  // radians           Output: 3.1503192998498
echo $angle->d;    // degree component  Output: 180
echo $angle->m;    // minute component  Output: 30
echo $angle->s;    // second component  Output: 0
```

#### Normalizing Angles
If you need to normalize an `Angle` to a specific interval you can do it as such:
```php
echo Angle::deg(480)->norm(0, 360);      // normalize to [0, +2π]    Output: 120°0'0".0
echo Angle::deg>norm(0, 180);      // normalize to [0, +π]     Output: 160°0'0".0
echo Angle::deg->norm(-360, 360);  // normalize to [-2π, +2π]  Output: -360°0'0".0
```

#### Mathematical Operations for Angles
The `Angle` object supports the mathematical operations addition, subtraction and multiplication as well as negation.
```php
echo Angle::deg(90)->add(Angle::fromDeg(90));       // addition        Output: 180°0'0".0
echo Angle::deg(90)->subtract(Angle::fromDeg(90));  // subtraction     Output: 180°0'0".0
echo Angle::deg(90)->multiply(Angle::fromDeg(3));   // multiplication  Output: 270°0'0".0
echo Angle::deg(90)->negate();                      // negation        Output: -90°0'0".0
```

#### String Value of an Angle
By default the string value of an `Angle` is expressed in the format `180°0'0".0` up to three decimal places. You can use the round method to specify the number of decimal places to display:
```php
echo Angle::rad(3)->round(0);   // round to nearest second  Output: 171°53'14"
echo Angle::rad(3)->round(3);   // round to 3 places        Output: 171°53'14".419
echo Angle::rad(3)->round(10);  // round to 10 places       Output: 171°53'14".4187412891
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
By default the string value of a `Distance` is expressed using three decimal places. You can use the round method to specify the number of decimal places to display:
```php
echo Distance::km(2/3)->round(0);   // round to nearest unit  Output: 1 km
echo Distance::km(2/3)->round(3);   // round to 3 places      Output: 0.667 km
echo Distance::km(2/3)->round(10);  // round to 10 places     Output: 0.6666666667 km
```

#### Conversion between Units
Once you have created a `Distance` instance, conversion between different units is simple:
```php
echo Distance::mi(1)->km;              // get the raw kilometer value  Output: 1.609344
echo Distance::mi(1)->setUnit('km');   // set units to kilometers      Output: 1.609 km
```
Valid values for the `setUnit()` are as follows: `mm`, `cm`, `m`, `km`, `mi`, `au`, `pc`, and `ly`

#### Overriding Unit Definitions
Sometimes you may wish to provide the parameters for the definition of a distance unit (usually in the case of historic applications). For example, you may wish to create a distance of 5 astronomica units, but using the old definition of `149597870.691` kilometers/AU instead of the current definition. This is possible as follows:
```php
// Create distance with default and custom AU definitions
$default = Distance::au(1.5); 
$custom  = Distance::au(1.5, Distance::km(149597870.691)); 

// AU is the same since that's what we supplied
echo $default;      // Output: 1.500 AU 
echo $custom;       // Output: 1.500 AU 

// Conveersions are different depending on the AU definition
echo $default->km;  // Output: 224396806.05
echo $custom->km;   // Output: 224396806.0365
```
Overriding definition parameters is currenly only possible for astronomical units, light-years and parsecs.


Time
----
#### Creating Time Instances
Time instances are simple to create:
```php
echo Time::hms(1, 10, 15);  // create time from h/m/s components  Output: 1ʰ10ᵐ15ˢ
echo Time::sec(30);         // create time from seconds           Output: 30 sec
echo Time::min(4);          // create time from minutes           Output: 5 min
echo Time::hours(2);        // create time from hours             Output: 2 hour
echo Time::days(1);         // create time from days              Output: 1 day
```

#### String Value of a Time Instance
By default the string value of a `Time` instance is expressed using three decimal places. You can use the round method to specify the number of decimal places to display:
```php
echo Time::days(2/3)->round(0);   // round to nearest unit  Output: 1 day
echo Time::days(2/3)->round(3);   // round to 3 places      Output: 0.667 days
echo Time::days(2/3)->round(10);  // round to 10 places     Output: 0.6666666667 days
```

#### Conversion between Units
Once you have created a `Time` instance, conversion between different units is simple:
```php
echo Time::days(0.54921)->min;             // get the raw minute value  Output: 790.8624
echo Time::days(0.54921)->setUnit('min');  // set units to minutes      Output: 790.862 min
```
Valid values for the `setUnit()` are as follows: `sec`, `min`, `hours`, `days`, `hms`

#### Mathematical Operations for Time Instances
The `Time` object supports the mathematical operations addition and subtraction. 
```php
echo Time::sec(90)->add(Time::min(30));       // addition     Output: 0ʰ31ᵐ30ˢ
echo Time::sec(90)->subtract(Time::min(30));  // subtraction  Output: -0ʰ28ᵐ30ˢ
```

#### Getting the H/M/S Components
The hour, minute and second components of a `Time` instance can be obtained as shown:
```php
$time = Time::sec(83028);

echo $time->h; // degree component  Output: 21
echo $time->m; // minute component  Output: 8
echo $time->s; // second component  Output: 43
```


Velocity
--------

#### Creating a Velocity Instance
A `Velocity` instance can be created two ways:

  1. By providing distance and time components, and  
  2. By using a pre-defined static constructor  

```php
echo new Velocity(Distance::m(5), Time::sec(1));  // Method 1  Output: 5 m/s
echo Velocity::ms(5);                             // Method 2  Output: 5 m/s
```

The first method is especially powerful for cases with non-uniform time denominators. By creating a velocity instance this way you can for example easily resolve unknown velocities from observational data:
```php
$velocity = new Velocity(Distance::m(507), Time::sec(14.532));

echo $velocity->ms;              // in m/s  Output: 34.888521882742
echo $velocity->setUnit('ms');   // in m/s  Output: 34.889 m/s

echo $velocity->mph;             // in mph  Output: 78.043400775639
echo $velocity->setUnit('mph');  // in mph  Output: 78.043 mph
```

As per the second method, the following pre-defined unit constructors exist:

Method            | Unit | Description
----------------- | ---- | ----------------------
`Velocity::kmd()` | km/d | kilometers per day
`Velocity::kmh()` | km/h | kilometers per hour
`Velocity::kms()` | km/s | kilometers per second
`Velocity::mph()` | mph  | miles per hour
`Velocity::ms()`  | m/s  | meters per second
`Velocity::pcy()` | pc/y | parsecs per year



#### String Value of a Velocity Instance
By default the string value of a `Velocity` instance is expressed using three decimal places. You can use the round method to specify the number of decimal places to display:
```php
echo Velocity::mph(2/3)->round(0);   // round to nearest unit  Output: 1 mph
echo Velocity::mph(2/3)->round(3);   // round to 3 places      Output: 0.667 mph
echo Velocity::mph(2/3)->round(10);  // round to 10 places     Output: 0.6666666667 mph
```

#### Conversion between Units
Once you have created a `Velocity` instance, conversion between different units is simple:
```php
echo Velocity::mph(60)->ms;              // get the raw m/s value  Output: 26.8224
echo Velocity::mph(60)->setUnit('m/s');  // set units to m/s       Output: 26.822 m/s
```
Valid values for the `setUnit()` are as follows: `km/d`, `km/h`, `km/s`, `mph`, `m/s`, and `pc/y`

#### Distance and Time Components of a Velocity
You can get the distance and time components of a `Velocity` instance as follows:
```php
echo Velocity::kmh(10)->getDistance();  // get distance component  Output: 10.000 km
echo Velocity::kmh(10)->getTime();      // get time component      Output: 1 hour
```













