# axy\crypt

Some crypt algorithms.

[![Latest Stable Version](https://img.shields.io/packagist/v/axy/crypt.svg?style=flat-square)](https://packagist.org/packages/axy/crypt)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg?style=flat-square)](https://php.net/)
[![Tests](https://github.com/axypro/crypt/actions/workflows/test.yml/badge.svg)](https://github.com/axypro/crypt/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/axypro/crypt/badge.svg?branch=master)](https://coveralls.io/github/axypro/crypt?branch=master)
[![License](https://poser.pugx.org/axy/crypt/license)](LICENSE)

### Documentation

#### APR1: Apache APR1-MD5 algorithm

```php
use axy\crypt\APR1;

$hash = APR1::hash($string);
APR1::verify($string, $hash); // TRUE
```

#### BCrypt

```php
use axy\crypt\BCrypt;

$hash = BCrypt::hash($string);
BCrypt::verify($string, $hash); // TRUE
```

Set computed time (5 by default):

```php
$hash = BCrypt::hash($string, 10);
```
