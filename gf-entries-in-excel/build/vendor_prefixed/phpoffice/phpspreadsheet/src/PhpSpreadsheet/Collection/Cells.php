<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Collection;

use Generator;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Cell;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use GFExcel\Vendor\Psr\SimpleCache\CacheInterface;

class Cells
{
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Parent worksheet.
     *
     * @var null|Worksheet
     */
    private $parent;

    /**
     * The currently active Cell.
     *
     * @var null|Cell
     */
    private $currentCell;

    /**
     * Coordinate of the currently active Cell.
     *
     * @var null|string
     */
    private $currentCoordinate;

    /**
     * Flag indicating whether the currently active Cell requires saving.
     *
     * @var bool
     */
    private $currentCellIsDirty = false;

    /**
     * An index of existing cells. Booleans indexed by their coordinate.
     *
     * @var bool[]
     */
    private $index = [];

    /**
     * Prefix used to uniquely identify cache data for this worksheet.
     *
     * @var string
     */
    private $cachePrefix;

    /**
     * Initialise this new cell collection.
     *
     * @param Worksheet $parent The worksheet for this cell collection
     */
    public function __construct(Worksheet $parent, CacheInterface $cache)
    {
        // Set our parent worksheet.
        // This is maintained here to facilitate re-attaching it to Cell objects when
        // they are woken from a serialized state
        $this->parent = $parent;
        $this->cache = $cache;
        $this->cachePrefix = $this->getUniqueID();
    }

    /**
     * Return the parent worksheet for this cell collection.
     *
     * @return Worksheet
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Whether the collection holds a cell for the given coordinate.
     *
     * @param string $cellCoordinate Coordinate of the cell to check
     *
     * @return bool
     */
    public function has($cellCoordinate)
    {
        if ($cellCoordinate === $this->currentCoordinate) {
            return true;
        }

        // Check if the requested entry exists in the index
        return isset($this->index[$cellCoordinate]);
    }

    /**
     * Add or update a cell in the collection.
     *
     * @param Cell $cell Cell to update
     *
     * @return Cell
     */
    public function update(Cell $cell)
    {
        return $this->add($cell->getCoordinate(), $cell);
    }

    /**
     * Delete a cell in cache identified by coordinate.
     *
     * @param string $cellCoordinate Coordinate of the cell to delete
     */
    public function delete($cellCoordinate): void
    {
        if ($cellCoordinate === $this->currentCoordinate && $this->currentCell !== null) {
            $this->currentCell->detach();
            $this->currentCoordinate = null;
            $this->currentCell = null;
            $this->currentCellIsDirty = false;
        }

        unset($this->index[$cellCoordinate]);

        // Delete the entry from cache
        $this->cache->delete($this->cachePrefix . $cellCoordinate);
    }

    /**
     * Get a list of all cell coordinates currently held in the collection.
     *
     * @return string[]
     */
    public function getCoordinates()
    {
        return array_keys($this->index);
    }

    /**
     * Get a sorted list of all cell coordinates currently held in the collection by row and column.
     *
     * @return string[]
     */
    public function getSortedCoordinates()
    {
        $sortKeys = [];
        foreach ($this->getCoordinates() as $coord) {
            $column = '';
            $row = 0;
            sscanf($coord, '%[A-Z]%d', $column, $row);
            $sortKeys[sprintf('%09d%3s', $row, $column)] = $coord;
        }
        ksort($sortKeys);

        return array_values($sortKeys);
    }

    /**
     * Get highest worksheet column and highest row that have cell records.
     *
     * @return array Highest column name and highest row number
     */
    public function getHighestRowAndColumn()
    {
        // Lookup highest column and highest row
        $col = ['A' => '1A'];
        $row = [1];
        foreach ($this->getCoordinates() as $coord) {
            $c = '';
            $r = 0;
            sscanf($coord, '%[A-Z]%d', $c, $r);
            $row[$r] = $r;
            $col[$c] = strlen($c) . $c;
        }

        // Determine highest column and row
        $highestRow = max($row);
        $highestColumn = substr(max($col), 1);

        return [
            'row' => $highestRow,
            'column' => $highestColumn,
        ];
    }

    /**
     * Return the cell coordinate of the currently active cell object.
     *
     * @return string
     */
    public function getCurrentCoordinate()
    {
        return $this->currentCoordinate;
    }

    /**
     * Return the column coordinate of the currently active cell object.
     *
     * @return string
     */
    public function getCurrentColumn()
    {
        $column = '';
        $row = 0;

        sscanf($this->currentCoordinate, '%[A-Z]%d', $column, $row);

        return $column;
    }

    /**
     * Return the row coordinate of the currently active cell object.
     *
     * @return int
     */
    public function getCurrentRow()
    {
        $column = '';
        $row = 0;

        sscanf($this->currentCoordinate, '%[A-Z]%d', $column, $row);

        return (int) $row;
    }

    /**
     * Get highest worksheet column.
     *
     * @param string $row Return the highest column for the specified row,
     *                    or the highest column of any row if no row number is passed
     *
     * @return string Highest column name
     */
    public function getHighestColumn($row = null)
    {
        if ($row === null) {
            $colRow = $this->getHighestRowAndColumn();

            return $colRow['column'];
        }

        $columnList = [1];
        foreach ($this->getCoordinates() as $coord) {
            $c = '';
            $r = 0;

            sscanf($coord, '%[A-Z]%d', $c, $r);
            if ($r != $row) {
                continue;
            }
            $columnList[] = Coordinate::columnIndexFromString($c);
        }

        return Coordinate::stringFromColumnIndex(max($columnList));
    }

    /**
     * Get highest worksheet row.
     *
     * @param string $column Return the highest row for the specified column,
     *                       or the highest row of any column if no column letter is passed
     *
     * @return int Highest row number
     */
    public function getHighestRow($column = null)
    {
        if ($column === null) {
            $colRow = $this->getHighestRowAndColumn();

            return $colRow['row'];
        }

        $rowList = [0];
        foreach ($this->getCoordinates() as $coord) {
            $c = '';
            $r = 0;

            sscanf($coord, '%[A-Z]%d', $c, $r);
            if ($c != $column) {
                continue;
            }
            $rowList[] = $r;
        }

        return max($rowList);
    }

    /**
     * Generate a unique ID for cache referencing.
     *
     * @return string Unique Reference
     */
    private function getUniqueID()
    {
        return uniqid('phpspreadsheet.', true) . '.';
    }

    /**
     * Clone the cell collection.
     *
     * @param Worksheet $worksheet The new worksheet that we're copying to
     *
     * @return self
     */
    public function cloneCellCollection(Worksheet $worksheet)
    {
        $this->storeCurrentCell();
        $newCollection = clone $this;

        $newCollection->parent = $worksheet;
        if (($newCollection->currentCell !== null) && (is_object($newCollection->currentCell))) {
            $newCollection->currentCell->attach($this);
        }

        // Get old values
        $oldKeys = $newCollection->getAllCacheKeys();
        $oldValues = $newCollection->cache->getMultiple($oldKeys);
        $newValues = [];
        $oldCachePrefix = $newCollection->cachePrefix;

        // Change prefix
        $newCollection->cachePrefix = $newCollection->getUniqueID();
        foreach ($oldValues as $oldKey => $value) {
            $newValues[str_replace($oldCachePrefix, $newCollection->cachePrefix, $oldKey)] = clone $value;
        }

        // Store new values
        $stored = $newCollection->cache->setMultiple($newValues);
        if (!$stored) {
            $newCollection->__destruct();

            throw new PhpSpreadsheetException('Failed to copy cells in cache');
        }

        return $newCollection;
    }

    /**
     * Remove a row, deleting all cells in that row.
     *
     * @param string $row Row number to remove
     */
    public function removeRow($row): void
    {
        foreach ($this->getCoordinates() as $coord) {
            $c = '';
            $r = 0;

            sscanf($coord, '%[A-Z]%d', $c, $r);
            if ($r == $row) {
                $this->delete($coord);
            }
        }
    }

    /**
     * Remove a column, deleting all cells in that column.
     *
     * @param string $column Column ID to remove
     */
    public function removeColumn($column): void
    {
        foreach ($this->getCoordinates() as $coord) {
            $c = '';
            $r = 0;

            sscanf($coord, '%[A-Z]%d', $c, $r);
            if ($c == $column) {
                $this->delete($coord);
            }
        }
    }

    /**
     * Store cell data in cache for the current cell object if it's "dirty",
     * and the 'nullify' the current cell object.
     */
    private function storeCurrentCell(): void
    {
        if ($this->currentCellIsDirty && !empty($this->currentCoordinate)) {
            $this->currentCell->detach();

            $stored = $this->cache->set($this->cachePrefix . $this->currentCoordinate, $this->currentCell);
            if (!$stored) {
                $this->__destruct();

                throw new PhpSpreadsheetException("Failed to store cell {$this->currentCoordinate} in cache");
            }
            $this->currentCellIsDirty = false;
        }

        $this->currentCoordinate = null;
        $this->currentCell = null;
    }

    /**
     * Add or update a cell identified by its coordinate into the collection.
     *
     * @param string $cellCoordinate Coordinate of the cell to update
     * @param Cell $cell Cell to update
     *
     * @return Cell
     */
    public function add($cellCoordinate, Cell $cell)
    {
        if ($cellCoordinate !== $this->currentCoordinate) {
            $this->storeCurrentCell();
        }
        $this->index[$cellCoordinate] = true;

        $this->currentCoordinate = $cellCoordinate;
        $this->currentCell = $cell;
        $this->currentCellIsDirty = true;

        return $cell;
    }

    /**
     * Get cell at a specific coordinate.
     *
     * @param string $cellCoordinate Coordinate of the cell
     *
     * @return null|Cell Cell that was found, or null if not found
     */
    public function get($cellCoordinate)
    {
        if ($cellCoordinate === $this->currentCoordinate) {
            return $this->currentCell;
        }
        $this->storeCurrentCell();

        // Return null if requested entry doesn't exist in collection
        if (!$this->has($cellCoordinate)) {
            return null;
        }

        // Check if the entry that has been requested actually exists
        $cell = $this->cache->get($this->cachePrefix . $cellCoordinate);
        if ($cell === null) {
            throw new PhpSpreadsheetException("Cell entry {$cellCoordinate} no longer exists in cache. This probably means that the cache was cleared by someone else.");
        }

        // Set current entry to the requested entry
        $this->currentCoordinate = $cellCoordinate;
        $this->currentCell = $cell;
        // Re-attach this as the cell's parent
        $this->currentCell->attach($this);

        // Return requested entry
        return $this->currentCell;
    }

    /**
     * Clear the cell collection and disconnect from our parent.
     */
    public function unsetWorksheetCells(): void
    {
        if ($this->currentCell !== null) {
            $this->currentCell->detach();
            $this->currentCell = null;
            $this->currentCoordinate = null;
        }

        // Flush the cache
        $this->__destruct();

        $this->index = [];

        // detach ourself from the worksheet, so that it can then delete this object successfully
        $this->parent = null;
    }

    /**
     * Destroy this cell collection.
     */
    public function __destruct()
    {
        $this->cache->deleteMultiple($this->getAllCacheKeys());
    }

    /**
     * Returns all known cache keys.
     *
     * @return Generator|string[]
     */
    private function getAllCacheKeys()
    {
        foreach ($this->getCoordinates() as $coordinate) {
            yield $this->cachePrefix . $coordinate;
        }
    }
}
