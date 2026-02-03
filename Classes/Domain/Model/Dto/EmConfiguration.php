<?php
declare(strict_types = 1);

namespace JosefGlatz\CropVariantsBuilder\Domain\Model\Dto;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmConfiguration implements SingletonInterface
{
    public function __construct()
    {
        $settings = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cropvariantsbuilder');

        foreach ($settings as $key => $value) {
            if (property_exists(self::class, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @var string;
     */
    protected $configurationProviderExtension = '';

    /**
     * @var string
     */
    protected $configurationProviderLocallangFilename = 'locallang';

    public function getConfigurationProviderExtension(): string
    {
        return trim((string) $this->configurationProviderExtension);
    }

    public function getConfigurationProviderLocallangFilename(): string
    {
        return trim($this->configurationProviderLocallangFilename);
    }
}
