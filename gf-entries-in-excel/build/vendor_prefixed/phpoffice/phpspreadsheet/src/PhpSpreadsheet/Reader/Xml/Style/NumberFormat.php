<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xml\Style;

use SimpleXMLElement;

class NumberFormat extends StyleBase
{
    public function parseStyle(SimpleXMLElement $styleAttributes): array
    {
        $style = [];

        $fromFormats = ['\-', '\ '];
        $toFormats = ['-', ' '];

        foreach ($styleAttributes as $styleAttributeKey => $styleAttributeValue) {
            $styleAttributeValue = str_replace($fromFormats, $toFormats, $styleAttributeValue);

            switch ($styleAttributeValue) {
                case 'Short Date':
                    $styleAttributeValue = 'dd/mm/yyyy';

                    break;
            }

            if ($styleAttributeValue > '') {
                $style['numberFormat']['formatCode'] = $styleAttributeValue;
            }
        }

        return $style;
    }
}
