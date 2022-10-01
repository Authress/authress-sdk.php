<?php
/**
 * ServiceTest

 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */


namespace AuthressSdk;

use AuthressSdk\AuthressClient;
use AuthressSdk\ApiException;
use AuthressSdk\ObjectSerializer;
/**
 * ServiceTest Class Doc Comment
 *
 * @category Class
 * @package  AuthressSdk
 * @author   Authress Developers
 * @link     https://authress.io/app/#/api
 */
class ServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testUserPermissionTest()
    {
        // create an instance of the API class during service initialization
        // Replace DOMAIN with the Authress domain for your account
        // $authressClient = new AuthressClient('https://DOMAIN.api-REGION.authress.io');
        // $authressClient->setApiKey('eyJ...');
        // $authressClient->setAccessToken("user-JWT");
        // $apiInstance = new \AuthressSdk\Api\UserPermissionsApi($authressClient);
        
        // try {
        //     $userId = "Authress|google-oauth2|100822687410662214374";
        //     // $userId = "test-userId";
        //     $resourceUri = "test-resource";
        //     $permission = "test-permission";
        //     $result = $apiInstance->authorizeUser($userId, $resourceUri, $permission);
        // } catch (ApiException $e) {
        //     if ($e->getStatusCode() === 404 || $e->getStatusCode() === 403) {
        //         return false;
        //     }
        //     throw $e;
        // }

        // json_decode(file_get_contents('../../vendor/composer/installed.json'), true);
        $this->markTestIncomplete('Implementation of this test is not complete.');
        print(implode(' ', get_defined_constants()));
    }
}
