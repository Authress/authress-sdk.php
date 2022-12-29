<?php
/**
 * AccessRecord
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
use InvalidArgumentException;

/**
 * AccessRecord Class Doc Comment
 *
 * @category    Class
 *
 * @description The access record which links users to roles.
 *
 * @author      Authress Developers
 *
 * @link        https://authress.io/app/#/api
 */
class AccessRecord implements ModelInterface, ArrayAccess
{
    public const DISCRIMINATOR = null;
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DELETED = 'DELETED';
    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'record_id' => 'string',
        'name' => 'string',
        'description' => '',
        'status' => 'string',
        'account' => '\AuthressSdk\Model\V1recordsAccount',
        'users' => '\AuthressSdk\Model\V1recordsUsers[]',
        'groups' => '\AuthressSdk\Model\LinkedGroup[]',
        'admins' => '\AuthressSdk\Model\V1recordsUsers[]',
        'statements' => '\AuthressSdk\Model\V1recordsStatements[]',
        'links' => '\AuthressSdk\Model\V1recordsLinks'
    ];
    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'record_id' => null,
        'name' => null,
        'description' => null,
        'status' => null,
        'account' => null,
        'users' => null,
        'groups' => null,
        'admins' => null,
        'statements' => null,
        'links' => null
    ];
    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'record_id' => 'recordId',
        'name' => 'name',
        'description' => 'description',
        'status' => 'status',
        'account' => 'account',
        'users' => 'users',
        'groups' => 'groups',
        'admins' => 'admins',
        'statements' => 'statements',
        'links' => 'links'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'record_id' => 'setRecordId',
        'name' => 'setName',
        'description' => 'setDescription',
        'status' => 'setStatus',
        'account' => 'setAccount',
        'users' => 'setUsers',
        'groups' => 'setGroups',
        'admins' => 'setAdmins',
        'statements' => 'setStatements',
        'links' => 'setLinks'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'record_id' => 'getRecordId',
        'name' => 'getName',
        'description' => 'getDescription',
        'status' => 'getStatus',
        'account' => 'getAccount',
        'users' => 'getUsers',
        'groups' => 'getGroups',
        'admins' => 'getAdmins',
        'statements' => 'getStatements',
        'links' => 'getLinks'
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
        $this->container['record_id'] = $data['record_id'] ?? null;
        $this->container['name'] = $data['name'] ?? null;
        $this->container['description'] = $data['description'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['account'] = $data['account'] ?? null;
        $this->container['users'] = $data['users'] ?? null;
        $this->container['groups'] = $data['groups'] ?? null;
        $this->container['admins'] = $data['admins'] ?? null;
        $this->container['statements'] = $data['statements'] ?? null;
        $this->container['links'] = $data['links'] ?? null;
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

        if ($this->container['name'] === null) {
            $invalidProperties[] = "'name' can't be null";
        }
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($this->container['status']) && !in_array($this->container['status'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'status', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['account'] === null) {
            $invalidProperties[] = "'account' can't be null";
        }
        if ($this->container['statements'] === null) {
            $invalidProperties[] = "'statements' can't be null";
        }
        if ($this->container['links'] === null) {
            $invalidProperties[] = "'links' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getStatusAllowableValues()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_DELETED,
        ];
    }

    /**
     * Gets record_id
     *
     * @return string
     */
    public function getRecordId()
    {
        return $this->container['record_id'];
    }

    /**
     * Sets record_id
     *
     * @param string $record_id Unique identifier for the record, can be specified on record creation.
     *
     * @return $this
     */
    public function setRecordId($record_id)
    {
        $this->container['record_id'] = $record_id;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     *
     * @param string $name A helpful name for this record
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets description
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     *
     * @param $description More details about this record
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status Current status of the access record.
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $allowedValues = $this->getStatusAllowableValues();
        if (!is_null($status) && !in_array($status, $allowedValues, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Invalid value for 'status', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets account
     *
     * @return \AuthressSdk\Model\V1recordsAccount
     */
    public function getAccount()
    {
        return $this->container['account'];
    }

    /**
     * Sets account
     *
     * @param \AuthressSdk\Model\V1recordsAccount $account account
     *
     * @return $this
     */
    public function setAccount($account)
    {
        $this->container['account'] = $account;

        return $this;
    }

    /**
     * Gets users
     *
     * @return \AuthressSdk\Model\V1recordsUsers[]
     */
    public function getUsers()
    {
        return $this->container['users'];
    }

    /**
     * Sets users
     *
     * @param \AuthressSdk\Model\V1recordsUsers[] $users The list of users this record applies to
     *
     * @return $this
     */
    public function setUsers($users)
    {
        $this->container['users'] = $users;

        return $this;
    }

    /**
     * Gets groups
     *
     * @return \AuthressSdk\Model\LinkedGroup[]
     */
    public function getGroups()
    {
        return $this->container['groups'];
    }

    /**
     * Sets groups
     *
     * @param \AuthressSdk\Model\LinkedGroup[] $groups The list of groups this record applies to. Users in these groups will be receive access to the resources listed.
     *
     * @return $this
     */
    public function setGroups($groups)
    {
        $this->container['groups'] = $groups;

        return $this;
    }

    /**
     * Gets admins
     *
     * @return \AuthressSdk\Model\V1recordsUsers[]
     */
    public function getAdmins()
    {
        return $this->container['admins'];
    }

    /**
     * Sets admins
     *
     * @param \AuthressSdk\Model\V1recordsUsers[] $admins The list of admin that can edit this record even if they do not have global record edit permissions.
     *
     * @return $this
     */
    public function setAdmins($admins)
    {
        $this->container['admins'] = $admins;

        return $this;
    }

    /**
     * Gets statements
     *
     * @return \AuthressSdk\Model\V1recordsStatements[]
     */
    public function getStatements()
    {
        return $this->container['statements'];
    }

    /**
     * Sets statements
     *
     * @param \AuthressSdk\Model\V1recordsStatements[] $statements A list of statements which match roles to resources. Users in this record have all statements apply to them
     *
     * @return $this
     */
    public function setStatements($statements)
    {
        $this->container['statements'] = $statements;

        return $this;
    }

    /**
     * Gets links
     *
     * @return \AuthressSdk\Model\V1recordsLinks
     */
    public function getLinks()
    {
        return $this->container['links'];
    }

    /**
     * Sets links
     *
     * @param \AuthressSdk\Model\V1recordsLinks $links links
     *
     * @return $this
     */
    public function setLinks($links)
    {
        $this->container['links'] = $links;

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
