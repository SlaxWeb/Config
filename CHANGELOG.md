# ChangeLog

Changes between versions.

## Current changes

* remove service provider (moved to main bootstrap component)
* remove unneeded exception (moved to main bootstrap component)
* add JSON configuration handler

## v0.2

### v0.2.1

* minor code improvements

### v0.2.0

* pass resource location to handler, instead of container
* multiple locations can be added as resource location
    * resource location parameter now has to be an array of strings
* add resource location at runtime method

## v0.1

### v0.1.2

* fix bug where the wrong file suffix was removed in YamlHandler

### v0.1.1

* fix config key prepend with resource name

### v0.1.0

* initial version
