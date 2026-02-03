<?php declare(strict_types = 1);

namespace JosefGlatz\CropVariantsBuilder;

use JosefGlatz\CropVariantsBuilder\Defaults\CropArea;
use JosefGlatz\CropVariantsBuilder\Domain\Model\Dto\EmConfiguration;
use JosefGlatz\CropVariantsBuilder\Utility\ArrayTool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CropVariant
{
    protected EmConfiguration $emConf;

    protected const LLPATH = 'LLL:EXT:%s/Resources/Private/Language/%s.xlf:';
    protected const LLPATHPREFIX = 'crop_variants.';
    protected const LLPATHSUFFIX = '.label';

    /**
     * Name (key)
     */
    protected string $name;

    /**
     * Visible Title (LLL)
     *
     * @var string
     */
    protected $title = '';

    /**
     * cropArea configuration
     */
    protected array $cropArea;

    /**
     * focusArea configuration
     *
     * @var array
     */
    protected $focusArea = [];

    /**
     * coverAreas configuration
     *
     * @var array
     */
    protected $coverAreas = [];

    /**
     * cropVariants configuration
     *
     * @var array
     */
    protected $allowedAspectRatios = [];

    /**
     * selectedRatio
     *
     * @var string
     */
    protected $selectedRatio = '';

    /**
     * CropVariant constructor.
     *  - set provided name
     *  - set title (LLL string based on configuration)
     *  - set default cropArea
     *
     * @param string $name name of this cropVariant
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name)
    {
        $this->emConf = GeneralUtility::makeInstance(EmConfiguration::class);
        $this->name = trim($name);
        $this->setDefaultTitle();
        $this->cropArea = CropArea::get();
    }

    /**
     * Instantiation of class
     *
     * @param string $name name/key for this cropVariant
     * @throws \InvalidArgumentException
     */
    public static function create(string $name): self
    {
        return GeneralUtility::makeInstance(self::class, $name);
    }

    /**
     * Set title
     *
     * Use it only if you don't want to use
     * xlf files for translating or if you
     * want to add custom LLL strings.
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = trim($title);

        return $this;
    }

    /**
     * Set cropArea
     *
     * @return $this
     */
    public function setCropArea(array $cropArea): self
    {
        $this->cropArea = $cropArea;

        return $this;
    }

    /**
     * Set focusArea
     *
     * @return $this
     * @throws \UnexpectedValueException
     */
    public function setFocusArea(array $focusArea): self
    {
        if ($focusArea !== [] && !ArrayTool::arrayKeysExists(['x', 'y', 'width', 'height'], $focusArea)) {
            throw new \UnexpectedValueException(
                'focusArea array for cropVariant "' . $this->name . '" does not have set all necessary keys set.',
                1520894420
            );
        }
        $this->focusArea = $focusArea;

        return $this;
    }

    /**
     * Add coverAreas
     *
     * @return $this
     */
    public function addCoverAreas(array $coverAreas): self
    {
        foreach ($coverAreas as $coverArea) {
            $this->coverAreas[] = $coverArea;
        }

        return $this;
    }

    /**
     * Add allowedAspectRatio(s)
     *
     * @return $this
     * @throws \RuntimeException
     */
    public function addAllowedAspectRatios(array $ratios): self
    {
        foreach ($ratios as $key => $ratio) {
            // Check wether aspectRatio with same name ($key) is already set
            if (\array_key_exists($key, $this->allowedAspectRatios)) {
                throw new \RuntimeException(
                    'allowedAspectRatio "' . $ratio . '" already exists in the configuration.
                        Please remove it with removeAllowedAspectRatio() before adding new with same name.',
                    1520891285
                );
            }
            $this->allowedAspectRatios[$key] = $ratio;
        }
        $this->allowedAspectRatios = $ratios;

        return $this;
    }

    /**
     * Remove an allowedAspectRatio
     *
     * @param string $ratio name of allowed aspect ratio
     * @return $this
     * @throws \UnexpectedValueException
     */
    public function removeAllowedAspectRatio(string $ratio): self
    {
        if (\array_key_exists(trim($ratio), $this->allowedAspectRatios)) {
            unset($this->allowedAspectRatios[$ratio]);
        } else {
            throw new \UnexpectedValueException(
                'Aspect ratio "' . htmlspecialchars(trim($ratio)) . '" for cropVariant "' . $this->name . '" can\'t be removed.
                It isn\'t defined in allowedAspectRatios for this cropVariant.',
                1520854115
            );
        }

        return $this;
    }

    /**
     * Set selectedRatio for cropVariant (optional)
     *
     * @return $this
     * @throws \UnexpectedValueException
     */
    public function setSelectedRatio(string $ratio): self
    {
        if (\array_key_exists(trim($ratio), $this->allowedAspectRatios)) {
            $this->selectedRatio = $ratio;
        } else {
            throw new \UnexpectedValueException(
                'selectedRatio "' . trim($ratio) . '" key does not exists in cropVariants configuration.',
                1520891907
            );
        }

        return $this;
    }

    /**
     * Return final cropVariant configuration
     *  and throw exceptions if some necessary options aren't set
     *
     * @TODO: Only return non emtpy sub-arrays
     * @TODO: Reduce checks by moving them to their classes (still needs introduced)
     *
     * @throws \UnexpectedValueException
     */
    public function get(): array
    {
        // Check if title is set
        if (empty($this->title)) {
            throw new \UnexpectedValueException(
                'Title for cropVariant "' . $this->name . '" not set.',
                1520731261
            );
        }
        // Check if necessary keys are set
        if ($this->cropArea === []) {
            throw new \UnexpectedValueException(
                'cropArea array for cropVariant "' . $this->name . '" not set.',
                1520731402
            );
        }
        if (!ArrayTool::arrayKeysExists(['x', 'y', 'width', 'height'], $this->cropArea)) {
            throw new \UnexpectedValueException(
                'cropArea array for cropVariant "' . $this->name . '" does not have set all necessary keys.',
                1520732819
            );
        }
        if ($this->focusArea !== [] && !ArrayTool::arrayKeysExists(['x', 'y', 'width', 'height'], $this->focusArea)) {
            throw new \UnexpectedValueException(
                'focusArea array for cropVariant "' . $this->name . '" does not have set all necessary keys.',
                1520892162
            );
        }
        foreach ($this->coverAreas as $coverArea) {
            if (!ArrayTool::arrayKeysExists(['x', 'y', 'width', 'height'], $coverArea)) {
                throw new \UnexpectedValueException(
                    'coverAreas array for cropVariant "' . $this->name . '" are not configured correctly. \
                        Not every coverArea is configured correctly.',
                    1520733632
                );
            }
        }
        if (empty($this->allowedAspectRatios)) {
            throw new \UnexpectedValueException(
                'No allowedAspectRatios set for cropVariant "' . $this->name . '". Seems like you forgot to add allowedAspectRatios via addAllowedAspectRatios().',
                1520962836
            );
        }

        return [
            $this->name => [
                'title' => $this->title,
                'cropArea' => $this->cropArea,
                'focusArea' => $this->focusArea ?: null,
                'coverAreas' => $this->coverAreas ?: null,
                'allowedAspectRatios' => $this->allowedAspectRatios,
                'selectedRatio' => $this->selectedRatio,
            ],
        ];
    }

    /**
     * Set the default title
     *
     *  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     *  !!! There is no fallback or magic LLL fallback since version 2.0 !!!
     *  !!! due to upstream changes in the TYPO3 core.                   !!!
     *  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     *
     * @throws \InvalidArgumentException
     */
    protected function setDefaultTitle(): void
    {
        $title = '';
        if ($this->name !== '') {
            $title = $this->setLllString($this->name);
            $this->title = $title;
        }
    }

    /**
     * Generate a LLL string for this cropVariant
     *
     * Based on the extension configuration
     *   by using the shipped locallang.xlf or by
     *   using a custom xlf file in another
     *   extension (e.g. sitepackage ext)
     */
    protected function setLllString(string $name): string
    {
        if ($this->validateConfigurationProviderSettings()) {

            return sprintf(self::LLPATH, $this->emConf->getConfigurationProviderExtension(),
                    $this->emConf->getConfigurationProviderLocallangFilename()) . self::LLPATHPREFIX . trim(htmlspecialchars($name)) . self::LLPATHSUFFIX;
        }

        return sprintf(self::LLPATH, 'cropvariantsbuilder',
                'locallang') . self::LLPATHPREFIX . trim(htmlspecialchars($name)) . self::LLPATHSUFFIX;
    }

    /**
     * Check for necessary configuration
     */
    protected function validateConfigurationProviderSettings(): bool
    {
        $configurationProvider['extension'] = $this->emConf->getConfigurationProviderExtension();
        $configurationProvider['locallangFilename'] = $this->emConf->getConfigurationProviderLocallangFilename();
        return isset($configurationProvider['extension']) && ($configurationProvider['extension'] !== '' && $configurationProvider['extension'] !== '0') && (isset($configurationProvider['locallangFilename']) && ($configurationProvider['locallangFilename'] !== '' && $configurationProvider['locallangFilename'] !== '0'));
    }
}
