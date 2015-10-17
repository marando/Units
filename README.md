Units
=====
Units is a PHP package consisting of classes representing units of measure.

The following units are provided:
* Angle
* Distance
* Time
* Velocity

Angle
-----

#### Creating Angles from Degrees and Radians
```php
Angle::fromDeg(180);
Angle::fromRad(3);
```

#### Creating Angles from Degrees, Minutes and Seconds
```php
Angle::fromDMS(180, 10, 4);
```

#### Getting the Properties of an Angle
```php
$angle = Angle::fromDMS(180, 30, 0);

echo $angle->deg;  // degrees           Output: 180.5 
echo $angle->rad;  // radians           Output: 3.1503192998498
echo $angle->d;    // degree component  Output: 180
echo $angle->m;    // minute component  Output: 30
echo $angle->s;    // second component  Output: 0
```

#### Normalizing Angles
Angles can easily be normalized to a convenient interval as such:
```php
echo Angle::fromDeg(480)->norm(0, 360);  // normalize to [0, 2π]  Output: 120°0'0".0
echo Angle::fromDeg(700)->norm(0, 180);  // normalize to [0, π]   Output: 160°0'0".0
```

#### Mathematical Operations
The `Angle` object supports the mathematical operations addition, subtraction and multiplication as well as negation. Please note that the angle instance will not be altered, but instead a new instance is returned with the new value based on the operation
```php
echo Angle::fromDeg(90)->add(Angle::fromDeg(90));       // addition        Output: 180°0'0".0
echo Angle::fromDeg(90)->subtract(Angle::fromDeg(90));  // subtraction     Output: 180°0'0".0
echo Angle::fromDeg(90)->multiply(Angle::fromDeg(3));   // multiplication  Output: 270°0'0".0
echo Angle::fromDeg(90)->negate();                      // negation        Output: -90°0'0".0
```



