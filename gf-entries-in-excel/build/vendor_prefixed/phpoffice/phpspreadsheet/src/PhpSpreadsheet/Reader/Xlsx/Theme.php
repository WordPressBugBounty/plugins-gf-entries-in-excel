<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Theme
{
    /**
     * Theme Name.
     *
     * @var string
     */
    private $themeName;

    /**
     * Colour Scheme Name.
     *
     * @var string
     */
    private $colourSchemeName;

    /**
     * Colour Map.
     *
     * @var string[]
     */
    private $colourMap;

    /**
     * Create a new Theme.
     *
     * @param string $themeName
     * @param string $colourSchemeName
     * @param string[] $colourMap
     */
    public function __construct($themeName, $colourSchemeName, $colourMap)
    {
        // Initialise values
        $this->themeName = $themeName;
        $this->colourSchemeName = $colourSchemeName;
        $this->colourMap = $colourMap;
    }

    /**
     * Get Theme Name.
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * Get colour Scheme Name.
     *
     * @return string
     */
    public function getColourSchemeName()
    {
        return $this->colourSchemeName;
    }

    /**
     * Get colour Map Value by Position.
     *
     * @param int $index
     *
     * @return null|string
     */
    public function getColourByIndex($index)
    {
        if (isset($this->colourMap[$index])) {
            return $this->colourMap[$index];
        }

        return null;
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ((is_object($value)) && ($key != '_parent')) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }
}
