<?php
/**
 * RolesApi
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */

namespace AuthressSdk\Api;

use AuthressSdk\ApiException;
use AuthressSdk\AuthressClient;
use AuthressSdk\HeaderSelector;
use AuthressSdk\Model\Role;
use AuthressSdk\ObjectSerializer;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

/**
 * RolesApi Class Doc Comment
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */
class RolesApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var AuthressClient
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @param HeaderSelector $selector
     */
    public function __construct(AuthressClient $config, HeaderSelector $selector = null)
    {
        $this->client = new Client();
        $this->config = $config;
        $this->headerSelector = $selector ?: new HeaderSelector();
    }

    /**
     * @return AuthressClient
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation createRole
     *
     * Create a role.
     *
     * @param Role $body body (required)
     *
     * @return Role
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createRole($body)
    {
        [$response] = $this->createRoleWithHttpInfo($body);
        return $response;
    }

    /**
     * Operation createRoleWithHttpInfo
     *
     * Create a role.
     *
     * @param Role $body (required)
     *
     * @return array of \AuthressSdk\Model\Role, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createRoleWithHttpInfo($body)
    {
        $returnType = '\AuthressSdk\Model\Role';
        $request = $this->createRoleRequest($body);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if (!in_array($returnType, ['string', 'integer', 'bool'])) {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];
        } catch (ApiException $e) {
            if ($e->getCode() == 200) {
                $data = ObjectSerializer::deserialize(
                    $e->getResponseBody(),
                    '\AuthressSdk\Model\Role',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'createRole'
     *
     * @param Role $body (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function createRoleRequest($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling createRole'
            );
        }

        $resourcePath = '/v1/roles';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // body params
        $_tempBody = null;
        if (isset($body)) {
            $_tempBody = $body;
        }

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/links+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/links+json'],
                ['application/json']
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'POST',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @return array of http client options
     *
     * @throws RuntimeException on file opening failure
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }

    /**
     * Operation createRoleAsync
     *
     * Create a role.
     *
     * @param Role $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createRoleAsync($body)
    {
        return $this->createRoleAsyncWithHttpInfo($body)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation createRoleAsyncWithHttpInfo
     *
     * Create a role.
     *
     * @param Role $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createRoleAsyncWithHttpInfo($body)
    {
        $returnType = '\AuthressSdk\Model\Role';
        $request = $this->createRoleRequest($body);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Operation deleteRole
     *
     * Deletes a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function deleteRole($role_id)
    {
        $this->deleteRoleWithHttpInfo($role_id);
    }

    /**
     * Operation deleteRoleWithHttpInfo
     *
     * Deletes a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function deleteRoleWithHttpInfo($role_id)
    {
        $returnType = '';
        $request = $this->deleteRoleRequest($role_id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            return [null, $statusCode, $response->getHeaders()];
        } catch (ApiException $e) {
            throw $e;
        }
    }

    /**
     * Create request for operation 'deleteRole'
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function deleteRoleRequest($role_id)
    {
        // verify the required parameter 'role_id' is set
        if ($role_id === null || (is_array($role_id) && count($role_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $role_id when calling deleteRole'
            );
        }

        $resourcePath = '/v1/roles/{roleId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($role_id !== null) {
            $resourcePath = str_replace(
                '{' . 'roleId' . '}',
                ObjectSerializer::toPathValue($role_id),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                []
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                [],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'DELETE',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation deleteRoleAsync
     *
     * Deletes a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function deleteRoleAsync($role_id)
    {
        return $this->deleteRoleAsyncWithHttpInfo($role_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteRoleAsyncWithHttpInfo
     *
     * Deletes a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function deleteRoleAsyncWithHttpInfo($role_id)
    {
        $returnType = '';
        $request = $this->deleteRoleRequest($role_id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    return [null, $response->getStatusCode(), $response->getHeaders()];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Operation getRole
     *
     * Get a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return Role
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getRole($role_id)
    {
        [$response] = $this->getRoleWithHttpInfo($role_id);
        return $response;
    }

    /**
     * Operation getRoleWithHttpInfo
     *
     * Get a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return array of \AuthressSdk\Model\Role, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getRoleWithHttpInfo($role_id)
    {
        $returnType = '\AuthressSdk\Model\Role';
        $request = $this->getRoleRequest($role_id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if (!in_array($returnType, ['string', 'integer', 'bool'])) {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];
        } catch (ApiException $e) {
            if ($e->getCode() == 200) {
                $data = ObjectSerializer::deserialize(
                    $e->getResponseBody(),
                    '\AuthressSdk\Model\Role',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'getRole'
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function getRoleRequest($role_id)
    {
        // verify the required parameter 'role_id' is set
        if ($role_id === null || (is_array($role_id) && count($role_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $role_id when calling getRole'
            );
        }

        $resourcePath = '/v1/roles/{roleId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($role_id !== null) {
            $resourcePath = str_replace(
                '{' . 'roleId' . '}',
                ObjectSerializer::toPathValue($role_id),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/links+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/links+json'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation getRoleAsync
     *
     * Get a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getRoleAsync($role_id)
    {
        return $this->getRoleAsyncWithHttpInfo($role_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getRoleAsyncWithHttpInfo
     *
     * Get a role.
     *
     * @param string $role_id The identifier of the role. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getRoleAsyncWithHttpInfo($role_id)
    {
        $returnType = '\AuthressSdk\Model\Role';
        $request = $this->getRoleRequest($role_id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Operation updateRole
     *
     * Update a role.
     *
     * @param Role   $body    body (required)
     * @param string $role_id The identifier of the role. (required)
     *
     * @return Role
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function updateRole($body, $role_id)
    {
        [$response] = $this->updateRoleWithHttpInfo($body, $role_id);
        return $response;
    }

    /**
     * Operation updateRoleWithHttpInfo
     *
     * Update a role.
     *
     * @param Role   $body    (required)
     * @param string $role_id The identifier of the role. (required)
     *
     * @return array of \AuthressSdk\Model\Role, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function updateRoleWithHttpInfo($body, $role_id)
    {
        $returnType = '\AuthressSdk\Model\Role';
        $request = $this->updateRoleRequest($body, $role_id);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if (!in_array($returnType, ['string', 'integer', 'bool'])) {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];
        } catch (ApiException $e) {
            if ($e->getCode() == 200) {
                $data = ObjectSerializer::deserialize(
                    $e->getResponseBody(),
                    '\AuthressSdk\Model\Role',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'updateRole'
     *
     * @param Role   $body    (required)
     * @param string $role_id The identifier of the role. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function updateRoleRequest($body, $role_id)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling updateRole'
            );
        }
        // verify the required parameter 'role_id' is set
        if ($role_id === null || (is_array($role_id) && count($role_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $role_id when calling updateRole'
            );
        }

        $resourcePath = '/v1/roles/{roleId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($role_id !== null) {
            $resourcePath = str_replace(
                '{' . 'roleId' . '}',
                ObjectSerializer::toPathValue($role_id),
                $resourcePath
            );
        }

        // body params
        $_tempBody = null;
        if (isset($body)) {
            $_tempBody = $body;
        }

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/links+json']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/links+json'],
                ['application/json']
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = Query::build($formParams);
            }
        }

        // // this endpoint requires Bearer token
        if ($this->config->getAccessToken() !== null) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = Query::build($queryParams);
        return new Request(
            'PUT',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation updateRoleAsync
     *
     * Update a role.
     *
     * @param Role   $body    (required)
     * @param string $role_id The identifier of the role. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function updateRoleAsync($body, $role_id)
    {
        return $this->updateRoleAsyncWithHttpInfo($body, $role_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation updateRoleAsyncWithHttpInfo
     *
     * Update a role.
     *
     * @param Role   $body    (required)
     * @param string $role_id The identifier of the role. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function updateRoleAsyncWithHttpInfo($body, $role_id)
    {
        $returnType = '\AuthressSdk\Model\Role';
        $request = $this->updateRoleRequest($body, $role_id);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                static function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                static function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }
}
