# josefglatz/cropvariantsbuilder (TYPO3 Extension `cropvariantsbuilder`)

> TYPO3 extension to simplify writing cropVariants for TYPO3
> Integrators/Developers

> The inital extraction of the functionality from
> `josefglatz/TYPO3-Distribution` into its own extension was supported
> by [supseven](https://www.supseven.at/).

---

1. [About](#about)
   1. [Past](#past)
   2. [Present](#present)
   3. [Future](#future)
2. [Installation](#installation)
   1. [Installation using Composer](#installation-using-composer)
   2. [Installation as extension from TYPO3 Extension Repository (TER)](#installation-as-extension-from-typo3-extension-repository-ter)
3. [Configuration](#configuration)
   1. [Name of the extension where the general configuration file lives](#name-of-the-extension-where-the-general-configuration-file-lives)
   2. [Example of using your own CropVariants.yaml file](#example-of-using-your-own-cropvariantsyaml-file)
   3. [Example of using your own CropVariants.yaml file while using the \TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader->load() imports feature](#example-of-using-your-own-cropvariantsyaml-file-while-using-the-typo3cmscoreconfigurationloaderyamlfileloader-load-imports-feature)
4. [Detailed manual and more examples](#detailed-manual-and-more-examples)
   1. [Handling of cropVariants in TYPO3](Documentation/Markdown/Images/Index.md#handling-of-cropvariants-in-typo3)
      1. [Centralized configuration for AspectRatio, Cover-/CropArea presets and CropVariant definitions](Documentation/Markdown/Images/Index.md#centralized-configuration-for-aspectratio-cover-croparea-presets-and-cropvariant-definitions)
      2. [Handling of cropVariants in TYPO3: Simplified cropVariants configuration for the Table Configuration Array](Documentation/Markdown/Images/Index.md#simplified-cropvariants-configuration-for-the-table-configuration-array)
   2. [Defaults And Presets](Documentation/Markdown/Images/DefaultsAndPresets.md)
   3. [CropVariantsBuilder](Documentation/Markdown/Images/CropVariantsBuilder.md)
      1. [Example 1: Set a global default cropVariants configuration](Documentation/Markdown/Images/CropVariantsBuilder.md#example-1-set-a-global-default-cropvariants-configuration)
      2. [Example 2: Set custom cropVariants for a specific field of a specific table (pages.tx_my_nice_site_extension_nav_image)](Documentation/Markdown/Images/CropVariantsBuilder.md#example-2-set-custom-cropvariants-for-a-specific-field-of-a-specific-table-pagestx_my_nice_site_extension_nav_image)

---

## About

This extensions centralizes the configuration of

* default aspectRatios,
* aspectRatios used within the TYPO3 instance,
* coverAreas used within the TYPO3 instance,
* cropAreas used within the TYPO3 instance,
* focusAreas used within the TYPO3 instance.

This extension makes it easy to configure cropVariants within TCA
(`EXT:your_ext/Configuration/TCA/**/*.php`) modifications in your "site
package" extension.

> ***The extension relies 100% on the TYPO3 core functionality*** and
> can be seen as an on-top-time-saver for TYPO3 integrators.

### Past

The initial public version was part of
`https://github.com/josefglatz/TYPO3-Distribution`. The demands grew in
2019 and therefore I came up with a new idea to support also the TYPO3
site configuration which was introduced in TYPO3 9.5 LTS.

### Present

This extension doesn't support the TYPO3 site configuration. With that
known fact, it's not possible to distinguish between multiple sites in a
multitree environment.

Beside that fact, I want to make
`https://github.com/josefglatz/TYPO3-Distribution` more versatile and
clearer. The interested crowd suggested me to remove the
cropvariantsbuilder feature into an own extension. At the time of
extracting the feature into this extension, the extension officially
supports TYPO3 8.7 LTS and 9.5 LTS. So, here it is, the standalone
cropvariantsbuilder formerly known from Josef Glatz's EXT:theme.

### Future

The functionality of this extension will stay as it is. There will be no
direct successor to which you can update and migrate automatically (from
my current perspective). I will link the new extension here when it's
publicly available.

At the time of writing about the future my plan is to re-adding the
functionality completely via the TYPO3 site configuration and the
FormDataProvider. With that in mind, it's possible to make site specific
configurations. Even the configuration can be done completely via a YAML
file beside your existing YAML file of one configured site.

If it makes really sense, I probably add also the functionality of
`EXT:cropvariantsbuilder` to make global TCA modifications possible (not
yet specified if you have to write YAML or the known PHP syntax).

## Installation

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

The following option has to be set within the TYPO3 extension
configuration:

```
configurationProviderExtension = my_nice_site_extension
```

> *So if you place the configuration file in
> `EXT:my_nice_site_extension/Configuration/ImageManipulation/CropVariants.yaml`
> you have to set the value to `my_nice_site_extension`.*

The following example shows the resulting PHP configuration part:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cropvariantsbuilder'] = serialize([
    'configurationProviderExtension' => 'my_nice_site_extension'
]);

// TYPO3 >= 9.5 LTS:
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['cropvariantsbuilder'] = [
    'configurationProviderExtension' => 'my_nice_site_extension'
];
```

### Example of using your own CropVariants.yaml file

Just clone the file
`EXT:cropvariantsbuilder/Configuration/ImageManipulation/CropVariants.yaml`
to
`EXT:my_nice_site_extension/Configuration/ImageManipulation/CropVariants.yaml`
and modify it however you want. Whith that approach, you have no
dependencies on the default CropVariants.yaml of
`EXT:cropvariantsbuilder`.

### Example of using your own CropVariants.yaml file while using the `\TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader->load()` imports feature

> The following example represents the content of the file
> `EXT:my_nice_site_extension/Configuration/ImageManipulation/CropVariants.yaml`.

```
imageManipulation:
  cropVariants:
    defaults:
      aspectRatios:
        "1.91:1":
          title: "This is the new title for an existing aspectRatio within EXT:cropvariantsbuilder"
        "123:321":
          title: "Completely new introduced aspect ratio"
          value: 123 / 321

      defaultCropVariantsConfiguration:
        default:
          aspectRatios:
            - "3:2"
            - "2:3"
            - "123:321"
            - "NaN"

imports:
  -
    resource: 'EXT:cropvariantsbuilder/Configuration/ImageManipulation/CropVariants.yaml'

```

You can rely on the default CropVariants.yaml of
`EXT:cropvariantsbuilder` while modifying it to meet the demands of your
specific project with the example shown above. And of course, you can
import any other YAML file. You don't have to rely on the default
CropVariants.yaml if the resulting YAML file includes a configuration
for every necessary part.

## Detailed manual and more examples

* [Handling of cropVariants in TYPO3](Documentation/Markdown/Images/Index.md#handling-of-cropvariants-in-typo3)
* [DefaultsAndPresets](Documentation/Markdown/Images/DefaultsAndPresets.md)
* [CropVariantsBuilder Examples](Documentation/Markdown/Images/CropVariantsBuilder.md)

---

## Development

> The ongoing development is done within the master branch!

You can use `composer require-dev
josefglatz/cropvariantsbuilder:dev-master` if you want to test the
current development state.


---

*Cheers to all TYPO3 enthusiasts out there!*

---

## Created by

<http://josefglatz.at/>

## Support

Many thanks to my employer [supseven.at](https://www.supseven.at/) for
sponsoring work time.
