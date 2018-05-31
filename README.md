# GBX Parser
This component is written to parse Maniaplanet engine's produced GBX Map files and extract useful information from it.
**Note:** this package requires  a 64-bit PHP installation to function properly.

# Installation
Just run `$ composer require eslkem/gbx-parser` or add `"eslkem/gbx-parser": "^1.0"` line to your composer.json file followed by a `$ composer install` or `$composer update` command.

# Setup and usage
 Using the package is straight forward: do not forget to include 
```php
require_once __DIR__.'/vendor/autoload.php';
 ```
and use the following classes:
```php
use ESLKem\GBXParser\Parser;
use ESLKem\GBXParser\Models\Map;
```
Sample usage:
```php
$map = Parser::parse('./path/to/file.gbx');
echo $map->getName();
```
# Documentation 
The full documentation is available [here](https://eslkem.github.io/gbx-parser/).

# Testing
This package is tested using PHPUnit. To run the tests, simply execute `$ ./vendor/bin/phpunit`.
