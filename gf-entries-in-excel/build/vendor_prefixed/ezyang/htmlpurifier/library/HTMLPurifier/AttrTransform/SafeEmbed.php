<?php
/**
 * @license LGPL-2.1-or-later
 *
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

class GFExcel_VendorHTMLPurifier_AttrTransform_SafeEmbed extends GFExcel_VendorHTMLPurifier_AttrTransform
{
    /**
     * @type string
     */
    public $name = "SafeEmbed";

    /**
     * @param array $attr
     * @param HTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        $attr['allowscriptaccess'] = 'never';
        $attr['allownetworking'] = 'internal';
        $attr['type'] = 'application/x-shockwave-flash';
        return $attr;
    }
}

// vim: et sw=4 sts=4
