<?php

/**
 * Performs validations on GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange
 *
 * @note If you see '// handled by InterchangeBuilder', that means a
 *       design decision in that class would prevent this validation from
 *       ever being necessary. We have them anyway, however, for
 *       redundancy.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */
class GFExcel_VendorHTMLPurifier_ConfigSchema_Validator
{

    /**
     * @type GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange
     */
    protected $interchange;

    /**
     * @type array
     */
    protected $aliases;

    /**
     * Context-stack to provide easy to read error messages.
     * @type array
     */
    protected $context = array();

    /**
     * to test default's type.
     * @type GFExcel_VendorHTMLPurifier_VarParser
     */
    protected $parser;

    public function __construct()
    {
        $this->parser = new GFExcel_VendorHTMLPurifier_VarParser();
    }

    /**
     * Validates a fully-formed interchange object.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange $interchange
     * @return bool
     */
    public function validate($interchange)
    {
        $this->interchange = $interchange;
        $this->aliases = array();
        // PHP is a bit lax with integer <=> string conversions in
        // arrays, so we don't use the identical !== comparison
        foreach ($interchange->directives as $i => $directive) {
            $id = $directive->id->toString();
            if ($i != $id) {
                $this->error(false, "Integrity violation: key '$i' does not match internal id '$id'");
            }
            $this->validateDirective($directive);
        }
        return true;
    }

    /**
     * Validates a GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Id object.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Id $id
     */
    public function validateId($id)
    {
        $id_string = $id->toString();
        $this->context[] = "id '$id_string'";
        if (!$id instanceof GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Id) {
            // handled by InterchangeBuilder
            $this->error(false, 'is not an instance of GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Id');
        }
        // keys are now unconstrained (we might want to narrow down to A-Za-z0-9.)
        // we probably should check that it has at least one namespace
        $this->with($id, 'key')
            ->assertNotEmpty()
            ->assertIsString(); // implicit assertIsString handled by InterchangeBuilder
        array_pop($this->context);
    }

    /**
     * Validates a GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive object.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive $d
     */
    public function validateDirective($d)
    {
        $id = $d->id->toString();
        $this->context[] = "directive '$id'";
        $this->validateId($d->id);

        $this->with($d, 'description')
            ->assertNotEmpty();

        // BEGIN - handled by InterchangeBuilder
        $this->with($d, 'type')
            ->assertNotEmpty();
        $this->with($d, 'typeAllowsNull')
            ->assertIsBool();
        try {
            // This also tests validity of $d->type
            $this->parser->parse($d->default, $d->type, $d->typeAllowsNull);
        } catch (GFExcel_VendorHTMLPurifier_VarParserException $e) {
            $this->error('default', 'had error: ' . $e->getMessage());
        }
        // END - handled by InterchangeBuilder

        if (!is_null($d->allowed) || !empty($d->valueAliases)) {
            // allowed and valueAliases require that we be dealing with
            // strings, so check for that early.
            $d_int = GFExcel_VendorHTMLPurifier_VarParser::$types[$d->type];
            if (!isset(GFExcel_VendorHTMLPurifier_VarParser::$stringTypes[$d_int])) {
                $this->error('type', 'must be a string type when used with allowed or value aliases');
            }
        }

        $this->validateDirectiveAllowed($d);
        $this->validateDirectiveValueAliases($d);
        $this->validateDirectiveAliases($d);

        array_pop($this->context);
    }

    /**
     * Extra validation if $allowed member variable of
     * GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive is defined.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive $d
     */
    public function validateDirectiveAllowed($d)
    {
        if (is_null($d->allowed)) {
            return;
        }
        $this->with($d, 'allowed')
            ->assertNotEmpty()
            ->assertIsLookup(); // handled by InterchangeBuilder
        if (is_string($d->default) && !isset($d->allowed[$d->default])) {
            $this->error('default', 'must be an allowed value');
        }
        $this->context[] = 'allowed';
        foreach ($d->allowed as $val => $x) {
            if (!is_string($val)) {
                $this->error("value $val", 'must be a string');
            }
        }
        array_pop($this->context);
    }

    /**
     * Extra validation if $valueAliases member variable of
     * GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive is defined.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive $d
     */
    public function validateDirectiveValueAliases($d)
    {
        if (is_null($d->valueAliases)) {
            return;
        }
        $this->with($d, 'valueAliases')
            ->assertIsArray(); // handled by InterchangeBuilder
        $this->context[] = 'valueAliases';
        foreach ($d->valueAliases as $alias => $real) {
            if (!is_string($alias)) {
                $this->error("alias $alias", 'must be a string');
            }
            if (!is_string($real)) {
                $this->error("alias target $real from alias '$alias'", 'must be a string');
            }
            if ($alias === $real) {
                $this->error("alias '$alias'", "must not be an alias to itself");
            }
        }
        if (!is_null($d->allowed)) {
            foreach ($d->valueAliases as $alias => $real) {
                if (isset($d->allowed[$alias])) {
                    $this->error("alias '$alias'", 'must not be an allowed value');
                } elseif (!isset($d->allowed[$real])) {
                    $this->error("alias '$alias'", 'must be an alias to an allowed value');
                }
            }
        }
        array_pop($this->context);
    }

    /**
     * Extra validation if $aliases member variable of
     * GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive is defined.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema_Interchange_Directive $d
     */
    public function validateDirectiveAliases($d)
    {
        $this->with($d, 'aliases')
            ->assertIsArray(); // handled by InterchangeBuilder
        $this->context[] = 'aliases';
        foreach ($d->aliases as $alias) {
            $this->validateId($alias);
            $s = $alias->toString();
            if (isset($this->interchange->directives[$s])) {
                $this->error("alias '$s'", 'collides with another directive');
            }
            if (isset($this->aliases[$s])) {
                $other_directive = $this->aliases[$s];
                $this->error("alias '$s'", "collides with alias for directive '$other_directive'");
            }
            $this->aliases[$s] = $d->id->toString();
        }
        array_pop($this->context);
    }

    // protected helper functions

    /**
     * Convenience function for generating GFExcel_VendorHTMLPurifier_ConfigSchema_ValidatorAtom
     * for validating simple member variables of objects.
     * @param $obj
     * @param $member
     * @return GFExcel_VendorHTMLPurifier_ConfigSchema_ValidatorAtom
     */
    protected function with($obj, $member)
    {
        return new GFExcel_VendorHTMLPurifier_ConfigSchema_ValidatorAtom($this->getFormattedContext(), $obj, $member);
    }

    /**
     * Emits an error, providing helpful context.
     * @throws GFExcel_VendorHTMLPurifier_ConfigSchema_Exception
     */
    protected function error($target, $msg)
    {
        if ($target !== false) {
            $prefix = ucfirst($target) . ' in ' . $this->getFormattedContext();
        } else {
            $prefix = ucfirst($this->getFormattedContext());
        }
        throw new GFExcel_VendorHTMLPurifier_ConfigSchema_Exception(trim($prefix . ' ' . $msg));
    }

    /**
     * Returns a formatted context string.
     * @return string
     */
    protected function getFormattedContext()
    {
        return implode(' in ', array_reverse($this->context));
    }
}

// vim: et sw=4 sts=4
