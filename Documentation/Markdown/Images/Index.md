Back to [Index](Index.md)

---

# Handling of cropVariants in TYPO3

This TYPO3 extension tries to help you as much with configuring
cropVariants in different situations:

1. [Centralized configuration for AspectRatio, Cover-/CropArea presets and CropVariant definitions](#centralized-configuration-for-aspectratio-cover-croparea-presets-and-cropvariant-definitions)
2. [Simplified cropVariants configuration for the Table Configuration Array](#simplified-cropvariants-configuration-for-the-table-configuration-array)
   (custom CropVariantsBuilder)
   1. [Custom CropVariants Builder details](CropVariantsBuilder.md)

---

## Centralized configuration for AspectRatio, Cover-/CropArea presets and CropVariant definitions

Each of them are configured in a YAML configuration file. If you don't
configure any other configuration provider extension or the file in the
given extension doesn't exists, the shipped YAML configuration file
[CropVariants.yaml](../../../Configuration/ImageManipulation/CropVariants.yaml)
is loaded automatically.

[**Overview of defaults and predefined presets*Predefined aspectRatiosts.md)

1. [Predefined aspectRatios](DefaultsAndPresets.md#predefined-aspectratios)
2. [Predefined coverAreas](DefaultsAndPresets.md#predefined-coverareas)
3. [Predefined cropAreas](DefaultsAndPresets.md#predefined-cropareas)
4. [List of default cropVariants](DefaultsAndPresets.md)


---

## Simplified cropVariants configuration for the Table Configuration Array

TYPO3 has some really powerful features to allow TYPO3 backend editors
to crop images. You actually have the following possibilities to
configure cropVariants for a field in following contexts (far as I know
and I've use in real world TYPO3 projects):

### Primary usage scenarios

1. Set a *global/default* cropVariants configuration (which is used if
   you do not make a more specific configuration)
2. Set a cropVariants configuration for a *specific field of a specific
   table* (where you optionally can disable the default/global
   cropVariants configuration)
3. Set a cropVariants configuration for a *specific field of a specific
   type of a table* (where you optionally can disable the default/global
   cropVariants configuration)

**I know that may seem confusing - but once you understand, how the
options are working, it makes sense for real world scenarios.**

### Additional usage scenarios

1. Set a cropVariants configuration for a *specific field childTca's
   type of a specific table* (where you optionally can disable (parts
   of) the default/global cropVariants configuration) - as used in IRRE
   elements for example.
2. Set a cropVariants configuration for a *specific field childTca's
   type of a specific type of a table* (where you optionally can disable
   (parts of) the default/global cropVariants configuration) - as used
   in IRRE elements for example.

### And what exactly simplifies this TYPO3 extension?

a) You can set up default/s
* for aspectRatios
* for coverArea
* for cropAreas
* list of default cropVariants

b) [A custom CropVariants Builder](CropVariantsBuilder.md) helps you
writing cropVariants configurations based on mentioned defaults with IDE
auto completion support.

