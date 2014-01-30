vcloud-sdk-php
==============

**WARNING:** this project is deprecated and no maintained anymore. Please use
[vmware-vcloud-sdk-php-patched](https://github.com/amercier/vmware-vcloud-sdk-php-patched) instead

21st century PHP SDK for vCloud Director, based on [a patched version of VMWare
SDK for PHP](https://github.com/amercier/vmware-vcloud-sdk-php-patched).

[![Build Status](https://travis-ci.org/amercier/vcloud-sdk-php.png?branch=master)](https://travis-ci.org/amercier/vcloud-sdk-php)
[![Coverage Status](https://coveralls.io/repos/amercier/vcloud-sdk-php/badge.png?branch=master)](https://coveralls.io/r/amercier/vcloud-sdk-php?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/5200f4fa632bac07760081ec/badge.png)](https://www.versioneye.com/user/projects/5200f4fa632bac07760081ec)

[![Latest Stable Version](https://poser.pugx.org/amercier/vcloud-sdk/v/stable.png)](https://packagist.org/packages/amercier/vcloud-sdk)


✔ **Object-oriented** - uses [PHP 5.3 namespaces](http://php.net/manual/en/language.namespaces.php)  
✔ **Hand-crafted** - no more auto-generated SOAP bindings!  
✔ **Clean** - follows [PSR-1](http://www.php-fig.org/psr/1/) and [PSR-2](http://www.php-fig.org/psr/2/) coding standards  
✔ **Interoperable** - follows the [PSR-0](http://www.php-fig.org/psr/0/) autoloading standard  
✔ **Extensible** - no private fields or methods, only public and protected  
✔ **Easily installable** - available with [Composer](http://getcomposer.org/)  
✔ **Maintainable** - it's open source! So clone it, fork it, tune it, fix it as you please!  



Installation
------------

### 1. Install Composer

    curl https://getcomposer.org/installer | php --

Note: you may need to have `https_proxy` variable set if you are behind a proxy.

### 2. Create _composer.json_

    php composer.phar init

### 3. Edit _composer.json_

```
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "pear",
            "url": "http://pear.php.net"
        }
    ],
    "require": {
        "amercier/vcloud-sdk": "*"
    }
```

### 4. Install dependencies

    php composer.phar update


Licensing
---------

This project is released under [MIT License](LICENSE) license. If this license
does not fit your requirement for whatever reason, but you would be interested
in using the work (as defined below) under another license, please contact
Alexandre Mercier at pro.alexandre.mercier@gmail.com .


Contributing
------------

Contributions (issues ♥, pull requests ♥♥♥) are more than welcome! Feel free to
clone, fork, modify, extend, etc, as long as you respect the
[license terms](LICENSE-CC-BY.md).

**Installation**: after cloning the project, all you need to to is execute
`make install`.

**SDK Generation**: you need to execute `make` in order to generate SDK from the
original SDK.

**Testing**: `make test`

