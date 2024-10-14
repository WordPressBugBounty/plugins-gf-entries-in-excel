<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared;

class IntOrFloat
{
    /**
     * Help some functions with large results operate correctly on 32-bit,
     * by returning result as int when possible, float otherwise.
     *
     * @param float|int $value
     *
     * @return float|int
     */
    public static function evaluate($value)
    {
        $iValue = (int) $value;

        return ($value == $iValue) ? $iValue : $value;
    }
}
