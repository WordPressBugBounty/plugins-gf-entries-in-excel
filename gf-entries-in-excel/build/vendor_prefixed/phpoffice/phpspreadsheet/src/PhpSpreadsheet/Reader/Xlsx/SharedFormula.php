<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SharedFormula
{
    private string $master;

    private string $formula;

    public function __construct(string $master, string $formula)
    {
        $this->master = $master;
        $this->formula = $formula;
    }

    public function master(): string
    {
        return $this->master;
    }

    public function formula(): string
    {
        return $this->formula;
    }
}
