<?php

/**
 * XHTML 1.1 Presentation Module, defines simple presentation-related
 * markup. Text Extension Module.
 * @note The official XML Schema and DTD specs further divide this into
 *       two modules:
 *          - Block Presentation (hr)
 *          - Inline Presentation (b, big, i, small, sub, sup, tt)
 *       We have chosen not to heed this distinction, as content_sets
 *       provides satisfactory disambiguation.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class GFExcel_VendorHTMLPurifier_HTMLModule_Presentation extends GFExcel_VendorHTMLPurifier_HTMLModule
{

    /**
     * @type string
     */
    public $name = 'Presentation';

    /**
     * @param HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $this->addElement('hr', 'Block', 'Empty', 'Common');
        $this->addElement('sub', 'Inline', 'Inline', 'Common');
        $this->addElement('sup', 'Inline', 'Inline', 'Common');
        $b = $this->addElement('b', 'Inline', 'Inline', 'Common');
        $b->formatting = true;
        $big = $this->addElement('big', 'Inline', 'Inline', 'Common');
        $big->formatting = true;
        $i = $this->addElement('i', 'Inline', 'Inline', 'Common');
        $i->formatting = true;
        $small = $this->addElement('small', 'Inline', 'Inline', 'Common');
        $small->formatting = true;
        $tt = $this->addElement('tt', 'Inline', 'Inline', 'Common');
        $tt->formatting = true;
    }
}

// vim: et sw=4 sts=4
