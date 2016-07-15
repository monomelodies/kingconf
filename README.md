# Kingconf
Load configuration in any sane format

Some prefer JSON, some prefer YAML. Some even prefer XML... why not allow them
all and let the user decide?

`Kingconf` loads configuration from any of the following formats:
- `json`: A JSON as a key/value store.
- `yaml`: A Yaml file.
- `xml`: An XML file. It should contain a root element which can be called
         anything.
- `ini`: The PHP .ini format.
- `php`: Plain ol' PHP (`return ['key' => 'value']` format).

## Installation

### Composer (recommended)
```bash
$ cd /path/to/project
$ composer require monomelodies/kingconf
```

### Manual
- Download or clone the repository somewhere;
- Add `/path/to/kingconf/src` to your autoloader for PSR-4 namespace
  `Monomelodies\\Kingconf\\`.

## Usage
Create a `Monomelodies\Kingconf\Config` object with one or more config file
paths as the constructor arguments:

```php
<?php

use Monomelodies\Kingconf\Config;

$config = new Config('/path/to/json', '/path/to/ini');
```

Note that the first value found is leading, i.e. if in the above example the
JSON defines `foo = bar` and the ini `foo = baz`, `foo` will remain `bar`.

The resulting `$config` object simply extends PHP's `ArrayObject`, so you can
access it like any other array:

```php
<?php

echo $config['foo']; // (string)"bar"
```

## Errors and exceptions
Kingconf will throw exceptions if config files can't be read, contain invalid
syntax etc. So, if you can't be sure about the contents (e.g. user submitted
data) be sure to wrap the constructor in a try/catch block:

```php
<?php

use Monomelodies\Kingconf\Config;
use Monomelodies\Kingconf\Exception;

try {
    $config = new Config($some_file_we_dont_trust);
} catch (Exception $e) {
    echo "Config invalid!!!1";
}
```

Note that user submitted configuration is probably best handled in a different
manner though...

> All Kingconf exceptions extend `Monomelodies\Kingconf\Exception`, which in
> turn extends `DomainException`.

