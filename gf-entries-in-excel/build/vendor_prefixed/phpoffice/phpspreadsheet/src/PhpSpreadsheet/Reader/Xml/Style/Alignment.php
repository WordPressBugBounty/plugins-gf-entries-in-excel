<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xml\Style;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style\Alignment as AlignmentStyles;
use SimpleXMLElement;

class Alignment extends StyleBase
{
    protected const VERTICAL_ALIGNMENT_STYLES = [
        AlignmentStyles::VERTICAL_BOTTOM,
        AlignmentStyles::VERTICAL_TOP,
        AlignmentStyles::VERTICAL_CENTER,
        AlignmentStyles::VERTICAL_JUSTIFY,
    ];

    protected const HORIZONTAL_ALIGNMENT_STYLES = [
        AlignmentStyles::HORIZONTAL_GENERAL,
        AlignmentStyles::HORIZONTAL_LEFT,
        AlignmentStyles::HORIZONTAL_RIGHT,
        AlignmentStyles::HORIZONTAL_CENTER,
        AlignmentStyles::HORIZONTAL_CENTER_CONTINUOUS,
        AlignmentStyles::HORIZONTAL_JUSTIFY,
    ];

    public function parseStyle(SimpleXMLElement $styleAttributes): array
    {
        $style = [];

        foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
            $styleAttributeValue = (string) $styleAttributeValue;
            switch ($styleAttributeKey) {
                case 'Vertical':
                    if (self::identifyFixedStyleValue(self::VERTICAL_ALIGNMENT_STYLES, $styleAttributeValue)) {
                        $style['alignment']['vertical'] = $styleAttributeValue;
                    }

                    break;
                case 'Horizontal':
                    if (self::identifyFixedStyleValue(self::HORIZONTAL_ALIGNMENT_STYLES, $styleAttributeValue)) {
                        $style['alignment']['horizontal'] = $styleAttributeValue;
                    }

                    break;
                case 'WrapText':
                    $style['alignment']['wrapText'] = true;

                    break;
                case 'Rotate':
                    $style['alignment']['textRotation'] = $styleAttributeValue;

                    break;
            }
        }

        return $style;
    }
}