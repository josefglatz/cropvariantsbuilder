<?php declare(strict_types = 1);

namespace JosefGlatz\CropVariantsBuilder\Defaults;

class FocusArea
{
    /**
     * Retrieve focus area
     *
     * @param string $name
     * @return array focusArea (no default is returned as there is no focusArea necessary)
     * @throws \UnexpectedValueException
     */
    public static function get(string $name = 'default'): array
    {
        $focusAreas = Configuration::defaultConfiguration('focusAreas');
        if (isset($focusAreas[$name]) && \is_array($focusAreas[$name])) {
            return $focusAreas[$name];
        }
        return [];
    }
}
