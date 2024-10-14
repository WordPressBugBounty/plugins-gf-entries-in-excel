<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NamedFormula extends DefinedName
{
    /**
     * Create a new Named Formula.
     */
    public function __construct(
        string $name,
        ?Worksheet $worksheet = null,
        ?string $formula = null,
        bool $localOnly = false,
        ?Worksheet $scope = null
    ) {
        // Validate data
        if (!isset($formula)) {
            throw new Exception('You must specify a Formula value for a Named Formula');
        }
        parent::__construct($name, $worksheet, $formula, $localOnly, $scope);
    }

    /**
     * Get the formula value.
     */
    public function getFormula(): string
    {
        return $this->value;
    }

    /**
     * Set the formula value.
     */
    public function setFormula(string $formula): self
    {
        if (!empty($formula)) {
            $this->value = $formula;
        }

        return $this;
    }
}
