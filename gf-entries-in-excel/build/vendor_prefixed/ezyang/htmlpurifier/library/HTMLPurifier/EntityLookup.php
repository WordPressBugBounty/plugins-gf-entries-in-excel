<?php

/**
 * Object that provides entity lookup table from entity name to character
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */
class GFExcel_VendorHTMLPurifier_EntityLookup
{
    /**
     * Assoc array of entity name to character represented.
     * @type array
     */
    public $table;

    /**
     * Sets up the entity lookup table from the serialized file contents.
     * @param bool $file
     * @note The serialized contents are versioned, but were generated
     *       using the maintenance script generate_entity_file.php
     * @warning This is not in constructor to help enforce the Singleton
     */
    public function setup($file = false)
    {
        if (!$file) {
            $file = HTMLPURIFIER_PREFIX . '/GFExcel_VendorHTMLPurifier/EntityLookup/entities.ser';
        }
        $this->table = unserialize(file_get_contents($file));
    }

    /**
     * Retrieves sole instance of the object.
     * @param bool|GFExcel_VendorHTMLPurifier_EntityLookup $prototype Optional prototype of custom lookup table to overload with.
     * @return GFExcel_VendorHTMLPurifier_EntityLookup
     */
    public static function instance($prototype = false)
    {
        // no references, since PHP doesn't copy unless modified
        static $instance = null;
        if ($prototype) {
            $instance = $prototype;
        } elseif (!$instance) {
            $instance = new GFExcel_VendorHTMLPurifier_EntityLookup();
            $instance->setup();
        }
        return $instance;
    }
}

// vim: et sw=4 sts=4
