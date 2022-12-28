<?php
/**
 * LinkIdentityParameters
 
 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */


namespace AuthressSdk\Login;

use \ArrayAccess;
use \AuthressSdk\ObjectSerializer;

/**
 * LinkIdentityParameters Class Doc Comment
 *
 * @category Class
 * @description The access record which links users to roles.
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */
class LinkIdentityParameters
{
    /**
      * Specify which provider connection that user would like to use to log in - see https://authress.io/app/#/manage?focus=connections
      *
      * @var string
      */
    public ?string $connectionId;
    
    /**
      * Instead of connectionId, specify the tenant lookup identifier to log the user with the mapped tenant - see https://authress.io/app/#/manage?focus=tenants*
      * @var string
      */
    public ?string $tenantLookupIdentifier;

    /**
      * Specify where the provider should redirect the user to in your application. If not specified, will be the current location href. Must be a valid redirect url matching what is defined in the application in the Authress Management portal. (Default: **window.location.href**)
      *
      * @var string
      */
    public ?string $redirectUrl;

    /**
      * Connection specific properties to pass to the identity provider. Can be used to override default scopes for example.
      *
      * @var object
      */
    // public $connectionProperties;

    /**
     * Constructor
     *
     * @param array $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
      $this->connectionId = $data['connectionId'] ?? null;
      $this->tenantLookupIdentifier = $data['tenantLookupIdentifier'] ?? null;
      $this->redirectUrl = $data['redirectUrl'] ?? null;
    }
}
