<?php
declare(strict_types = 1);

namespace JosefGlatz\CropVariantsBuilder\Domain\Model\Dto;

use JosefGlatz\CropVariantsBuilder\Service\VersionService;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EmConfiguration implements SingletonInterface
{
    public function __construct()
    {
        $settings = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cropvariantsbuilder');

        foreach ($settings as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
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

    /**
     * @return string
     */
    public function getConfigurationProviderExtension(): string
    {
        return trim($this->configurationProviderExtension);
    }

    /**
     * @return string
     */
    public function getConfigurationProviderLocallangFilename(): string
    {
        return trim($this->configurationProviderLocallangFilename);
    }
}
