<?php
/**
 * @license LGPL-2.1-or-later
 *
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */

class GFExcel_VendorHTMLPurifier_URIFilter_DisableExternal extends GFExcel_VendorHTMLPurifier_URIFilter
{
    /**
     * @type string
     */
    public $name = 'DisableExternal';

    /**
     * @type array
     */
    protected $ourHostParts = false;

    /**
     * @param GFExcel_VendorHTMLPurifier_Config $config
     * @return void
     */
    public function prepare($config)
    {
        $our_host = $config->getDefinition('URI')->host;
        if ($our_host !== null) {
            $this->ourHostParts = array_reverse(explode('.', $our_host));
        }
    }

    /**
     * @param GFExcel_VendorHTMLPurifier_URI $uri Reference
     * @param GFExcel_VendorHTMLPurifier_Config $config
     * @param GFExcel_VendorHTMLPurifier_Context $context
     * @return bool
     */
    public function filter(&$uri, $config, $context)
    {
        if (is_null($uri->host)) {
            return true;
        }
        if ($this->ourHostParts === false) {
            return false;
        }
        $host_parts = array_reverse(explode('.', $uri->host));
        foreach ($this->ourHostParts as $i => $x) {
            if (!isset($host_parts[$i])) {
                return false;
            }
            if ($host_parts[$i] != $this->ourHostParts[$i]) {
                return false;
            }
        }
        return true;
    }
}

// vim: et sw=4 sts=4
