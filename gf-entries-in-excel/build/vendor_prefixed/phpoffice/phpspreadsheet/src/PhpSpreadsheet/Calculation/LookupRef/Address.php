<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\LookupRef;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Address
{
    public const ADDRESS_ABSOLUTE = 1;
    public const ADDRESS_COLUMN_RELATIVE = 2;
    public const ADDRESS_ROW_RELATIVE = 3;
    public const ADDRESS_RELATIVE = 4;

    public const REFERENCE_STYLE_A1 = true;
    public const REFERENCE_STYLE_R1C1 = false;

    /**
     * ADDRESS.
     *
     * Creates a cell address as text, given specified row and column numbers.
     *
     * Excel Function:
     *        =ADDRESS(row, column, [relativity], [referenceStyle], [sheetText])
     *
     * @param mixed $row Row number (integer) to use in the cell reference
     * @param mixed $column Column number (integer) to use in the cell reference
     * @param mixed $relativity Integer flag indicating the type of reference to return
     *                             1 or omitted    Absolute
     *                             2               Absolute row; relative column
     *                             3               Relative row; absolute column
     *                             4               Relative
     * @param mixed $referenceStyle A logical (boolean) value that specifies the A1 or R1C1 reference style.
     *                                TRUE or omitted    ADDRESS returns an A1-style reference
     *                                FALSE              ADDRESS returns an R1C1-style reference
     * @param mixed $sheetName Optional Name of worksheet to use
     *
     * @return string
     */
    public static function cell($row, $column, $relativity = 1, $referenceStyle = true, $sheetName = '')
    {
        $row = Functions::flattenSingleValue($row);
        $column = Functions::flattenSingleValue($column);
        $relativity = ($relativity === null) ? 1 : Functions::flattenSingleValue($relativity);
        $referenceStyle = ($referenceStyle === null) ? true : Functions::flattenSingleValue($referenceStyle);
        $sheetName = Functions::flattenSingleValue($sheetName);

        if (($row < 1) || ($column < 1)) {
            return Functions::VALUE();
        }

        $sheetName = self::sheetName($sheetName);

        if ((!is_bool($referenceStyle)) || $referenceStyle === self::REFERENCE_STYLE_A1) {
            return self::formatAsA1($row, $column, $relativity, $sheetName);
        }

        return self::formatAsR1C1($row, $column, $relativity, $sheetName);
    }

    private static function sheetName(string $sheetName)
    {
        if ($sheetName > '') {
            if (strpos($sheetName, ' ') !== false || strpos($sheetName, '[') !== false) {
                $sheetName = "'{$sheetName}'";
            }
            $sheetName .= '!';
        }

        return $sheetName;
    }

    private static function formatAsA1(int $row, int $column, int $relativity, string $sheetName): string
    {
        $rowRelative = $columnRelative = '$';
        if (($relativity == self::ADDRESS_COLUMN_RELATIVE) || ($relativity == self::ADDRESS_RELATIVE)) {
            $columnRelative = '';
        }
        if (($relativity == self::ADDRESS_ROW_RELATIVE) || ($relativity == self::ADDRESS_RELATIVE)) {
            $rowRelative = '';
        }
        $column = Coordinate::stringFromColumnIndex($column);

        return "{$sheetName}{$columnRelative}{$column}{$rowRelative}{$row}";
    }

    private static function formatAsR1C1(int $row, int $column, int $relativity, string $sheetName): string
    {
        if (($relativity == self::ADDRESS_COLUMN_RELATIVE) || ($relativity == self::ADDRESS_RELATIVE)) {
            $column = "[{$column}]";
        }
        if (($relativity == self::ADDRESS_ROW_RELATIVE) || ($relativity == self::ADDRESS_RELATIVE)) {
            $row = "[{$row}]";
        }

        return "{$sheetName}R{$row}C{$column}";
    }
}
