<?php
/**
 * @license MIT
 *
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GFExcel\Vendor\PhpOffice\PhpSpreadsheet\Calculation;

class ExceptionHandler
{
    /**
     * Register errorhandler.
     */
    public function __construct()
    {
        set_error_handler([Exception::class, 'errorHandlerCallback'], E_ALL);
    }

    /**
     * Unregister errorhandler.
     */
    public function __destruct()
    {
        restore_error_handler();
    }
}
