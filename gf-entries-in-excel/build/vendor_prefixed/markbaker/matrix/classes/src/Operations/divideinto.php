<?php

/**
 *
 * Function code for the matrix division operation
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 *
 *Modified by GravityKit on 08-March-2024 using Strauss.
 *@see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\Matrix;

use GFExcel\Vendor\Matrix\Operators\Division;

/**
 * Divides two or more matrix numbers
 *
 * @param array<int, mixed> $matrixValues The numbers to divide
 * @return Matrix
 * @throws Exception
 */
function divideinto(...$matrixValues)
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
