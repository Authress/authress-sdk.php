<?php
/**
 * AuthenticationParameters
 
 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */


namespace AuthressSdk\Login;

use \ArrayAccess;
use \AuthressSdk\ObjectSerializer;

/**
 * AuthenticationParameters Class Doc Comment
 *
 * @category Class
 * @description The access record which links users to roles.
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */
class AuthenticationParameters
{
    /**
      * Specify which provider connection that user would like to use to log in - see https://authress.io/app/#/manage?focus=connections
      *
      * @var string
      */
    public $connectionId;
    
    /**
      * Instead of connectionId, specify the tenant lookup identifier to log the user with the mapped tenant - see https://authress.io/app/#/manage?focus=tenants*
      * @var string
      */
    public $tenantLookupIdentifier;
      
    /**
      * Store the credentials response in the specified location. Options are either 'cookie' or 'query'. (Default: **cookie**)
      *
      * @var string
      */
    // public $responseLocation;

    /**
      * The type of credentials returned in the response. The list of options is any of 'code token id_token' separated by a space. Select token to receive an access_token, id_token to return the user identity in an JWT, and code for the authorization_code grant_type flow. (Default: **token id_token**)
      *
      * @var string
      */
    // public $flowType;

    /**
      * Specify where the provider should redirect the user to in your application. If not specified, will be the current location href. Must be a valid redirect url matching what is defined in the application in the Authress Management portal. (Default: **window.location.href**)
      *
      * @var string
      */
    public $redirectUrl;

    /**
      * Connection specific properties to pass to the identity provider. Can be used to override default scopes for example.
      *
      * @var object
      */
    // public $connectionProperties;

    /**
      * Force getting new credentials. (Default: **false** - only get new credentials if none exist.)
      *
      * @var boolean
      */
    public $force = false;

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->$connectionId = isset($data['connectionId']) ? $data['connectionId'] : null;
        $this->$tenantLookupIdentifier = isset($data['tenantLookupIdentifier']) ? $data['tenantLookupIdentifier'] : null;
        $this->$redirectUrl = isset($data['redirectUrl']) ? $data['redirectUrl'] : null;
        $this->$force = isset($data['force']) ? $data['force'] : false;
    }
}
