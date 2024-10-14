<?php

/**
 * A "safe" script module. No inline JS is allowed, and pointed to JS
 * files must match whitelist.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class GFExcel_VendorHTMLPurifier_HTMLModule_SafeScripting extends GFExcel_VendorHTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'SafeScripting';

    /**
     * @param HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        // These definitions are not intrinsically safe: the attribute transforms
        // are a vital part of ensuring safety.

        $allowed = $config->get('HTML.SafeScripting');
        $script = $this->addElement(
            'script',
            'Inline',
            'Optional:', // Not `Empty` to not allow to autoclose the <script /> tag @see https://www.w3.org/TR/html4/interact/scripts.html
            null,
            array(
                // While technically not required by the spec, we're forcing
                // it to this value.
                'type' => 'Enum#text/javascript',
                'src*' => new GFExcel_VendorHTMLPurifier_AttrDef_Enum(array_keys($allowed), /*case sensitive*/ true)
            )
        );
        $script->attr_transform_pre[] =
        $script->attr_transform_post[] = new GFExcel_VendorHTMLPurifier_AttrTransform_ScriptRequired();
    }
}

// vim: et sw=4 sts=4
