<?php

/**
 * Abstract base token class that all others inherit from.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 14-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
abstract class GFExcel_VendorHTMLPurifier_Token
{
    /**
     * Line number node was on in source document. Null if unknown.
     * @type int
     */
    public $line;

    /**
     * Column of line node was on in source document. Null if unknown.
     * @type int
     */
    public $col;

    /**
     * Lookup array of processing that this token is exempt from.
     * Currently, valid values are "ValidateAttributes" and
     * "MakeWellFormed_TagClosedError"
     * @type array
     */
    public $armor = array();

    /**
     * Used during MakeWellFormed.  See Note [Injector skips]
     * @type
     */
    public $skip;

    /**
     * @type
     */
    public $rewind;

    /**
     * @type
     */
    public $carryover;

    /**
     * @param string $n
     * @return null|string
     */
    public function __get($n)
    {
        if ($n === 'type') {
            trigger_error('Deprecated type property called; use instanceof', E_USER_NOTICE);
            switch (get_class($this)) {
                case 'GFExcel_VendorHTMLPurifier_Token_Start':
                    return 'start';
                case 'GFExcel_VendorHTMLPurifier_Token_Empty':
                    return 'empty';
                case 'GFExcel_VendorHTMLPurifier_Token_End':
                    return 'end';
                case 'GFExcel_VendorHTMLPurifier_Token_Text':
                    return 'text';
                case 'GFExcel_VendorHTMLPurifier_Token_Comment':
                    return 'comment';
                default:
                    return null;
            }
        }
    }

    /**
     * Sets the position of the token in the source document.
     * @param int $l
     * @param int $c
     */
    public function position($l = null, $c = null)
    {
        $this->line = $l;
        $this->col = $c;
    }

    /**
     * Convenience function for DirectLex settings line/col position.
     * @param int $l
     * @param int $c
     */
    public function rawPosition($l, $c)
    {
        if ($c === -1) {
            $l++;
        }
        $this->line = $l;
        $this->col = $c;
    }

    /**
     * Converts a token into its corresponding node.
     */
    abstract public function toNode();
}

// vim: et sw=4 sts=4
