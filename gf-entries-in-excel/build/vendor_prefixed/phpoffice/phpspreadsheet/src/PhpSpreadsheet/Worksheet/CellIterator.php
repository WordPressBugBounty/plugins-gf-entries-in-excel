<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet;

use Iterator;

abstract class CellIterator implements Iterator
{
    /**
     * Worksheet to iterate.
     *
     * @var Worksheet
     */
    protected $worksheet;

    /**
     * Iterate only existing cells.
     *
     * @var bool
     */
    protected $onlyExistingCells = false;

    /**
     * Destructor.
     */
    public function __destruct()
    {
        // @phpstan-ignore-next-line
        $this->worksheet = null;
    }

    /**
     * Get loop only existing cells.
     */
    public function getIterateOnlyExistingCells(): bool
    {
        return $this->onlyExistingCells;
    }

    /**
     * Validate start/end values for "IterateOnlyExistingCells" mode, and adjust if necessary.
     */
    abstract protected function adjustForExistingOnlyRange();

    /**
     * Set the iterator to loop only existing cells.
     */
    public function setIterateOnlyExistingCells(bool $value): void
    {
        $this->onlyExistingCells = (bool) $value;

        $this->adjustForExistingOnlyRange();
    }
}
