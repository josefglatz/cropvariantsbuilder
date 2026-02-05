<?php
defined('TYPO3') || die('Access denied.');

use JosefGlatz\CropVariantsBuilder\Builder;
use JosefGlatz\CropVariantsBuilder\CropVariant;
use JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio;
use JosefGlatz\CropVariantsBuilder\Defaults\Configuration;

call_user_func(
    static function ($extKey, string $table): void {

        // Default cropVariants configuration is automatically generated from the selected YAML configuration file
        $defaults = Configuration::defaultConfiguration('defaultCropVariantsConfiguration');

        // Overwrite the TYPO3 core default cropVariant configuration
        // if the necessary configuration has at least one aspect ratio specified
        if ($defaults !== []) {
            $defaultCrop = Builder::getInstance($table, 'crop');
            foreach ($defaults as $key => $config) {
                $defaultCrop = $defaultCrop->addCropVariant(
                    CropVariant::create($key)
                        ->addAllowedAspectRatios(AspectRatio::get($config['aspectRatios']))
                        ->get()
                );
            }
            $defaultCrop->persistToDefaultTableTca();
        }
    },
    'cropvariantsbuilder',
    'sys_file_reference'
);
