# Config

[![Build Status](https://travis-ci.org/SlaxWeb/Config.svg?branch=0.1.0)](https://travis-ci.org/SlaxWeb/Config)

Config component of the SlaxWeb\Framework handles loading and parsing of
configuration options from multiple sources. Currently only file based
resources are supported, and can be in 3 formats, PHP, XML, or Yaml.

Requirements
============

* PHP 7.0+
* desperado/xml-bundle 0.1.\* package - for XML configuration handler
* symfony/yaml 3.0.\* package - for Yaml configuration handler
* pimple/pimple 3.0.\* package - to use the provided service provider

Installation
============

Easiest form of installation is through [composer](https://getcomposer.org/),
just require the package in your *composer.json* file:

```js
{
    "require": {
        "slaxweb/config": "~0.1"
    }
}
```

And this should get you started to use the Config component, with the PHP
configuration handler. If you want to use XML or Yaml configuration providers,
you need to install their respective required packages with composer (see
Requirements).

Usage
=====

The Config component provides you a *Factory* class for easier instantiation.
To get started, simply call the **init** static method of the Factory with
the correct constant for the configuration handler you want to use, and the path
to your configuration file location. Configuration handler constants:
* \SlaxWeb\Config\Container::PHP_CONFIG_HANDLER
* \SlaxWeb\Config\Container::XML_CONFIG_HANDLER
* \SlaxWeb\Config\Container::YAML_CONFIG_HANDLER

```php
$config = \SlaxWeb\Config\Factory::init(
    \SlaxWeb\Config\Container::PHP_CONFIG_HANDLER,
    "/path/to/configuration/"
);
```

The factory will automatically instantiate the correct configuration handler
and inject it to the Config class, along with your provided configuration
resource location.

Manipulating configuration
--------------------------

The Container class implements *ArrayAccess* and must be used as such.
Retrieving, setting, removing and checking for existence, is done like if it
were on an array. For loading of new configuration resources, the **load**
method is provided. Example PHP configuration file:

```php
<?php
$configuration["foo"] = "bar";
$configuration["baz"] = true;
```

The PHP configuration file requires the *$configuration* array, and all of your
configuration items must be set to it.

To load the configuration file, just place it inside your
*/path/to/configuration* and call **load** method with the file name:

```php
$config->load("myconfig.php");
```

After the configuration file has been loaded you can normally do operations on
*$config* as if it is a simple array:

```php
if (isset($config["foo"])) {
    $foo = $config["foo"];
}
$config["foo"] = "baz";
unset($config["baz"]);
```

Using the Provider
------------------

If you are using the Pimple\Pimple Dependency Injection Container, you can use
the provided Service Provider. Make sure that before you are going to use the
config.service, that you have set the *configResourceLocation*, and the
*configHandler* properties in your container. To use the provider, simply
register it with your container:

```php
<?php
use Pimple\Container;

$container = new Container;

$container->register(new \SlaxWeb\Config\Service\Provider);

$container["configHandler"] = \SlaxWeb\Config\Container::PHP_CONFIG_HANDLER;
$container["configResourceLocation"] = "/path/to/configuration";

$container["service.provider"]->load("myconfig.php");
```
