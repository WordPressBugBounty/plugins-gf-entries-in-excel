<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\MathTrig;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Exception;

class Exp
{
    /**
     * EXP.
     *
     * Returns the result of builtin function exp after validating args.
     *
     * @param mixed $number Should be numeric
     *
     * @return float|string Rounded number
     */
    public static function evaluate($number)
    {
        try {
            $number = Helpers::validateNumericNullBool($number);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return exp($number);
    }
}
