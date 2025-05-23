<?php

/**
 * Configuration definition, defines directives and their defaults.
 *
 * @license LGPL-2.1-or-later
 * Modified by GravityKit using {@see https://github.com/BrianHenryIE/strauss}.
 */
class GFExcel_VendorHTMLPurifier_ConfigSchema
{
    /**
     * Defaults of the directives and namespaces.
     * @type array
     * @note This shares the exact same structure as GFExcel_VendorHTMLPurifier_Config::$conf
     */
    public $defaults = array();

    /**
     * The default property list. Do not edit this property list.
     * @type array
     */
    public $defaultPlist;

    /**
     * Definition of the directives.
     * The structure of this is:
     *
     *  array(
     *      'Namespace' => array(
     *          'Directive' => new stdClass(),
     *      )
     *  )
     *
     * The stdClass may have the following properties:
     *
     *  - If isAlias isn't set:
     *      - type: Integer type of directive, see GFExcel_VendorHTMLPurifier_VarParser for definitions
     *      - allow_null: If set, this directive allows null values
     *      - aliases: If set, an associative array of value aliases to real values
     *      - allowed: If set, a lookup array of allowed (string) values
     *  - If isAlias is set:
     *      - namespace: Namespace this directive aliases to
     *      - name: Directive name this directive aliases to
     *
     * In certain degenerate cases, stdClass will actually be an integer. In
     * that case, the value is equivalent to an stdClass with the type
     * property set to the integer. If the integer is negative, type is
     * equal to the absolute value of integer, and allow_null is true.
     *
     * This class is friendly with GFExcel_VendorHTMLPurifier_Config. If you need introspection
     * about the schema, you're better of using the ConfigSchema_Interchange,
     * which uses more memory but has much richer information.
     * @type array
     */
    public $info = array();

    /**
     * Application-wide singleton
     * @type GFExcel_VendorHTMLPurifier_ConfigSchema
     */
    protected static $singleton;

    public function __construct()
    {
        $this->defaultPlist = new GFExcel_VendorHTMLPurifier_PropertyList();
    }

    /**
     * Unserializes the default ConfigSchema.
     * @return GFExcel_VendorHTMLPurifier_ConfigSchema
     */
    public static function makeFromSerial()
    {
        $contents = file_get_contents(HTMLPURIFIER_PREFIX . '/GFExcel_VendorHTMLPurifier/ConfigSchema/schema.ser');
        $r = unserialize($contents);
        if (!$r) {
            $hash = sha1($contents);
            trigger_error("Unserialization of configuration schema failed, sha1 of file was $hash", E_USER_ERROR);
        }
        return $r;
    }

    /**
     * Retrieves an instance of the application-wide configuration definition.
     * @param GFExcel_VendorHTMLPurifier_ConfigSchema $prototype
     * @return GFExcel_VendorHTMLPurifier_ConfigSchema
     */
    public static function instance($prototype = null)
    {
        if ($prototype !== null) {
            GFExcel_VendorHTMLPurifier_ConfigSchema::$singleton = $prototype;
        } elseif (GFExcel_VendorHTMLPurifier_ConfigSchema::$singleton === null || $prototype === true) {
            GFExcel_VendorHTMLPurifier_ConfigSchema::$singleton = GFExcel_VendorHTMLPurifier_ConfigSchema::makeFromSerial();
        }
        return GFExcel_VendorHTMLPurifier_ConfigSchema::$singleton;
    }

    /**
     * Defines a directive for configuration
     * @warning Will fail of directive's namespace is defined.
     * @warning This method's signature is slightly different from the legacy
     *          define() static method! Beware!
     * @param string $key Name of directive
     * @param mixed $default Default value of directive
     * @param string $type Allowed type of the directive. See
     *      GFExcel_VendorHTMLPurifier_VarParser::$types for allowed values
     * @param bool $allow_null Whether or not to allow null values
     */
    public function add($key, $default, $type, $allow_null)
    {
        $obj = new stdClass();
        $obj->type = is_int($type) ? $type : GFExcel_VendorHTMLPurifier_VarParser::$types[$type];
        if ($allow_null) {
            $obj->allow_null = true;
        }
        $this->info[$key] = $obj;
        $this->defaults[$key] = $default;
        $this->defaultPlist->set($key, $default);
    }

    /**
     * Defines a directive value alias.
     *
     * Directive value aliases are convenient for developers because it lets
     * them set a directive to several values and get the same result.
     * @param string $key Name of Directive
     * @param array $aliases Hash of aliased values to the real alias
     */
    public function addValueAliases($key, $aliases)
    {
        if (!isset($this->info[$key]->aliases)) {
            $this->info[$key]->aliases = array();
        }
        foreach ($aliases as $alias => $real) {
            $this->info[$key]->aliases[$alias] = $real;
        }
    }

    /**
     * Defines a set of allowed values for a directive.
     * @warning This is slightly different from the corresponding static
     *          method definition.
     * @param string $key Name of directive
     * @param array $allowed Lookup array of allowed values
     */
    public function addAllowedValues($key, $allowed)
    {
        $this->info[$key]->allowed = $allowed;
    }

    /**
     * Defines a directive alias for backwards compatibility
     * @param string $key Directive that will be aliased
     * @param string $new_key Directive that the alias will be to
     */
    public function addAlias($key, $new_key)
    {
        $obj = new stdClass;
        $obj->key = $new_key;
        $obj->isAlias = true;
        $this->info[$key] = $obj;
    }

    /**
     * Replaces any stdClass that only has the type property with type integer.
     */
    public function postProcess()
    {
        foreach ($this->info as $key => $v) {
            if (count((array) $v) == 1) {
                $this->info[$key] = $v->type;
            } elseif (count((array) $v) == 2 && isset($v->allow_null)) {
                $this->info[$key] = -$v->type;
            }
        }
    }
}

// vim: et sw=4 sts=4
