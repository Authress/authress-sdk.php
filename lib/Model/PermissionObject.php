<?php
/**
 * PermissionObject
 *
 * PHP version 5
 *
 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */


namespace AuthressSdk\Model;

use \ArrayAccess;
use \AuthressSdk\ObjectSerializer;

/**
 * PermissionObject Class Doc Comment
 *
 * @category Class
 * @description The collective action and associate grants on a permission
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */
class PermissionObject implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'PermissionObject';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'action' => 'string',
'allow' => 'bool',
'grant' => 'bool',
'delegate' => 'bool'    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'action' => null,
'allow' => null,
'grant' => null,
'delegate' => null    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'action' => 'action',
'allow' => 'allow',
'grant' => 'grant',
'delegate' => 'delegate'    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'action' => 'setAction',
'allow' => 'setAllow',
'grant' => 'setGrant',
'delegate' => 'setDelegate'    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'action' => 'getAction',
'allow' => 'getAllow',
'grant' => 'getGrant',
'delegate' => 'getDelegate'    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }


    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['action'] = isset($data['action']) ? $data['action'] : null;
        $this->container['allow'] = isset($data['allow']) ? $data['allow'] : null;
        $this->container['grant'] = isset($data['grant']) ? $data['grant'] : null;
        $this->container['delegate'] = isset($data['delegate']) ? $data['delegate'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['action'] === null) {
            $invalidProperties[] = "'action' can't be null";
        }
        if ($this->container['allow'] === null) {
            $invalidProperties[] = "'allow' can't be null";
        }
        if ($this->container['grant'] === null) {
            $invalidProperties[] = "'grant' can't be null";
        }
        if ($this->container['delegate'] === null) {
            $invalidProperties[] = "'delegate' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->container['action'];
    }

    /**
     * Sets action
     *
     * @param string $action The action the permission grants, can be scoped using `:` and parent actions imply sub-resource permissions, action:* or action implies action:sub-action. This property is case-insensitive, it will always be cast to lowercase before comparing actions to user permissions.
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->container['action'] = $action;

        return $this;
    }

    /**
     * Gets allow
     *
     * @return bool
     */
    public function getAllow()
    {
        return $this->container['allow'];
    }

    /**
     * Sets allow
     *
     * @param bool $allow Does this permission grant the user the ability to execute the action?
     *
     * @return $this
     */
    public function setAllow($allow)
    {
        $this->container['allow'] = $allow;

        return $this;
    }

    /**
     * Gets grant
     *
     * @return bool
     */
    public function getGrant()
    {
        return $this->container['grant'];
    }

    /**
     * Sets grant
     *
     * @param bool $grant Allows the user to give the permission to others without being able to execute the action.
     *
     * @return $this
     */
    public function setGrant($grant)
    {
        $this->container['grant'] = $grant;

        return $this;
    }

    /**
     * Gets delegate
     *
     * @return bool
     */
    public function getDelegate()
    {
        return $this->container['delegate'];
    }

    /**
     * Sets delegate
     *
     * @param bool $delegate Allows delegating or granting the permission to others without being able to execute tha action.
     *
     * @return $this
     */
    public function setDelegate($delegate)
    {
        $this->container['delegate'] = $delegate;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
