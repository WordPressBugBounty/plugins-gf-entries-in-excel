<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Style;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\IComparable;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Spreadsheet;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class Supervisor implements IComparable
{
    /**
     * Supervisor?
     *
     * @var bool
     */
    protected $isSupervisor;

    /**
     * Parent. Only used for supervisor.
     *
     * @var Spreadsheet|Style
     */
    protected $parent;

    /**
     * Parent property name.
     *
     * @var null|string
     */
    protected $parentPropertyName;

    /**
     * Create a new Supervisor.
     *
     * @param bool $isSupervisor Flag indicating if this is a supervisor or not
     *                                    Leave this value at default unless you understand exactly what
     *                                        its ramifications are
     */
    public function __construct($isSupervisor = false)
    {
        // Supervisor?
        $this->isSupervisor = $isSupervisor;
    }

    /**
     * Bind parent. Only used for supervisor.
     *
     * @param Spreadsheet|Style $parent
     * @param null|string $parentPropertyName
     *
     * @return $this
     */
    public function bindParent($parent, $parentPropertyName = null)
    {
        $this->parent = $parent;
        $this->parentPropertyName = $parentPropertyName;

        return $this;
    }

    /**
     * Is this a supervisor or a cell style component?
     *
     * @return bool
     */
    public function getIsSupervisor()
    {
        return $this->isSupervisor;
    }

    /**
     * Get the currently active sheet. Only used for supervisor.
     *
     * @return Worksheet
     */
    public function getActiveSheet()
    {
        return $this->parent->getActiveSheet();
    }

    /**
     * Get the currently active cell coordinate in currently active sheet.
     * Only used for supervisor.
     *
     * @return string E.g. 'A1'
     */
    public function getSelectedCells()
    {
        return $this->getActiveSheet()->getSelectedCells();
    }

    /**
     * Get the currently active cell coordinate in currently active sheet.
     * Only used for supervisor.
     *
     * @return string E.g. 'A1'
     */
    public function getActiveCell()
    {
        return $this->getActiveSheet()->getActiveCell();
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ((is_object($value)) && ($key != 'parent')) {
                $this->$key = clone $value;
            } else {
                $this->$key = $value;
            }
        }
    }

    /**
     * Export style as array.
     *
     * Available to anything which extends this class:
     * Alignment, Border, Borders, Color, Fill, Font,
     * NumberFormat, Protection, and Style.
     */
    final public function exportArray(): array
    {
        return $this->exportArray1();
    }

    /**
     * Abstract method to be implemented in anything which
     * extends this class.
     *
     * This method invokes exportArray2 with the names and values
     * of all properties to be included in output array,
     * returning that array to exportArray, then to caller.
     */
    abstract protected function exportArray1(): array;

    /**
     * Populate array from exportArray1.
     * This method is available to anything which extends this class.
     * The parameter index is the key to be added to the array.
     * The parameter objOrValue is either a primitive type,
     * which is the value added to the array,
     * or a Style object to be recursively added via exportArray.
     *
     * @param mixed $objOrValue
     */
    final protected function exportArray2(array &$exportedArray, string $index, $objOrValue): void
    {
        if ($objOrValue instanceof self) {
            $exportedArray[$index] = $objOrValue->exportArray();
        } else {
            $exportedArray[$index] = $objOrValue;
        }
    }
}
