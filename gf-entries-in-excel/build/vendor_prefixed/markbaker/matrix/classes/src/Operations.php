<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\Matrix;

use GFExcel\Vendor\Matrix\Operators\Addition;
use GFExcel\Vendor\Matrix\Operators\DirectSum;
use GFExcel\Vendor\Matrix\Operators\Division;
use GFExcel\Vendor\Matrix\Operators\Multiplication;
use GFExcel\Vendor\Matrix\Operators\Subtraction;

class Operations
{
    public static function add(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Addition operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Addition arguments must be Matrix or array');
        }

        $result = new Addition($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function directsum(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('DirectSum operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('DirectSum arguments must be Matrix or array');
        }

        $result = new DirectSum($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function divideby(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Division operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Division arguments must be Matrix or array');
        }

        $result = new Division($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function divideinto(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Division operation requires at least 2 arguments');
        }

        $matrix = array_pop($matrixValues);
        $matrixValues = array_reverse($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Division arguments must be Matrix or array');
        }

        $result = new Division($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function multiply(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Multiplication operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Multiplication arguments must be Matrix or array');
        }

        $result = new Multiplication($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }

    public static function subtract(...$matrixValues): Matrix
    {
        if (count($matrixValues) < 2) {
            throw new Exception('Subtraction operation requires at least 2 arguments');
        }

        $matrix = array_shift($matrixValues);

        if (is_array($matrix)) {
            $matrix = new Matrix($matrix);
        }
        if (!$matrix instanceof Matrix) {
            throw new Exception('Subtraction arguments must be Matrix or array');
        }

        $result = new Subtraction($matrix);

        foreach ($matrixValues as $matrix) {
            $result->execute($matrix);
        }

        return $result->result();
    }
}