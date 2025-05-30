<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Statistical;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Database\DAverage;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Database\DCount;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Database\DMax;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Database\DMin;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Database\DSum;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;

class Conditional
{
    private const CONDITION_COLUMN_NAME = 'CONDITION';
    private const VALUE_COLUMN_NAME = 'VALUE';
    private const CONDITIONAL_COLUMN_NAME = 'CONDITIONAL %d';

    /**
     * AVERAGEIF.
     *
     * Returns the average value from a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        AVERAGEIF(range,condition[, average_range])
     *
     * @param mixed[] $range Data values
     * @param string $condition the criteria that defines which cells will be checked
     * @param mixed[] $averageRange Data values
     *
     * @return null|float|string
     */
    public static function AVERAGEIF($range, $condition, $averageRange = [])
    {
        $database = self::databaseFromRangeAndValue($range, $averageRange);
        $condition = [[self::CONDITION_COLUMN_NAME, self::VALUE_COLUMN_NAME], [$condition, null]];

        return DAverage::evaluate($database, self::VALUE_COLUMN_NAME, $condition);
    }

    /**
     * AVERAGEIFS.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        AVERAGEIFS(average_range, criteria_range1, criteria1, [criteria_range2, criteria2]…)
     *
     * @param mixed $args Pairs of Ranges and Criteria
     *
     * @return null|float|string
     */
    public static function AVERAGEIFS(...$args)
    {
        if (empty($args)) {
            return 0.0;
        } elseif (count($args) === 3) {
            return self::AVERAGEIF($args[1], $args[2], $args[0]);
        }

        $conditions = self::buildConditionSetForValueRange(...$args);
        $database = self::buildDatabaseWithValueRange(...$args);

        return DAverage::evaluate($database, self::VALUE_COLUMN_NAME, $conditions);
    }

    /**
     * COUNTIF.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNTIF(range,condition)
     *
     * @param mixed[] $range Data values
     * @param string $condition the criteria that defines which cells will be counted
     *
     * @return int
     */
    public static function COUNTIF($range, $condition)
    {
        // Filter out any empty values that shouldn't be included in a COUNT
        $range = array_filter(
            Functions::flattenArray($range),
            function ($value) {
                return $value !== null && $value !== '';
            }
        );

        $range = array_merge([[self::CONDITION_COLUMN_NAME]], array_chunk($range, 1));
        $condition = array_merge([[self::CONDITION_COLUMN_NAME]], [[$condition]]);

        return DCount::evaluate($range, null, $condition);
    }

    /**
     * COUNTIFS.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        COUNTIFS(criteria_range1, criteria1, [criteria_range2, criteria2]…)
     *
     * @param mixed $args Pairs of Ranges and Criteria
     *
     * @return int
     */
    public static function COUNTIFS(...$args)
    {
        if (empty($args)) {
            return 0;
        } elseif (count($args) === 2) {
            return self::COUNTIF(...$args);
        }

        $database = self::buildDatabase(...$args);
        $conditions = self::buildConditionSet(...$args);

        return DCount::evaluate($database, null, $conditions);
    }

    /**
     * MAXIFS.
     *
     * Returns the maximum value within a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        MAXIFS(max_range, criteria_range1, criteria1, [criteria_range2, criteria2]…)
     *
     * @param mixed $args Pairs of Ranges and Criteria
     *
     * @return null|float|string
     */
    public static function MAXIFS(...$args)
    {
        if (empty($args)) {
            return 0.0;
        }

        $conditions = self::buildConditionSetForValueRange(...$args);
        $database = self::buildDatabaseWithValueRange(...$args);

        return DMax::evaluate($database, self::VALUE_COLUMN_NAME, $conditions);
    }

    /**
     * MINIFS.
     *
     * Returns the minimum value within a range of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        MINIFS(min_range, criteria_range1, criteria1, [criteria_range2, criteria2]…)
     *
     * @param mixed $args Pairs of Ranges and Criteria
     *
     * @return null|float|string
     */
    public static function MINIFS(...$args)
    {
        if (empty($args)) {
            return 0.0;
        }

        $conditions = self::buildConditionSetForValueRange(...$args);
        $database = self::buildDatabaseWithValueRange(...$args);

        return DMin::evaluate($database, self::VALUE_COLUMN_NAME, $conditions);
    }

    /**
     * SUMIF.
     *
     * Totals the values of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        SUMIF(range, criteria, [sum_range])
     *
     * @param mixed $range Data values
     * @param mixed $sumRange
     * @param mixed $condition
     *
     * @return float|string
     */
    public static function SUMIF($range, $condition, $sumRange = [])
    {
        $database = self::databaseFromRangeAndValue($range, $sumRange);
        $condition = [[self::CONDITION_COLUMN_NAME, self::VALUE_COLUMN_NAME], [$condition, null]];

        return DSum::evaluate($database, self::VALUE_COLUMN_NAME, $condition);
    }

    /**
     * SUMIFS.
     *
     * Counts the number of cells that contain numbers within the list of arguments
     *
     * Excel Function:
     *        SUMIFS(average_range, criteria_range1, criteria1, [criteria_range2, criteria2]…)
     *
     * @param mixed $args Pairs of Ranges and Criteria
     *
     * @return null|float|string
     */
    public static function SUMIFS(...$args)
    {
        if (empty($args)) {
            return 0.0;
        } elseif (count($args) === 3) {
            return self::SUMIF($args[1], $args[2], $args[0]);
        }

        $conditions = self::buildConditionSetForValueRange(...$args);
        $database = self::buildDatabaseWithValueRange(...$args);

        return DSum::evaluate($database, self::VALUE_COLUMN_NAME, $conditions);
    }

    private static function buildConditionSet(...$args): array
    {
        $conditions = self::buildConditions(1, ...$args);

        return array_map(null, ...$conditions);
    }

    private static function buildConditionSetForValueRange(...$args): array
    {
        $conditions = self::buildConditions(2, ...$args);

        if (count($conditions) === 1) {
            return array_map(
                function ($value) {
                    return [$value];
                },
                $conditions[0]
            );
        }

        return array_map(null, ...$conditions);
    }

    private static function buildConditions(int $startOffset, ...$args): array
    {
        $conditions = [];

        $pairCount = 1;
        $argumentCount = count($args);
        for ($argument = $startOffset; $argument < $argumentCount; $argument += 2) {
            $conditions[] = array_merge([sprintf(self::CONDITIONAL_COLUMN_NAME, $pairCount)], [$args[$argument]]);
            ++$pairCount;
        }

        return $conditions;
    }

    private static function buildDatabase(...$args): array
    {
        $database = [];

        return self::buildDataSet(0, $database, ...$args);
    }

    private static function buildDatabaseWithValueRange(...$args): array
    {
        $database = [];
        $database[] = array_merge(
            [self::VALUE_COLUMN_NAME],
            Functions::flattenArray($args[0])
        );

        return self::buildDataSet(1, $database, ...$args);
    }

    private static function buildDataSet(int $startOffset, array $database, ...$args): array
    {
        $pairCount = 1;
        $argumentCount = count($args);
        for ($argument = $startOffset; $argument < $argumentCount; $argument += 2) {
            $database[] = array_merge(
                [sprintf(self::CONDITIONAL_COLUMN_NAME, $pairCount)],
                Functions::flattenArray($args[$argument])
            );
            ++$pairCount;
        }

        return array_map(null, ...$database);
    }

    private static function databaseFromRangeAndValue(array $range, array $valueRange = []): array
    {
        $range = Functions::flattenArray($range);

        $valueRange = Functions::flattenArray($valueRange);
        if (empty($valueRange)) {
            $valueRange = $range;
        }

        $database = array_map(
            null,
            array_merge([self::CONDITION_COLUMN_NAME], $range),
            array_merge([self::VALUE_COLUMN_NAME], $valueRange)
        );

        return $database;
    }
}
