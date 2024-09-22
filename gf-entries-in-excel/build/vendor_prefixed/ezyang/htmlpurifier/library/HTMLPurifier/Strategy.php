<?php

/**
 * Supertype for classes that define a strategy for modifying/purifying tokens.
 *
 * While HTMLPurifier's core purpose is fixing HTML into something proper,
 * strategies provide plug points for extra configuration or even extra
 * features, such as custom tags, custom parsing of text, etc.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 05-July-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */


abstract class GFExcel_VendorHTMLPurifier_Strategy
{

    /**
     * Executes the strategy on the tokens.
     *
     * @param GFExcel_VendorHTMLPurifier_Token[] $tokens Array of GFExcel_VendorHTMLPurifier_Token objects to be operated on.
     * @param HTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return GFExcel_VendorHTMLPurifier_Token[] Processed array of token objects.
     */
    abstract public function execute($tokens, $config, $context);
}

// vim: et sw=4 sts=4
