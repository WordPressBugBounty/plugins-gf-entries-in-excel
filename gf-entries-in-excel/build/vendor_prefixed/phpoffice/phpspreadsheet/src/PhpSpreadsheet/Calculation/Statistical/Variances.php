<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Statistical;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;

class Variances extends VarianceBase
{
    /**
     * VAR.
     *
     * Estimates variance based on a sample.
     *
     * Excel Function:
     *        VAR(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     *
     * @return float|string (string if result is an error)
     */
    public static function VAR(...$args)
    {
        $returnValue = Functions::DIV0();

        $summerA = $summerB = 0.0;

        // Loop through arguments
        $aArgs = Functions::flattenArray($args);
        $aCount = 0;
        foreach ($aArgs as $arg) {
            $arg = self::datatypeAdjustmentBooleans($arg);

            // Is it a numeric value?
            if ((is_numeric($arg)) && (!is_string($arg))) {
                $summerA += ($arg * $arg);
                $summerB += $arg;
                ++$aCount;
            }
        }

        if ($aCount > 1) {
            $summerA *= $aCount;
            $summerB *= $summerB;

            return ($summerA - $summerB) / ($aCount * ($aCount - 1));
        }

        return $returnValue;
    }

    /**
     * VARA.
     *
     * Estimates variance based on a sample, including numbers, text, and logical values
     *
     * Excel Function:
     *        VARA(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     *
     * @return float|string (string if result is an error)
     */
    public static function VARA(...$args)
    {
        $returnValue = Functions::DIV0();

        $summerA = $summerB = 0.0;

        // Loop through arguments
        $aArgs = Functions::flattenArrayIndexed($args);
        $aCount = 0;
        foreach ($aArgs as $k => $arg) {
            if ((is_string($arg)) && (Functions::isValue($k))) {
                return Functions::VALUE();
            } elseif ((is_string($arg)) && (!Functions::isMatrixValue($k))) {
            } else {
                // Is it a numeric value?
                if ((is_numeric($arg)) || (is_bool($arg)) || ((is_string($arg) & ($arg != '')))) {
                    $arg = self::datatypeAdjustmentAllowStrings($arg);
                    $summerA += ($arg * $arg);
                    $summerB += $arg;
                    ++$aCount;
                }
            }
        }

        if ($aCount > 1) {
            $summerA *= $aCount;
            $summerB *= $summerB;

            return ($summerA - $summerB) / ($aCount * ($aCount - 1));
        }

        return $returnValue;
    }

    /**
     * VARP.
     *
     * Calculates variance based on the entire population
     *
     * Excel Function:
     *        VARP(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     *
     * @return float|string (string if result is an error)
     */
    public static function VARP(...$args)
    {
        // Return value
        $returnValue = Functions::DIV0();

        $summerA = $summerB = 0.0;

        // Loop through arguments
        $aArgs = Functions::flattenArray($args);
        $aCount = 0;
        foreach ($aArgs as $arg) {
            $arg = self::datatypeAdjustmentBooleans($arg);

            // Is it a numeric value?
            if ((is_numeric($arg)) && (!is_string($arg))) {
                $summerA += ($arg * $arg);
                $summerB += $arg;
                ++$aCount;
            }
        }

        if ($aCount > 0) {
            $summerA *= $aCount;
            $summerB *= $summerB;

            return ($summerA - $summerB) / ($aCount * $aCount);
        }

        return $returnValue;
    }

    /**
     * VARPA.
     *
     * Calculates variance based on the entire population, including numbers, text, and logical values
     *
     * Excel Function:
     *        VARPA(value1[,value2[, ...]])
     *
     * @param mixed ...$args Data values
     *
     * @return float|string (string if result is an error)
     */
    public static function VARPA(...$args)
    {
        $returnValue = Functions::DIV0();

        $summerA = $summerB = 0.0;

        // Loop through arguments
        $aArgs = Functions::flattenArrayIndexed($args);
        $aCount = 0;
        foreach ($aArgs as $k => $arg) {
            if ((is_string($arg)) && (Functions::isValue($k))) {
                return Functions::VALUE();
            } elseif ((is_string($arg)) && (!Functions::isMatrixValue($k))) {
            } else {
                // Is it a numeric value?
                if ((is_numeric($arg)) || (is_bool($arg)) || ((is_string($arg) & ($arg != '')))) {
                    $arg = self::datatypeAdjustmentAllowStrings($arg);
                    $summerA += ($arg * $arg);
                    $summerB += $arg;
                    ++$aCount;
                }
            }
        }

        if ($aCount > 0) {
            $summerA *= $aCount;
            $summerB *= $summerB;

            return ($summerA - $summerB) / ($aCount * $aCount);
        }

        return $returnValue;
    }
}