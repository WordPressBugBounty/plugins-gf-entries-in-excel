<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel;

use DateTimeImmutable;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\Date as SharedDateHelper;

class DateValue
{
    /**
     * DATEVALUE.
     *
     * Returns a value that represents a particular date.
     * Use DATEVALUE to convert a date represented by a text string to an Excel or PHP date/time stamp
     * value.
     *
     * NOTE: When used in a Cell Formula, MS Excel changes the cell format so that it matches the date
     * format of your regional settings. PhpSpreadsheet does not change cell formatting in this way.
     *
     * Excel Function:
     *        DATEVALUE(dateValue)
     *
     * @param string $dateValue Text that represents a date in a Microsoft Excel date format.
     *                                    For example, "1/30/2008" or "30-Jan-2008" are text strings within
     *                                    quotation marks that represent dates. Using the default date
     *                                    system in Excel for Windows, date_text must represent a date from
     *                                    January 1, 1900, to December 31, 9999. Using the default date
     *                                    system in Excel for the Macintosh, date_text must represent a date
     *                                    from January 1, 1904, to December 31, 9999. DATEVALUE returns the
     *                                    #VALUE! error value if date_text is out of this range.
     *
     * @return mixed Excel date/time serial value, PHP date/time serial value or PHP date/time object,
     *                        depending on the value of the ReturnDateType flag
     */
    public static function fromString($dateValue)
    {
        $dti = new DateTimeImmutable();
        $baseYear = SharedDateHelper::getExcelCalendar();
        $dateValue = trim(Functions::flattenSingleValue($dateValue ?? ''), '"');
        //    Strip any ordinals because they're allowed in Excel (English only)
        $dateValue = preg_replace('/(\d)(st|nd|rd|th)([ -\/])/Ui', '$1$3', $dateValue) ?? '';
        //    Convert separators (/ . or space) to hyphens (should also handle dot used for ordinals in some countries, e.g. Denmark, Germany)
        $dateValue = str_replace(['/', '.', '-', '  '], ' ', $dateValue);

        $yearFound = false;
        $t1 = explode(' ', $dateValue);
        $t = '';
        foreach ($t1 as &$t) {
            if ((is_numeric($t)) && ($t > 31)) {
                if ($yearFound) {
                    return Functions::VALUE();
                }
                if ($t < 100) {
                    $t += 1900;
                }
                $yearFound = true;
            }
        }
        if (count($t1) === 1) {
            //    We've been fed a time value without any date
            return ((strpos((string) $t, ':') === false)) ? Functions::Value() : 0.0;
        }
        unset($t);

        $dateValue = self::t1ToString($t1, $dti, $yearFound);

        $PHPDateArray = self::setUpArray($dateValue, $dti);

        return self::finalResults($PHPDateArray, $dti, $baseYear);
    }

    private static function t1ToString(array $t1, DateTimeImmutable $dti, bool $yearFound): string
    {
        if (count($t1) == 2) {
            //    We only have two parts of the date: either day/month or month/year
            if ($yearFound) {
                array_unshift($t1, 1);
            } else {
                if (is_numeric($t1[1]) && $t1[1] > 29) {
                    $t1[1] += 1900;
                    array_unshift($t1, 1);
                } else {
                    $t1[] = $dti->format('Y');
                }
            }
        }
        $dateValue = implode(' ', $t1);

        return $dateValue;
    }

    /**
     * Parse date.
     *
     * @return array|bool
     */
    private static function setUpArray(string $dateValue, DateTimeImmutable $dti)
    {
        $PHPDateArray = date_parse($dateValue);
        if (($PHPDateArray === false) || ($PHPDateArray['error_count'] > 0)) {
            // If original count was 1, we've already returned.
            // If it was 2, we added another.
            // Therefore, neither of the first 2 stroks below can fail.
            $testVal1 = strtok($dateValue, '- ');
            $testVal2 = strtok('- ');
            $testVal3 = strtok('- ') ?: $dti->format('Y');
            Helpers::adjustYear((string) $testVal1, (string) $testVal2, $testVal3);
            $PHPDateArray = date_parse($testVal1 . '-' . $testVal2 . '-' . $testVal3);
            if (($PHPDateArray === false) || ($PHPDateArray['error_count'] > 0)) {
                $PHPDateArray = date_parse($testVal2 . '-' . $testVal1 . '-' . $testVal3);
            }
        }

        return $PHPDateArray;
    }

    /**
     * Final results.
     *
     * @param array|bool $PHPDateArray
     *
     * @return mixed Excel date/time serial value, PHP date/time serial value or PHP date/time object,
     *                        depending on the value of the ReturnDateType flag
     */
    private static function finalResults($PHPDateArray, DateTimeImmutable $dti, int $baseYear)
    {
        $retValue = Functions::Value();
        if (is_array($PHPDateArray) && $PHPDateArray['error_count'] == 0) {
            // Execute function
            Helpers::replaceIfEmpty($PHPDateArray['year'], $dti->format('Y'));
            if ($PHPDateArray['year'] < $baseYear) {
                return Functions::VALUE();
            }
            Helpers::replaceIfEmpty($PHPDateArray['month'], $dti->format('m'));
            Helpers::replaceIfEmpty($PHPDateArray['day'], $dti->format('d'));
            $PHPDateArray['hour'] = 0;
            $PHPDateArray['minute'] = 0;
            $PHPDateArray['second'] = 0;
            $month = (int) $PHPDateArray['month'];
            $day = (int) $PHPDateArray['day'];
            $year = (int) $PHPDateArray['year'];
            if (!checkdate($month, $day, $year)) {
                return ($year === 1900 && $month === 2 && $day === 29) ? Helpers::returnIn3FormatsFloat(60.0) : Functions::VALUE();
            }
            $retValue = Helpers::returnIn3FormatsArray($PHPDateArray, true);
        }

        return $retValue;
    }
}
