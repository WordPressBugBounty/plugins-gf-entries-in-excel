<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Exception;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Financial\Constants as FinancialConstants;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Financial\FinancialValidations;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;

class CashFlowValidations extends FinancialValidations
{
    /**
     * @param mixed $rate
     */
    public static function validateRate($rate): float
    {
        $rate = self::validateFloat($rate);

        return $rate;
    }

    /**
     * @param mixed $type
     */
    public static function validatePeriodType($type): int
    {
        $rate = self::validateInt($type);
        if (
            $type !== FinancialConstants::PAYMENT_END_OF_PERIOD &&
            $type !== FinancialConstants::PAYMENT_BEGINNING_OF_PERIOD
        ) {
            throw new Exception(Functions::NAN());
        }

        return $rate;
    }

    /**
     * @param mixed $presentValue
     */
    public static function validatePresentValue($presentValue): float
    {
        return self::validateFloat($presentValue);
    }

    /**
     * @param mixed $futureValue
     */
    public static function validateFutureValue($futureValue): float
    {
        return self::validateFloat($futureValue);
    }
}
