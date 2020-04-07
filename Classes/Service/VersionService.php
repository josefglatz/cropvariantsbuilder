<?php
declare(strict_types = 1);

namespace JosefGlatz\CropVariantsBuilder\Service;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class VersionService
{
    public static function isVersion8(): bool
    {
        $constraintVersionMax = 8999999;
        $constraintVersionMin = 8000000;

        return static::evaluateCondition($constraintVersionMin, $constraintVersionMax);

    }
    public static function isVersion9(): bool
    {
        $constraintVersionMax = 9999999;
        $constraintVersionMin = 9000000;

        return static::evaluateCondition($constraintVersionMin, $constraintVersionMax);

    }

    public static function isVersion10(): bool
    {
        $constraintVersionMax = 10999999;
        $constraintVersionMin = 10000000;

        return static::evaluateCondition($constraintVersionMin, $constraintVersionMax);

    }

    /**
     * @param int $min
     * @param int $max
     * @return bool
     */
    protected static function evaluateCondition(int $min, int $max): bool
    {
        $typo3Version = self::getTypo3Version();
        return VersionNumberUtility::convertVersionNumberToInteger($typo3Version) <= $max
            && VersionNumberUtility::convertVersionNumberToInteger($typo3Version) >= $min;
    }

    protected static function getTypo3Version(): string
    {
        if (class_exists(\TYPO3\CMS\Core\Information\Typo3Version::class)) {
            return GeneralUtility::makeInstance(Typo3Version::class)->getVersion();
        }

        return TYPO3_version;
    }
}
