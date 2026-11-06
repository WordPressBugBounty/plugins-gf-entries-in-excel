<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation\Engine\Operands;

interface Operand
{
    public static function fromParser(string $formula, int $index, array $matches): self;

    public function value(): string;
}
