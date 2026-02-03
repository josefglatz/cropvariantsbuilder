<?php declare(strict_types = 1);

namespace JosefGlatz\CropVariantsBuilder;

use JosefGlatz\CropVariantsBuilder\Defaults\CropVariant;
use JosefGlatz\CropVariantsBuilder\Utility\ArrayTool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Builder
{
    /**
     * Default used imageManipulationField name
     */
    public const DEFAULT_IMAGE_MANIPULATION_FIELD = 'crop';

    /**
     * Table with system wide default cropVariants basic settings
     */
    public const DEFAULT_CROP_VARIANTS_TABLE = 'sys_file_reference';

    /**
     * All eligible cropVariants
     *
     * @var array
     */
    protected $cropVariants = [];

    /**
     * CropVariantsBuilder constructor.
     */
    public function __construct(protected string $table, protected string $fieldName, protected string $type = '')
    {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function getInstance(string $table, string $field, string $type = ''): self
    {
        return GeneralUtility::makeInstance(self::class, $table, $field, $type);
    }

    /** Add cropVariant
     *
     * @throws \RuntimeException
     */
    public function addCropVariant(array $cropVariant): self
    {
        foreach ($cropVariant as $key => $item) {
            // Check for existing cropVariant with same name
            if (\array_key_exists($key, $this->cropVariants)) {
                throw new \RuntimeException(
                    'cropVariant "' . $key . '" already exists in the cropVariants configuration
                    for "' . $this->table . '.' . $this->fieldName . '".',
                    1520892669
                );
            }
            // Check if cropVariant has no configuration at all
            if (empty($item)) {
                throw new \UnexpectedValueException(
                    'An empty cropVariant "' . $key . '" can not be added to the cropVariants configuration
                    for "' . $this->table . '.' . $this->fieldName . '".',
                    1520906531
                );
            }
            // Check whether minimum of keys has been defined for cropVariant
            if (!ArrayTool::arrayKeysExists(['title', 'cropArea', 'allowedAspectRatios'], $item)) {
                throw new \UnexpectedValueException(
                    'cropVariant "' . $key . '" without minimum set of configuration can not be added to the cropVariants
                    configuration for "' . $this->table . '.' . $this->fieldName . '".',
                    1520906581
                );
            }
            $this->cropVariants[$key] = $item;
        }

        return $this;
    }

    /**
     * Disable all default cropVariants
     *
     * This is mostly needed to disable any – as default set – cropVariant.
     *
     * For example: if you want to allow only specific cropVariants for a particular field configuration.
     *
     * @return $this
     * @throws \UnexpectedValueException
     */
    public function disableDefaultCropVariants(): self
    {
        $defaultCropVariants = CropVariant::getDefaultCropVariantsNames();
        $cropVariants = $this->cropVariants;

        if ($defaultCropVariants !== []) {
            foreach ($defaultCropVariants as $defaultCropVariant) {
                // remove possible existing cropVariant configuration
                unset($cropVariants[$defaultCropVariant]);
                // configure cropVariant as disabled
                $cropVariants[$defaultCropVariant]['disabled'] = true;
            }
        } else {
            throw new \UnexpectedValueException(
                'Removing default cropVariants not possible. Default cropVariants can\'t be processed.',
                1520488435
            );
        }

        $this->cropVariants = $cropVariants;

        return $this;
    }

    /**
     * Return the final configuration for further processing
     *
     * This is mostly needed if you don't want to use persistToTca() method to allow modifications to the array, which
     * is not covered by the CropVariantsBuilder class.
     *
     * @throws \UnexpectedValueException
     */
    public function get(): array
    {
        if (empty($this->cropVariants)) {
            throw new \UnexpectedValueException(
                'Final cropVariants configuration could not be queried. The property cropVariants contains an empty array.',
                1520861189
            );
        }
        return $this->cropVariants;
    }

    /**
     * Persist the cropVariants configuration for specific a) table, b) column, (possibly) c) type
     * to Table Configuration Array (TCA)
     *
     * @param int|null $customChildType
     * @throws \Exception
     */
    public function persistToTca(bool $force = false, ?int $customChildType = null, string $imageManipulationField = self::DEFAULT_IMAGE_MANIPULATION_FIELD): self
    {
        if (empty($this->cropVariants)) {
            throw new \RuntimeException(
                'Persisting cropVariants configuration not possible. The cropVariants configuration is empty.',
                1520887257
            );
        }

        $config = $this->cropVariants;

        // Unset existing cropVariants configuration
        if ($force) {
            if ($customChildType === null) {
                if ($this->type === '' || $this->type === '0') {
                    unset($GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['columns'][$imageManipulationField]['config']['cropVariants']);
                    $GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['columns'][$imageManipulationField]['config']['cropVariants'] = $config;
                } else {
                    unset($GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants']);
                    $GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
                }
            } elseif ($this->type === '' || $this->type === '0') {
                unset($GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants']);
                $GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
            } else {
                unset($GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants']);
                $GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
            }

            return $this;
        }

        if ($customChildType === null) {
            if ($this->type === '' || $this->type === '0') {
                // no type set, no customChildType
                if (!empty($GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['columns'][$imageManipulationField]['config']['cropVariants'])) {
                    throw new \UnexpectedValueException('cropVariants configuration can not be persisted.
                    cropVariants configuration already exists for ' . $this->table . '.' . $this->fieldName . ($this->type === '' || $this->type === '0' ? '' : ' (type ' . $this->type . ')') . '.', 1520884066);
                }
                $GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['columns'][$imageManipulationField]['config']['cropVariants'] = $config;
            } else {
                // type set, no customChildType
                if (!empty($GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['columns'][$imageManipulationField]['config']['cropVariants'])) {
                    throw new \UnexpectedValueException('cropVariants configuration can not be persisted.
                    cropVariants configuration already exists for ' . $this->table . '.' . $this->fieldName . ($this->type === '' || $this->type === '0' ? '' : ' (type ' . $this->type . ')') . '.', 1520884830);
                }
                $GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['columns'][$imageManipulationField]['config']['cropVariants'] = $config;
            }
        } elseif ($this->type === '' || $this->type === '0') {
            // type set, customChildType set
            if (!empty($GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'])) {
                throw new \UnexpectedValueException('cropVariants configuration can not be persisted.
                    cropVariants configuration already exists for ' . $this->table . '.' . $this->fieldName . ($this->type === '' || $this->type === '0' ? '' : ' (type ' . $this->type . ')') . ' (customChildType ' . (string)$customChildType . '.', 1520885194);
            }
            $GLOBALS['TCA'][$this->table]['columns'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
        } else {
            // no type set, customChildType set
            if (!empty($GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'])) {
                throw new \UnexpectedValueException('cropVariants configuration can not be persisted.
                    cropVariants configuration already exists for ' . $this->table . '.' . $this->fieldName . ($this->type === '' || $this->type === '0' ? '' : ' (type ' . $this->type . ')') . ' (customChildType ' . (string)$customChildType . '.', 1520885283);
            }
            $GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$this->fieldName]['config']['overrideChildTca']['types'][$customChildType]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
        }

        return $this;
    }

    /**
     * Persist the cropVariants configuration for default cropVariants table.
     *
     * @throws \RuntimeException
     */
    public function persistToDefaultTableTca(bool $force = false, string $imageManipulationField = self::DEFAULT_IMAGE_MANIPULATION_FIELD): self
    {
        if (!$this->isTableForDefaultCropVariants()) {
            throw new \RuntimeException('Persisting default cropVariants configuration not possible for a non-default table cropVariants table!
            Please use method persistToTca() instead.', 1520888498);
        }
        if (empty($this->cropVariants)) {
            throw new \RuntimeException('Persisting cropVariants configuration not possible. The cropVariants configuration is empty.', 1520888495);
        }

        $config = $this->cropVariants;

        /**
         * Persist to TCA (force mode)
         */
        if ($force) {
            if ($this->type === '' || $this->type === '0') {
                unset($GLOBALS['TCA'][$this->table]['columns'][$imageManipulationField]['config']['cropVariants']);
                $GLOBALS['TCA'][$this->table]['columns'][$imageManipulationField]['config']['cropVariants'] = $config;
            } else {
                unset($GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$imageManipulationField]['config']['cropVariants']);
                $GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
            }

            return $this;
        }

        /**
         * Persist to TCA (check wether configuration key exists)
         */
        if ($this->type === '' || $this->type === '0') {
            if (!empty($GLOBALS['TCA'][$this->table]['columns'][$imageManipulationField]['config']['cropVariants'])) {
                throw new \RuntimeException('cropVariants configuration can not be persisted.
                    cropVariants configuration already exists for ' . $this->table . '.' . $this->fieldName . '.', 1520890145);
            }
            $GLOBALS['TCA'][$this->table]['columns'][$imageManipulationField]['config']['cropVariants'] = $config;
        } else {
            if (!empty($GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'])) {
                throw new \RuntimeException('cropVariants configuration can not be persisted.
                    cropVariants configuration already exists for ' . $this->table . '.' . $this->fieldName . ' (type: ' . $this->type . ').', 1520890145);
            }
            $GLOBALS['TCA'][$this->table]['types'][$this->type]['columnsOverrides'][$imageManipulationField]['config']['cropVariants'] = $config;
        }

        return $this;
    }

    /**
     * Check table for default cropVariants table
     */
    protected function isTableForDefaultCropVariants(): bool
    {
        return trim($this->table) === self::DEFAULT_CROP_VARIANTS_TABLE;
    }
}
