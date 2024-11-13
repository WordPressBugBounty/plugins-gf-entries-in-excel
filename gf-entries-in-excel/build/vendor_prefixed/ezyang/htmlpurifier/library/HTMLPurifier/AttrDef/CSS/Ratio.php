<?php

/**
 * Validates a ratio as defined by the CSS spec.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */
class GFExcel_VendorHTMLPurifier_AttrDef_CSS_Ratio extends GFExcel_VendorHTMLPurifier_AttrDef
{
    /**
     * @param   string               $ratio   Ratio to validate
     * @param   GFExcel_VendorHTMLPurifier_Config  $config  Configuration options
     * @param   GFExcel_VendorHTMLPurifier_Context $context Context
     *
     * @return  string|boolean
     *
     * @warning Some contexts do not pass $config, $context. These
     *          variables should not be used without checking GFExcel_VendorHTMLPurifier_Length
     */
    public function validate($ratio, $config, $context)
    {
        $ratio = $this->parseCDATA($ratio);

        $parts = explode('/', $ratio, 2);
        $length = count($parts);

        if ($length < 1 || $length > 2) {
            return false;
        }

        $num = new \HTMLPurifier_AttrDef_CSS_Number();

        if ($length === 1) {
            return $num->validate($parts[0], $config, $context);
        }

        $num1 = $num->validate($parts[0], $config, $context);
        $num2 = $num->validate($parts[1], $config, $context);

        if ($num1 === false || $num2 === false) {
            return false;
        }

        return $num1 . '/' . $num2;
    }
}

// vim: et sw=4 sts=4
