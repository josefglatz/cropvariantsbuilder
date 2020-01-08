# josefglatz/cropvariantsbuilder (TYPO3 Extension `cropvariantsbuilder`)

> TYPO3 extension to simplify writing cropVariants for TYPO3
> Integrators/Developers

> The inital extraction of the functionality from
> `josefglatz/TYPO3-Distribution` into its own extension was supported
> by [supseven](https://www.supseven.at/).

## About

### Past

The initial public version was part of
`https://github.com/josefglatz/TYPO3-Distribution`. The demands grew in
2019 and therefore I came up with a new idea how to support also the
TYPO3 site configuration which was introduced in TYPO3 9.5 LTS.

### Future

The functionality of this extension will stay as it is. There will be no
direct successor to which you can update and migrate automatically (from
my current perspective). I will link the new extension here when it's
publicly available.

## Installation and Configuration

### Installation using Composer

The recommended way to install the extension is by using
[Composer](https://getcomposer.org/). In your Composer based TYPO3
project root, just do `composer require josefglatz/cropvariantsbuilder`.

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 CMS extension manager
module or directly via
[typo3.org](https://typo3.org/extensions/repository/view/cropvariantsbuilder).

## Configuration

### Name of the extension where the general configuration file lives

The following option has to be set within the TYPO3 extension configuration:

```
configurationProviderExtension = name_of_the_extension_where_you_place_yaml_config_file
```

> *So if you place the configuration file in `EXT:my_nice_site/Configuration/ImageManipulation/CropVariants.yaml` you have to set the value to `my_nice_site`.*

---

## Development

> The ongoing development is done within the master branch!

You can use `composer require-dev
josefglatz/cropvariantsbuilder:dev-master` if you want to test the
current development state.


---

Cheers to all TYPO3 enthusiasts out there!

---


## Created by

http://josefglatz.at/

## Support

Many thanks to my employer [supseven.at](https://www.supseven.at/) for
sponsoring work time.
