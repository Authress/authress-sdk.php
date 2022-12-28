<?php
/**
 * IdentityRequest
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */

namespace AuthressSdk\Model;

use ArrayAccess;
use AuthressSdk\ObjectSerializer;

/**
 * IdentityRequest Class Doc Comment
 *
 * @category    Class
 *
 * @description Request to link an identity provider&#x27;s audience and your app&#x27;s audience with Authress.
 *
 * @author      Authress Developers
 *
 * @link        https://authress.io/app/#/api
 */
class IdentityRequest implements ModelInterface, ArrayAccess
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'IdentityRequest';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'jwt' => 'string',
        'preferred_audience' => ''
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'jwt' => null,
        'preferred_audience' => null
    ];
    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'jwt' => 'jwt',
        'preferred_audience' => 'preferredAudience'
    ];
    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'jwt' => 'setJwt',
        'preferred_audience' => 'setPreferredAudience'
    ];
    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'jwt' => 'getJwt',
        'preferred_audience' => 'getPreferredAudience'
    ];
    /**
     * Associative array for storing property values
     *
     * @var array
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param array $data Associated array of property values
     *                    initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['jwt'] = $data['jwt'] ?? null;
        $this->container['preferred_audience'] = $data['preferred_audience'] ?? null;
    }

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
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['jwt'] === null) {
            $invalidProperties[] = "'jwt' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Gets jwt
     *
     * @return string
     */
    public function getJwt()
    {
        return $this->container['jwt'];
    }

    /**
     * Sets jwt
     *
     * @param string $jwt A valid JWT OIDC compliant token which will still pass authentication requests to the identity provider. Must contain a unique audience and issuer.
     *
     * @return $this
     */
    public function setJwt($jwt)
    {
        $this->container['jwt'] = $jwt;

        return $this;
    }

    /**
     * Gets preferred_audience
     */
    public function getPreferredAudience()
    {
        return $this->container['preferred_audience'];
    }

    /**
     * Sets preferred_audience
     *
     * @param $preferred_audience If the `jwt` token contains more than one valid audience, then the single audience that should associated with Authress. If more than one audience is preferred, repeat this call with each one.
     *
     * @return $this
     */
    public function setPreferredAudience($preferred_audience)
    {
        $this->container['preferred_audience'] = $preferred_audience;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param int $offset Offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param int $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int   $offset Offset
     * @param mixed $value  Value to be set
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
     * @param int $offset Offset
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
                \JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
