<?php
defined('TYPO3') || die('Access denied.');

use JosefGlatz\CropVariantsBuilder\Builder;
use JosefGlatz\CropVariantsBuilder\CropVariant;
use JosefGlatz\CropVariantsBuilder\Defaults\AspectRatio;
use JosefGlatz\CropVariantsBuilder\Defaults\Configuration;

call_user_func(
    static function ($extKey, $table) {

        // Default cropVariants configuration is automatically generated from the selected YAML configuration file
        $defaults = Configuration::defaultConfiguration('defaultCropVariantsConfiguration');

        // Array must be at least available
        if (!\is_array($defaults)) {
            throw new \UnexpectedValueException(
                'The defaultCropVariantsConfiguration configuration can\'t be retrieved from the configuration file. (Please check manually if your active EXT:cropvariantsbuilder configuration file is not empty and the "defaultCropVariantsConfiguration" section is not missing and filled with available aspectRatios. ( ' . Configuration::getActiveConfigurationFilePath() . ' )',
                1524948477
            );
        }

        // Overwrite the TYPO3 core default cropVariant configuration
        // if the necessary configuration has at least one aspect ratio specified
        if (!empty($defaults)) {
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
