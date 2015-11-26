# axy\crypt

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
