<?php

/**
 * This variable parser uses PHP's internal code engine. Because it does
 * this, it can represent all inputs; however, it is dangerous and cannot
 * be used by users.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit on 29-October-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class GFExcel_VendorHTMLPurifier_VarParser_Native extends GFExcel_VendorHTMLPurifier_VarParser
{

    /**
     * @param mixed $var
     * @param int $type
     * @param bool $allow_null
     * @return null|string
     */
    protected function parseImplementation($var, $type, $allow_null)
    {
        return $this->evalExpression($var);
    }

    /**
     * @param string $expr
     * @return mixed
     * @throws GFExcel_VendorHTMLPurifier_VarParserException
     */
    protected function evalExpression($expr)
    {
        $var = null;
        $result = eval("\$var = $expr;");
        if ($result === false) {
            throw new GFExcel_VendorHTMLPurifier_VarParserException("Fatal error in evaluated code");
        }
        return $var;
    }
}

// vim: et sw=4 sts=4
