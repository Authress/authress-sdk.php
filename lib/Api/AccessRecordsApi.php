<?php
/**
 * AccessRecordsApi
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
use AuthressSdk\Model\AccessRecord;
use AuthressSdk\Model\AccessRecordCollection;
use AuthressSdk\Model\Account;
use AuthressSdk\Model\ClientClaimRequest;
use AuthressSdk\Model\Invite;
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
 * AccessRecordsApi Class Doc Comment
 *
 * @category Class
 *
 * @author   Authress Developers
 *
 * @link     https://authress.io/app/#/api
 */
class AccessRecordsApi
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
     * Operation createClaim
     *
     * Claim a resource by an allowed user.
     *
     * @param ClientClaimRequest $body body (required)
     *
     * @return object
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createClaim($body)
    {
        [$response] = $this->createClaimWithHttpInfo($body);
        return $response;
    }

    /**
     * Operation createClaimWithHttpInfo
     *
     * Claim a resource by an allowed user.
     *
     * @param ClientClaimRequest $body (required)
     *
     * @return array of object, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createClaimWithHttpInfo($body)
    {
        $returnType = 'object';
        $request = $this->createClaimRequest($body);

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
                    'object',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'createClaim'
     *
     * @param ClientClaimRequest $body (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function createClaimRequest($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling createClaim'
            );
        }

        $resourcePath = '/v1/claims';
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
     * Operation createClaimAsync
     *
     * Claim a resource by an allowed user.
     *
     * @param ClientClaimRequest $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createClaimAsync($body)
    {
        return $this->createClaimAsyncWithHttpInfo($body)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation createClaimAsyncWithHttpInfo
     *
     * Claim a resource by an allowed user.
     *
     * @param ClientClaimRequest $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createClaimAsyncWithHttpInfo($body)
    {
        $returnType = 'object';
        $request = $this->createClaimRequest($body);

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
     * Operation createInvite
     *
     * Create a new invite.
     *
     * @param Invite $body body (required)
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createInvite($body)
    {
        $this->createInviteWithHttpInfo($body);
    }

    /**
     * Operation createInviteWithHttpInfo
     *
     * Create a new invite.
     *
     * @param Invite $body (required)
     *
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createInviteWithHttpInfo($body)
    {
        $returnType = '';
        $request = $this->createInviteRequest($body);

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
     * Create request for operation 'createInvite'
     *
     * @param Invite $body (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function createInviteRequest($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling createInvite'
            );
        }

        $resourcePath = '/v1/invites';
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
                []
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                [],
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
     * Operation createInviteAsync
     *
     * Create a new invite.
     *
     * @param Invite $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createInviteAsync($body)
    {
        return $this->createInviteAsyncWithHttpInfo($body)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation createInviteAsyncWithHttpInfo
     *
     * Create a new invite.
     *
     * @param Invite $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createInviteAsyncWithHttpInfo($body)
    {
        $returnType = '';
        $request = $this->createInviteRequest($body);

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
     * Operation createRecord
     *
     * Create a new access record.
     *
     * @param AccessRecord $body body (required)
     *
     * @return AccessRecord
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createRecord($body)
    {
        [$response] = $this->createRecordWithHttpInfo($body);
        return $response;
    }

    /**
     * Operation createRecordWithHttpInfo
     *
     * Create a new access record.
     *
     * @param AccessRecord $body (required)
     *
     * @return array of \AuthressSdk\Model\AccessRecord, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function createRecordWithHttpInfo($body)
    {
        $returnType = '\AuthressSdk\Model\AccessRecord';
        $request = $this->createRecordRequest($body);

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
                    '\AuthressSdk\Model\AccessRecord',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'createRecord'
     *
     * @param AccessRecord $body (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function createRecordRequest($body)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling createRecord'
            );
        }

        $resourcePath = '/v1/records';
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
     * Operation createRecordAsync
     *
     * Create a new access record.
     *
     * @param AccessRecord $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createRecordAsync($body)
    {
        return $this->createRecordAsyncWithHttpInfo($body)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation createRecordAsyncWithHttpInfo
     *
     * Create a new access record.
     *
     * @param AccessRecord $body (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function createRecordAsyncWithHttpInfo($body)
    {
        $returnType = '\AuthressSdk\Model\AccessRecord';
        $request = $this->createRecordRequest($body);

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
     * Operation deleteInvite
     *
     * Delete an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function deleteInvite($invite_id)
    {
        $this->deleteInviteWithHttpInfo($invite_id);
    }

    /**
     * Operation deleteInviteWithHttpInfo
     *
     * Delete an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function deleteInviteWithHttpInfo($invite_id)
    {
        $returnType = '';
        $request = $this->deleteInviteRequest($invite_id);

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
     * Create request for operation 'deleteInvite'
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function deleteInviteRequest($invite_id)
    {
        // verify the required parameter 'invite_id' is set
        if ($invite_id === null || (is_array($invite_id) && count($invite_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $invite_id when calling deleteInvite'
            );
        }

        $resourcePath = '/v1/invites/{inviteId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($invite_id !== null) {
            $resourcePath = str_replace(
                '{' . 'inviteId' . '}',
                ObjectSerializer::toPathValue($invite_id),
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
     * Operation deleteInviteAsync
     *
     * Delete an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function deleteInviteAsync($invite_id)
    {
        return $this->deleteInviteAsyncWithHttpInfo($invite_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteInviteAsyncWithHttpInfo
     *
     * Delete an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function deleteInviteAsyncWithHttpInfo($invite_id)
    {
        $returnType = '';
        $request = $this->deleteInviteRequest($invite_id);

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
     * Operation deleteRecord
     *
     * Deletes an access record.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function deleteRecord($record_id)
    {
        $this->deleteRecordWithHttpInfo($record_id);
    }

    /**
     * Operation deleteRecordWithHttpInfo
     *
     * Deletes an access record.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return array of null, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function deleteRecordWithHttpInfo($record_id)
    {
        $returnType = '';
        $request = $this->deleteRecordRequest($record_id);

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
     * Create request for operation 'deleteRecord'
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function deleteRecordRequest($record_id)
    {
        // verify the required parameter 'record_id' is set
        if ($record_id === null || (is_array($record_id) && count($record_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $record_id when calling deleteRecord'
            );
        }

        $resourcePath = '/v1/records/{recordId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($record_id !== null) {
            $resourcePath = str_replace(
                '{' . 'recordId' . '}',
                ObjectSerializer::toPathValue($record_id),
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
     * Operation deleteRecordAsync
     *
     * Deletes an access record.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function deleteRecordAsync($record_id)
    {
        return $this->deleteRecordAsyncWithHttpInfo($record_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation deleteRecordAsyncWithHttpInfo
     *
     * Deletes an access record.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function deleteRecordAsyncWithHttpInfo($record_id)
    {
        $returnType = '';
        $request = $this->deleteRecordRequest($record_id);

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
     * Operation getRecord
     *
     * Get an access record for the account.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return AccessRecord
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getRecord($record_id)
    {
        [$response] = $this->getRecordWithHttpInfo($record_id);
        return $response;
    }

    /**
     * Operation getRecordWithHttpInfo
     *
     * Get an access record for the account.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return array of \AuthressSdk\Model\AccessRecord, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getRecordWithHttpInfo($record_id)
    {
        $returnType = '\AuthressSdk\Model\AccessRecord';
        $request = $this->getRecordRequest($record_id);

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
                    '\AuthressSdk\Model\AccessRecord',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'getRecord'
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function getRecordRequest($record_id)
    {
        // verify the required parameter 'record_id' is set
        if ($record_id === null || (is_array($record_id) && count($record_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $record_id when calling getRecord'
            );
        }

        $resourcePath = '/v1/records/{recordId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($record_id !== null) {
            $resourcePath = str_replace(
                '{' . 'recordId' . '}',
                ObjectSerializer::toPathValue($record_id),
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
     * Operation getRecordAsync
     *
     * Get an access record for the account.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getRecordAsync($record_id)
    {
        return $this->getRecordAsyncWithHttpInfo($record_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getRecordAsyncWithHttpInfo
     *
     * Get an access record for the account.
     *
     * @param string $record_id The identifier of the access record. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getRecordAsyncWithHttpInfo($record_id)
    {
        $returnType = '\AuthressSdk\Model\AccessRecord';
        $request = $this->getRecordRequest($record_id);

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
     * Operation getRecords
     *
     * Get all account records.
     *
     * @param int    $limit  Max number of results to return (optional, default to 20)
     * @param string $cursor Continuation cursor for paging (will automatically be set) (optional)
     * @param string $filter Filter to search records by. This is a case insensitive search through every text field. (optional)
     * @param string $status Filter records by their current status. (optional)
     *
     * @return AccessRecordCollection
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getRecords($limit = '20', $cursor = null, $filter = null, $status = null)
    {
        [$response] = $this->getRecordsWithHttpInfo($limit, $cursor, $filter, $status);
        return $response;
    }

    /**
     * Operation getRecordsWithHttpInfo
     *
     * Get all account records.
     *
     * @param int    $limit  Max number of results to return (optional, default to 20)
     * @param string $cursor Continuation cursor for paging (will automatically be set) (optional)
     * @param string $filter Filter to search records by. This is a case insensitive search through every text field. (optional)
     * @param string $status Filter records by their current status. (optional)
     *
     * @return array of \AuthressSdk\Model\AccessRecordCollection, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function getRecordsWithHttpInfo($limit = '20', $cursor = null, $filter = null, $status = null)
    {
        $returnType = '\AuthressSdk\Model\AccessRecordCollection';
        $request = $this->getRecordsRequest($limit, $cursor, $filter, $status);

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
                    '\AuthressSdk\Model\AccessRecordCollection',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'getRecords'
     *
     * @param int    $limit  Max number of results to return (optional, default to 20)
     * @param string $cursor Continuation cursor for paging (will automatically be set) (optional)
     * @param string $filter Filter to search records by. This is a case insensitive search through every text field. (optional)
     * @param string $status Filter records by their current status. (optional)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function getRecordsRequest($limit = '20', $cursor = null, $filter = null, $status = null)
    {
        $resourcePath = '/v1/records';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        if ($limit !== null) {
            $queryParams['limit'] = ObjectSerializer::toQueryValue($limit, 'int32');
        }
        // query params
        if ($cursor !== null) {
            $queryParams['cursor'] = ObjectSerializer::toQueryValue($cursor, null);
        }
        // query params
        if ($filter !== null) {
            $queryParams['filter'] = ObjectSerializer::toQueryValue($filter, null);
        }
        // query params
        if ($status !== null) {
            $queryParams['status'] = ObjectSerializer::toQueryValue($status, null);
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
     * Operation getRecordsAsync
     *
     * Get all account records.
     *
     * @param int    $limit  Max number of results to return (optional, default to 20)
     * @param string $cursor Continuation cursor for paging (will automatically be set) (optional)
     * @param string $filter Filter to search records by. This is a case insensitive search through every text field. (optional)
     * @param string $status Filter records by their current status. (optional)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getRecordsAsync($limit = '20', $cursor = null, $filter = null, $status = null)
    {
        return $this->getRecordsAsyncWithHttpInfo($limit, $cursor, $filter, $status)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getRecordsAsyncWithHttpInfo
     *
     * Get all account records.
     *
     * @param int    $limit  Max number of results to return (optional, default to 20)
     * @param string $cursor Continuation cursor for paging (will automatically be set) (optional)
     * @param string $filter Filter to search records by. This is a case insensitive search through every text field. (optional)
     * @param string $status Filter records by their current status. (optional)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function getRecordsAsyncWithHttpInfo($limit = '20', $cursor = null, $filter = null, $status = null)
    {
        $returnType = '\AuthressSdk\Model\AccessRecordCollection';
        $request = $this->getRecordsRequest($limit, $cursor, $filter, $status);

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
     * Operation respondToInvite
     *
     * Accept an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return Account
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function respondToInvite($invite_id)
    {
        [$response] = $this->respondToInviteWithHttpInfo($invite_id);
        return $response;
    }

    /**
     * Operation respondToInviteWithHttpInfo
     *
     * Accept an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return array of \AuthressSdk\Model\Account, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function respondToInviteWithHttpInfo($invite_id)
    {
        $returnType = '\AuthressSdk\Model\Account';
        $request = $this->respondToInviteRequest($invite_id);

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
                    '\AuthressSdk\Model\Account',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'respondToInvite'
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function respondToInviteRequest($invite_id)
    {
        // verify the required parameter 'invite_id' is set
        if ($invite_id === null || (is_array($invite_id) && count($invite_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $invite_id when calling respondToInvite'
            );
        }

        $resourcePath = '/v1/invites/{inviteId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($invite_id !== null) {
            $resourcePath = str_replace(
                '{' . 'inviteId' . '}',
                ObjectSerializer::toPathValue($invite_id),
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
            'PATCH',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation respondToInviteAsync
     *
     * Accept an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function respondToInviteAsync($invite_id)
    {
        return $this->respondToInviteAsyncWithHttpInfo($invite_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation respondToInviteAsyncWithHttpInfo
     *
     * Accept an invite.
     *
     * @param string $invite_id The identifier of the invite. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function respondToInviteAsyncWithHttpInfo($invite_id)
    {
        $returnType = '\AuthressSdk\Model\Account';
        $request = $this->respondToInviteRequest($invite_id);

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
     * Operation updateRecord
     *
     * Update an access record.
     *
     * @param AccessRecord $body      body (required)
     * @param string       $record_id The identifier of the access record. (required)
     *
     * @return AccessRecord
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function updateRecord($body, $record_id)
    {
        [$response] = $this->updateRecordWithHttpInfo($body, $record_id);
        return $response;
    }

    /**
     * Operation updateRecordWithHttpInfo
     *
     * Update an access record.
     *
     * @param AccessRecord $body      (required)
     * @param string       $record_id The identifier of the access record. (required)
     *
     * @return array of \AuthressSdk\Model\AccessRecord, HTTP status code, HTTP response headers (array of strings)
     *
     * @throws InvalidArgumentException
     * @throws \AuthressSdk\ApiException on non-2xx response
     */
    public function updateRecordWithHttpInfo($body, $record_id)
    {
        $returnType = '\AuthressSdk\Model\AccessRecord';
        $request = $this->updateRecordRequest($body, $record_id);

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
                    '\AuthressSdk\Model\AccessRecord',
                    $e->getResponseHeaders()
                );
                $e->setResponseObject($data);
            }
            throw $e;
        }
    }

    /**
     * Create request for operation 'updateRecord'
     *
     * @param AccessRecord $body      (required)
     * @param string       $record_id The identifier of the access record. (required)
     *
     * @return \GuzzleHttp\Psr7\Request
     *
     * @throws InvalidArgumentException
     */
    protected function updateRecordRequest($body, $record_id)
    {
        // verify the required parameter 'body' is set
        if ($body === null || (is_array($body) && count($body) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $body when calling updateRecord'
            );
        }
        // verify the required parameter 'record_id' is set
        if ($record_id === null || (is_array($record_id) && count($record_id) === 0)) {
            throw new InvalidArgumentException(
                'Missing the required parameter $record_id when calling updateRecord'
            );
        }

        $resourcePath = '/v1/records/{recordId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // path params
        if ($record_id !== null) {
            $resourcePath = str_replace(
                '{' . 'recordId' . '}',
                ObjectSerializer::toPathValue($record_id),
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
     * Operation updateRecordAsync
     *
     * Update an access record.
     *
     * @param AccessRecord $body      (required)
     * @param string       $record_id The identifier of the access record. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function updateRecordAsync($body, $record_id)
    {
        return $this->updateRecordAsyncWithHttpInfo($body, $record_id)
            ->then(
                static function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation updateRecordAsyncWithHttpInfo
     *
     * Update an access record.
     *
     * @param AccessRecord $body      (required)
     * @param string       $record_id The identifier of the access record. (required)
     *
     * @return PromiseInterface
     *
     * @throws InvalidArgumentException
     */
    public function updateRecordAsyncWithHttpInfo($body, $record_id)
    {
        $returnType = '\AuthressSdk\Model\AccessRecord';
        $request = $this->updateRecordRequest($body, $record_id);

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
