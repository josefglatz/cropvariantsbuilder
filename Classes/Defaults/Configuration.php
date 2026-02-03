<?php declare(strict_types=1);

namespace JosefGlatz\CropVariantsBuilder\Defaults;

use JosefGlatz\CropVariantsBuilder\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Configuration\Loader\YamlFileLoader;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Configuration
{
    /**
     * Relative path to configuration file
     */
    public const CONFIGFILE = '/Configuration/ImageManipulation/CropVariants.yaml';

    /**
     * Static runtime cache for yaml file content
     */
    private static ?array $cache =  null;

    /**
     * Returns the processed configuration array from the YAML configuration file
     *
     * @param string $key specific configuration key
     */
    public static function defaultConfiguration(string $key = ''): array
    {
        // Initiate Classes
        $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(self::class);

        // If cache is empty load configuration from yaml file
        if (($configuration = self::$cache) === null) {
            // Get extension name and path from where the custom CropVariants.yaml file should be loaded
            $configFilePath = self::getActiveConfigurationFilePath();

            // Try to load the custom CropVariants.yaml and log errors just in case
            try {
                $configuration = self::loadYamlFile($configFilePath);
            } catch (\UnexpectedValueException $e) {
                // @extensionScannerIgnoreLine
                $logger->error(sprintf('The CONFIGFILE "%s" could not be parsed.', $configFilePath), [$e->getMessage()]);
            } catch (\RuntimeException $e) {
                // @extensionScannerIgnoreLine
                $logger->error(sprintf('The CONFIGFILE "%s" could not be found.', $configFilePath), [$e->getMessage()]);
            }
            if (!isset($configuration)) {
                $configuration = self::loadYamlFile('EXT:cropvariantsbuilder' . self::CONFIGFILE);
            }
            // Write configuration to cache
            self::$cache = $configuration;
        }

        $defaults = $configuration['imageManipulation']['cropVariants']['defaults'];
        // Check if configuration array path exists
        if (!\is_array($defaults)) {
            throw new \UnexpectedValueException(
                'The imageManipulation configuration can\'t be retrieved from the configuration file. (Please take a look at ' . self::CONFIGFILE . ')',
                1524832974
            );
        }

        // Return the whole default configuration if key argument is empty
        if ($key === '' || $key === '0') {
            return $defaults;
        }

        // Check if requested key is set in the configuration
        if (!isset($defaults[trim($key)])) {
            throw new \UnexpectedValueException(
                'Requested key was not found. Is something missing in your configuration file? (Please take a look at ' . self::CONFIGFILE . ')',
                1524835641
            );
        }

        // Check if requested key is set in the configuration
        if (empty($defaults[trim($key)])) {
            throw new \UnexpectedValueException(
                'Requested key doesn\'t contain any children.  (Please take a look at ' . self::CONFIGFILE . ')',
                1524835441
            );
        }

        return $defaults[trim($key)];
    }

    public static function loadYamlFile(string $path): ?array
    {
        $fileLoader = GeneralUtility::makeInstance(YamlFileLoader::class);

        return $fileLoader->load($path, YamlFileLoader::PROCESS_IMPORTS);
    }

    public static function getActiveConfigurationFilePath(): string
    {
        $configurationProviderExtension = GeneralUtility::makeInstance(EmConfiguration::class)
                                                        ->getConfigurationProviderExtension();

        return 'EXT:' . $configurationProviderExtension . self::CONFIGFILE;
    }
}
