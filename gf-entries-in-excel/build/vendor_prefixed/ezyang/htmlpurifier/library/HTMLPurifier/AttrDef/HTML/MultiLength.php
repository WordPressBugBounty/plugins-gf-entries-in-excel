<?php

/**
 * Validates a MultiLength as defined by the HTML spec.
 *
 * A multilength is either a integer (pixel count), a percentage, or
 * a relative number.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 29-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class GFExcel_VendorHTMLPurifier_AttrDef_HTML_MultiLength extends GFExcel_VendorHTMLPurifier_AttrDef_HTML_Length
{

    /**
     * @param string $string
     * @param HTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return bool|string
     */
    public function validate($string, $config, $context)
    {
        $string = trim($string);
        if ($string === '') {
            return false;
        }

        $parent_result = parent::validate($string, $config, $context);
        if ($parent_result !== false) {
            return $parent_result;
        }

        $length = strlen($string);
        $last_char = $string[$length - 1];

        if ($last_char !== '*') {
            return false;
        }

        $int = substr($string, 0, $length - 1);

        if ($int == '') {
            return '*';
        }
        if (!is_numeric($int)) {
            return false;
        }

        $int = (int)$int;
        if ($int < 0) {
            return false;
        }
        if ($int == 0) {
            return '0';
        }
        if ($int == 1) {
            return '*';
        }
        return ((string)$int) . '*';
    }
}

// vim: et sw=4 sts=4
