# axy\crypt

[![Latest Stable Version](https://img.shields.io/packagist/v/axy/crypt.svg?style=flat-square)](https://packagist.org/packages/axy/crypt)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://img.shields.io/travis/axypro/crypt/master.svg?style=flat-square)](https://travis-ci.org/axypro/crypt)

Some crypt algorithms.

* GitHub: [axypro/crypt](https://github.com/axypro/crypt)
* Composer: [axy/crypt](https://packagist.org/packages/axy/crypt)

PHP 5.4+

Library does not require any dependencies.

### APR1: Apache APR1-MD5 algorithm

```php
use axy\crypt\APR1;

$hash = APR1::hash($string);
APR1::verify($string, $hash); // TRUE
```

### BCrypt

```php
use axy\crypt\BCrypt;

$hash = BCrypt::hash($string);
BCrypt::verify($string, $hash); // TRUE
```

Set computed time (5 by default):

```php
$hash = BCrypt::hash($string, 10);
```
