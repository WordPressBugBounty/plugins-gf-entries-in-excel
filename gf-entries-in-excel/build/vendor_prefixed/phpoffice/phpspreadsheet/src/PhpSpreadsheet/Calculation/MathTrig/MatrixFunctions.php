<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\MathTrig;

use GFExcel\Vendor\Matrix\Builder;
use GFExcel\Vendor\Matrix\Div0Exception as MatrixDiv0Exception;
use GFExcel\Vendor\Matrix\Exception as MatrixException;
use GFExcel\Vendor\Matrix\Matrix;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Exception;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Functions;

class MatrixFunctions
{
    /**
     * Convert parameter to matrix.
     *
     * @param mixed $matrixValues A matrix of values
     */
    private static function getMatrix($matrixValues): Matrix
    {
        $matrixData = [];
        if (!is_array($matrixValues)) {
            $matrixValues = [[$matrixValues]];
        }

        $row = 0;
        foreach ($matrixValues as $matrixRow) {
            if (!is_array($matrixRow)) {
                $matrixRow = [$matrixRow];
            }
            $column = 0;
            foreach ($matrixRow as $matrixCell) {
                if ((is_string($matrixCell)) || ($matrixCell === null)) {
                    throw new Exception(Functions::VALUE());
                }
                $matrixData[$row][$column] = $matrixCell;
                ++$column;
            }
            ++$row;
        }

        return new Matrix($matrixData);
    }

    /**
     * MDETERM.
     *
     * Returns the matrix determinant of an array.
     *
     * Excel Function:
     *        MDETERM(array)
     *
     * @param mixed $matrixValues A matrix of values
     *
     * @return float|string The result, or a string containing an error
     */
    public static function determinant($matrixValues)
    {
        try {
            $matrix = self::getMatrix($matrixValues);

            return $matrix->determinant();
        } catch (MatrixException $ex) {
            return Functions::VALUE();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * MINVERSE.
     *
     * Returns the inverse matrix for the matrix stored in an array.
     *
     * Excel Function:
     *        MINVERSE(array)
     *
     * @param mixed $matrixValues A matrix of values
     *
     * @return array|string The result, or a string containing an error
     */
    public static function inverse($matrixValues)
    {
        try {
            $matrix = self::getMatrix($matrixValues);

            return $matrix->inverse()->toArray();
        } catch (MatrixDiv0Exception $e) {
            return Functions::NAN();
        } catch (MatrixException $e) {
            return Functions::VALUE();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * MMULT.
     *
     * @param mixed $matrixData1 A matrix of values
     * @param mixed $matrixData2 A matrix of values
     *
     * @return array|string The result, or a string containing an error
     */
    public static function multiply($matrixData1, $matrixData2)
    {
        try {
            $matrixA = self::getMatrix($matrixData1);
            $matrixB = self::getMatrix($matrixData2);

            return $matrixA->multiply($matrixB)->toArray();
        } catch (MatrixException $ex) {
            return Functions::VALUE();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * MUnit.
     *
     * @param mixed $dimension Number of rows and columns
     *
     * @return array|string The result, or a string containing an error
     */
    public static function identity($dimension)
    {
        try {
            $dimension = (int) Helpers::validateNumericNullBool($dimension);
            Helpers::validatePositive($dimension, Functions::VALUE());
            $matrix = Builder::createIdentityMatrix($dimension, 0)->toArray();

            return $matrix;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
