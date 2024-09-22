<?php

/**
 * Implements special behavior for class attribute (normally NMTOKENS)
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class GFExcel_VendorHTMLPurifier_AttrDef_HTML_Class extends GFExcel_VendorHTMLPurifier_AttrDef_HTML_Nmtokens
{
    /**
     * @param string $string
     * @param HTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return bool|string
     */
    protected function split($string, $config, $context)
    {
        // really, this twiddle should be lazy loaded
        $name = $config->getDefinition('HTML')->doctype->name;
        if ($name == "XHTML 1.1" || $name == "XHTML 2.0") {
            return parent::split($string, $config, $context);
        } else {
            return preg_split('/\s+/', $string);
        }
    }

    /**
     * @param array $tokens
     * @param HTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return array
     */
    protected function filter($tokens, $config, $context)
    {
        $allowed = $config->get('Attr.AllowedClasses');
        $forbidden = $config->get('Attr.ForbiddenClasses');
        $ret = array();
        foreach ($tokens as $token) {
            if (($allowed === null || isset($allowed[$token])) &&
                !isset($forbidden[$token]) &&
                // We need this O(n) check because of PHP's array
                // implementation that casts -0 to 0.
                !in_array($token, $ret, true)
            ) {
                $ret[] = $token;
            }
        }
        return $ret;
    }
}