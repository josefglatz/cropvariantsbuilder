Back to [Index](Index.md)

---

# CropVariants Builder :construction_worker_man:

**Learn the usage of CropVariants Builder by reading the code
examples!** You get a good overview just by comparing with/-out the
builder:

1. [Example 1: Set a global default cropVariants configuration](#example-1-set-a-global-default-cropvariants-configuration):
   Global/Default cropVariants configuration for TYPO3 instance
2. [Example 2: Set custom cropVariants for a specific field of a specific table (`pages.tx_my_nice_site_extension_nav_image`)](#example-2-set-custom-cropvariants-for-a-specific-field-of-a-specific-table-pagestx_my_nice_site_extension_nav_image):
   Custom cropVariants configuration for a specific field of a specific
   table
3. [Example 3: Set custom cropVariants for `tx_news_domain_model_news.fal_media`](#example-3-set-custom-cropvariants-for-tx_news_domain_model_newsfal_media)


**The CropVariants Builder uses centralized configured
[defaults and presets](DefaultsAndPresets.md):**

1. [aspectRatio presets](DefaultsAndPresets.md#predefined-aspectratios)
2. [coverArea presets](DefaultsAndPresets.md#predefined-coverareas)
3. [Predefined focusAreas](DefaultsAndPresets.md#predefined-focusareas)
4. [cropArea presets](DefaultsAndPresets.md#predefined-cropareas)
5. [List of default cropVariants](DefaultsAndPresets.md#list-of-default-cropvariants)

---

## Example 1: Set a global default cropVariants configuration

The code examples shown in Example 1 can be seen as the content of files
in your own site package extension named `my_nice_site_extension`.

The "default" cropVariants configuration is set as a project default. 6
allowed aspect ratios are configured in this example.

### Before (TYPO3 Core only)

**The downside:**
* All options are set without defaults
* writing the configuration is error-prone (because you have no
  autocompletion)
* the cropArea is set manually (no centralized preset)
* allowed aspect ratios are set manually (no centralized presets)
* manual title option string

`EXT:my_nice_site_extension/Configuration/TCA/Overrides/sys_file_reference.php`:

```php
<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    static function ($extKey, $table) {
        $languageFileBePrefix = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_be.xlf:';

        $tca = [
            'columns' => [
                'crop' => [
                    'config' => [
                        'cropVariants' => [
                            'default' => [
                                'title' => $languageFileBePrefix . 'crop_variants.default.label',
                                'coverAreas' => [],
                                'cropArea' => [
                                    'x' => '0.0',
                                    'y' => '0.0',
                                    'width' => '1.0',
                                    'height' => '1.0'
                                ],
                                'allowedAspectRatios' => [
                                    '3:2' => [
                                        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.3_2',
                                        'value' => 3 / 2
                                    ],
                                    '2:3' => [
                                        'title' => '2:3',
                                        'value' => 2 / 3
                                    ],
                                    '4:3' => [
                                        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3',
                                        'value' => 4 / 3
                                    ],
                                    '3:4' => [
                                        'title' => '3:4',
                                        'value' => 3 / 4
                                    ],
                                    '1:1' => [
                                        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.1_1',
                                        'value' => 1.0
                                    ],
                                    'NaN' => [
                                        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                        'value' => 0.0
                                    ],
                                ],
                                'selectedRatio' => 'NaN'
                            ],
                        ],
                    ],
                ],
            ]
        ];
        $GLOBALS['TCA'][$table] = array_replace_recursive($GLOBALS['TCA'][$table], $tca);
    },
    'my_nice_site_extension',
    'sys_file_reference'
);
```

### Afterwards (with CropVariants Builder)

```yaml
imageManipulation:
  cropVariants:
    defaults:
      #################################################################################
      ### Set default cropVariants for sys_file_reference.columns.crop
      ###
      ###     Each cropVariant must have a minimum of one aspectRatio
      ###     for sys_file_reference.columns.crop
      ###     (Look for "persistToDefaultTableTca")
      ###
      defaultCropVariantsConfiguration:
        default:
          aspectRatios:
            - "3:2"
            - "2:3"
            - "4:3"
            - "3:4"
            - "1:1"
            - "NaN"
```

**If you want to use a custom aspectRatio for the defaults, you just
have to configure it in the same YAML configuration file!**

---

## Example 2: Set custom cropVariants for a specific field of a specific table (`pages.tx_my_nice_site_extension_nav_image`)

`EXT:my_nice_site_extension/Configuration/TCA/Overrides/pages.php`

A common usecase: You add a custom field to the `pages` table and want a
custom cropVariants configuration for this particular field. The TYPO3
editor can add 1 image per page and have to set 3 crops for breakpoint
xs'n'up, md'n'up and lg'n'up. All three with same allowed aspectRatios.

### Before (TYPO3 Core only)

**The downside:**
* All options are set without defaults and aren't configured centralized
* writing the configuration is error-prone (because you have no
  autocompletion)
* cropAreas are always set manually (no centralized preset, no automatic
  fallback to default cropArea)
* e.g. if you add some additional cropVariant for the project as
  default, you have to disable the new default cropVariant here (and in
  every other file)
* allowed aspect ratios are set manually (no centralized presets)
* cropVariant title LLL string isn't automatically fetched from you
  xliff file by convention

```php
<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    static function ($extKey, $table) {
        $languageFileBePrefix = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_BackendGeneral.xlf:';

        $additionalColumns = [
            'tx_my_nice_site_extension_nav_image' => [
                'exclude' => true,
                'label' => $languageFileBePrefix . 'field.pages.tx_my_nice_site_extension_nav_image.label',
                'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('tx_my_nice_site_extension_nav_image', [
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                alternative,title,
                                --linebreak--,crop,
                                --palette--;;filePalette',
                                'columnsOverrides' => [],
                            ],
                        ],
                        'columns' => [
                            'crop' => [
                                'config' => [
                                    'cropVariants' => [
                                        'default' => [
                                            'disabled' => true
                                        ],
                                        'xs' => [
                                            'title' => $languageFileBePrefix . 'crop_variants.xs.label',
                                            'coverAreas' => [],
                                            'cropArea' => [
                                                'x' => '0.0',
                                                'y' => '0.0',
                                                'width' => '1.0',
                                                'height' => '1.0'
                                            ],
                                            'allowedAspectRatios' => [
                                                '4:3' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3',
                                                    'value' => 4 / 3
                                                ],
                                            ],
                                        ],
                                        'md' => [
                                            'title' => $languageFileBePrefix . 'crop_variants.md.label',
                                            'coverAreas' => [],
                                            'cropArea' => [
                                                'x' => '0.0',
                                                'y' => '0.0',
                                                'width' => '1.0',
                                                'height' => '1.0'
                                            ],
                                            'allowedAspectRatios' => [
                                                '4:3' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3',
                                                    'value' => 4 / 3
                                                ],
                                            ],
                                        ],
                                        'lg' => [
                                            'title' => $languageFileBePrefix . 'crop_variants.lg.label',
                                            'coverAreas' => [],
                                            'cropArea' => [
                                                'x' => '0.0',
                                                'y' => '0.0',
                                                'width' => '1.0',
                                                'height' => '1.0'
                                            ],
                                            'allowedAspectRatios' => [
                                                '4:3' => [
                                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3',
                                                    'value' => 4 / 3
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'maxitems' => 1,
                ],
                    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                )
            ],
        ];
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $additionalColumns);
    },
    'my_nice_site_extension',
    'pages'
);
```


### Afterwards (with CropVariants Builder)

**The advantages:**
* add cropVariants configuration after adding the custom TCA column
* Enjoy IDE auto completion
* 32 lines of code less
* easy to read
* add cropVariants with much fewer lines of code
* finally persist the cropVariants configuration with a oneliner
  (`persistToTca()`)
* combine other cropVariants configurations to this code block, so you
  have a good overview

```php
<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    static function ($extKey, $table) {
        $languageFileBePrefix = 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_BackendGeneral.xlf:';

        $additionalColumns = [
            'tx_my_nice_site_extension_nav_image' => [
                'exclude' => true,
                'label' => $languageFileBePrefix . 'field.pages.tx_my_nice_site_extension_nav_image.label',
                'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('tx_my_nice_site_extension_nav_image', [
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                alternative,title,
                                --linebreak--,crop,
                                --palette--;;filePalette',
                                'columnsOverrides' => [],
                            ],
                        ],
                    ],
                    'maxitems' => 1,
                ],
                    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                )
            ],
        ];
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $additionalColumns);

        /**
         * Set cropVariants configuration
         */
        \JosefGlatz\CropVariantsBuilder\Builder::getInstance($table, 'tx_my_nice_site_extension_nav_image')
            ->disableDefaultCropVariants()
            ->addCropVariant(
                \JosefGlatz\CropVariantsBuilder\CropVariant::create('xs')
                    ->addAllowedAspectRatios(\JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio::get(['4:3']))
                    ->get()
            )
            ->addCropVariant(
                \JosefGlatz\CropVariantsBuilder\CropVariant::create('md')
                    ->addAllowedAspectRatios(\JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio::get(['4:3']))
                    ->get()
            )
            ->addCropVariant(
                \JosefGlatz\CropVariantsBuilder\CropVariant::create('lg')
                    ->addAllowedAspectRatios(\JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio::get(['4:3']))
                    ->get()
            )
            ->persistToTca();
    },
    'my_nice_site_extension',
    'pages'
);
```

---

## Example 3: Set custom cropVariants for `tx_news_domain_model_news.fal_media`

`EXT:my_nice_site_extension/Configuration/TCA/Overrides/tx_news_domain_model_news.php`

A common usecase: You want to configure cropVariants for the `fal_media`
column of `EXT:news`. The default cropVariant was removed and two new
cropVariants was added.
The first is called `teaser` with one aspectRatio 3:2. The other one
called `teaser-big-md` with one aspectRatio 16:9.

The two different cropVariants are used for a news list design, where
not every news item has the same aspect ratio.

```php
<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    static function ($extKey, $table) {

        /**
         * Set cropVariants configuration with EXT:cropvariantsbuilder
         */
        \JosefGlatz\CropVariantsBuilder\Builder::getInstance($table, 'fal_media')
            ->disableDefaultCropVariants()
            ->addCropVariant(
                \JosefGlatz\CropVariantsBuilder\CropVariant::create('teaser')
                    ->setCropArea(\JosefGlatz\CropVariantsBuilder\Defaults\CropArea::get())
                    ->addAllowedAspectRatios(\JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio::get(['3:2']))
                    ->get()
            )
            ->addCropVariant(
                \JosefGlatz\CropVariantsBuilder\CropVariant::create('teaser-big-md')
                    ->setCropArea(\JosefGlatz\CropVariantsBuilder\Defaults\CropArea::get())
                    ->addAllowedAspectRatios(\JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio::get(['16:9']))
                    ->get()
            )
            ->persistToTca();
    },
    'my_nice_site_extension',
    'tx_news_domain_model_news'
);
```
