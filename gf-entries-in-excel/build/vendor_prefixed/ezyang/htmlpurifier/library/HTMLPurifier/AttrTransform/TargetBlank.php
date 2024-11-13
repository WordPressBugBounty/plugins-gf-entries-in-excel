<?php

// must be called POST validation

/**
 * Adds target="blank" to all outbound links.  This transform is
 * only attached if Attr.TargetBlank is TRUE.  This works regardless
 * of whether or not Attr.AllowedFrameTargets
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */
class GFExcel_VendorHTMLPurifier_AttrTransform_TargetBlank extends GFExcel_VendorHTMLPurifier_AttrTransform
{
    /**
     * @type GFExcel_VendorHTMLPurifier_URIParser
     */
    private $parser;

    public function __construct()
    {
        $this->parser = new GFExcel_VendorHTMLPurifier_URIParser();
    }

    /**
     * @param array $attr
     * @param GFExcel_VendorHTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['href'])) {
            return $attr;
        }

        // XXX Kind of inefficient
        $url = $this->parser->parse($attr['href']);
        
        // Ignore invalid schemes (e.g. `javascript:`)
        if (!($scheme = $url->getSchemeObj($config, $context))) {
            return $attr;
        }

        if ($scheme->browsable && !$url->isBenign($config, $context)) {
            $attr['target'] = '_blank';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
