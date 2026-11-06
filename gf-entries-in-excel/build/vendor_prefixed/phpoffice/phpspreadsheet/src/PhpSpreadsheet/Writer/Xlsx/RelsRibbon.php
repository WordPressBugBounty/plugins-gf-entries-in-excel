<?php
/**
 * @license MIT
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Reader\Xlsx\Namespaces;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\XMLWriter;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Spreadsheet;

class RelsRibbon extends WriterPart
{
    /**
     * Write relationships for additional objects of custom UI (ribbon).
     *
     * @return string XML Output
     */
    public function writeRibbonRelationships(Spreadsheet $spreadsheet)
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $objWriter->startElement('Relationships');
        $objWriter->writeAttribute('xmlns', Namespaces::RELATIONSHIPS);
        $localRels = $spreadsheet->getRibbonBinObjects('names');
        if (is_array($localRels)) {
            foreach ($localRels as $aId => $aTarget) {
                $objWriter->startElement('Relationship');
                $objWriter->writeAttribute('Id', $aId);
                $objWriter->writeAttribute('Type', Namespaces::IMAGE);
                $objWriter->writeAttribute('Target', $aTarget);
                $objWriter->endElement();
            }
        }
        $objWriter->endElement();

        return $objWriter->getData();
    }
}
