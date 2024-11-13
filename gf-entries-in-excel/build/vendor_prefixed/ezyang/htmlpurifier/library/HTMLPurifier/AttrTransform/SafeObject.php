<?php

/**
 * Writes default type for all objects. Currently only supports flash.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */
class GFExcel_VendorHTMLPurifier_AttrTransform_SafeObject extends GFExcel_VendorHTMLPurifier_AttrTransform
{
    /**
     * @type string
     */
    public $name = "SafeObject";

    /**
     * @param array $attr
     * @param GFExcel_VendorHTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['type'])) {
            $attr['type'] = 'application/x-shockwave-flash';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
