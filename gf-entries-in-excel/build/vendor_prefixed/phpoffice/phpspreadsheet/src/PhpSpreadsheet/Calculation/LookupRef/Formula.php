<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\LookupRef;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Cell;

class Formula
{
    /**
     * FORMULATEXT.
     *
     * @param mixed $cellReference The cell to check
     * @param Cell $pCell The current cell (containing this formula)
     *
     * @return string
     */
    public static function text($cellReference = '', ?Cell $pCell = null)
    {
        if ($pCell === null) {
            return Functions::REF();
        }

        preg_match('/^' . Calculation::CALCULATION_REGEXP_CELLREF . '$/i', $cellReference, $matches);

        $cellReference = $matches[6] . $matches[7];
        $worksheetName = trim($matches[3], "'");
        $worksheet = (!empty($worksheetName))
            ? $pCell->getWorksheet()->getParent()->getSheetByName($worksheetName)
            : $pCell->getWorksheet();

        if (
            $worksheet === null ||
            !$worksheet->cellExists($cellReference) ||
            !$worksheet->getCell($cellReference)->isFormula()
        ) {
            return Functions::NA();
        }

        return $worksheet->getCell($cellReference)->getValue();
    }
}
