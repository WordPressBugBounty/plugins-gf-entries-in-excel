<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Writer\Ods\Cell;

use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Cell\Cell;
use GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

/**
 * @author     Alexander Pervakov <frost-nzcr4@jagmort.com>
 */
class Comment
{
    public static function write(XMLWriter $objWriter, Cell $cell): void
    {
        $comments = $cell->getWorksheet()->getComments();
        if (!isset($comments[$cell->getCoordinate()])) {
            return;
        }
        $comment = $comments[$cell->getCoordinate()];

        $objWriter->startElement('office:annotation');
        $objWriter->writeAttribute('svg:width', $comment->getWidth());
        $objWriter->writeAttribute('svg:height', $comment->getHeight());
        $objWriter->writeAttribute('svg:x', $comment->getMarginLeft());
        $objWriter->writeAttribute('svg:y', $comment->getMarginTop());
        $objWriter->writeElement('dc:creator', $comment->getAuthor());
        $objWriter->writeElement('text:p', $comment->getText()->getPlainText());
        $objWriter->endElement();
    }
}