<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi;

use ShopModule\WeclappApi\Exceptions\MissingPropertyException;

use ShopModule\WeclappApi\Requests\Request;
use ShopModule\WeclappApi\Requests\PostRequest;
use ShopModule\WeclappApi\Requests\PutRequest;
use ShopModule\WeclappApi\Responses\Response;
use ShopModule\WeclappApi\Traits\IsSingleton;

class Client
{
    use IsSingleton;

    /**
     * URL of the weclapp API.
     */
    const API_URL = 'https://<tenant>.weclapp.com/webapp/api/v1/';

    /**
     * Tenant for authentication.
     *
     * @var string
     */
    private $tenant;

    /**
     * Authentication token.
     *
     * @var string
     */
    private $token;

    /**
     * @var string|null
     */
    private $requestUrl = null;

    /**
     * @var int|null
     */
    private $httpStatus = null;

    /**
     * @var string|null
     */
    private $curlError = null;

    /**
     * @var integer|null
     */
    private $curlErrno = null;

    /**
     * @param string|null $tenant
     * @param string|null $token
     */
    public function __construct(?string $tenant = null, ?string $token = null)
    {
        null === $tenant || $this->setTenant($tenant);
        null === $token || $this->setToken($token);
    }

    /**
     * Sets the tenant for authentication.
     *
     * @param string $tenant
     * @return $this
     */
    public function setTenant(string $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }

    /**
     * Returns the tenant for authentication.
     *
     * @return string
     * @throws MissingPropertyException
     */
    private function getTenant(): string
    {
        if (empty($this->tenant)) {
            throw MissingPropertyException::create(get_class($this), 'tenant');
        }
      
        return $this->tenant;
    }

    /**
     * Sets the token for authentication.
     *
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Returns the token used for authentication.
     *
     * @return string
     * @throws MissingPropertyException
     */
    private function getToken(): string
    {
        if (empty($this->token)) {
            throw MissingPropertyException::create(get_class($this), 'token');
        }
      
        return $this->token;
    }

    /**
     * Sets the URL of the last request and returns the current object.
     *
     * @param string|null $url
     * @return $this
     */
    private function setRequestUrl(?string $url): self
    {
        $this->requestUrl = $url;
        return $this;
    }

    /**
     * Returns the URL of the last request.
     *
     * @return string|null
     */
    public function getRequestUrl(): ?string
    {
        return $this->requestUrl;
    }

    /**
     * Sets the HTTP status of the last request and returns the current object.
     *
     * @param int|null $httpStatus
     * @return self
     */
    private function setHttpStatus(?int $httpStatus): self
    {
        $this->httpStatus = $httpStatus;
        return $this;
    }

    /**
     * Returns the HTTP status of the last request.
     *
     * @return int|null
     */
    public function getHttpStatus(): ?int
    {
        return $this->httpStatus;
    }

    /**
     * Sets the CURL error of the last request and returns the current object.
     *
     * @param string|null $curlError
     * @return self
     */
    protected function setCurlError(?string $curlError): self
    {
        $this->curlError = $curlError;
        return $this;
    }

    /**
     * Returns the CURL error of the last request.
     *
     * @return string|null
     */
    public function getCurlError(): ?string
    {
        return $this->curlError;
    }

    /**
     * Sets the CURL error number of the last request and returns the current object.
     *
     * @param int|null $curlErrno
     * @return self
     */
    protected function setCurlErrno(?int $curlErrno): self
    {
        $this->curlErrno = $curlErrno;
        return $this;
    }

    /**
     * Returns the CURL error number of the last request.
     *
     * @return int|null
     */
    public function getCurlErrno(): ?int
    {
        return $this->curlErrno;
    }

    /**
     * Resets the properties of the last request.
     */
    protected function resetStatusProperties(): void
    {
        $this->setRequestUrl(null);
        $this->setHttpStatus(null);
        $this->setCurlErrno(null);
        $this->setCurlError(null);
    }

    /**
     * Generates the complete URL for a request and returns it.
     *
     * @param Request $request
     * @return string
     * @throws MissingPropertyException
     */
    private function buildApiUrl(Request $request): string
    {
        $apiUrl = str_replace('<tenant>', $this->getTenant(), self::API_URL);
        return $apiUrl . $request->getPath();
    }

    /**
     * Generates the HTTP headers for a request and returns it.
     *
     * @return string[]
     * @throws MissingPropertyException
     */
    private function buildHttpHeader(): array
    {
        return [
            'AuthenticationToken: ' . $this->getToken(),
            'Content-Type: application/json',
        ];
    }

    /**
     * Sends a request and returns the parsed response.
     *
     * @param Request $request
     * @return Response
     * @throws MissingPropertyException
     */
    public function sendRequest(Request $request): Response
    {
        $this->resetStatusProperties();

        $url = $this->buildApiUrl($request);
        $this->setRequestUrl($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHttpHeader());
        
        if ($request instanceof PostRequest || $request instanceof PutRequest) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getJsonData());
        }
        
        $response = curl_exec($ch);

        $this->setHttpStatus((int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE));

        if ('' != curl_error($ch)) {
            $this->setCurlError(curl_error($ch));
            $this->setCurlErrno(curl_errno($ch));
        }
        curl_close($ch);
        
        return $this->parseResponse($request, $response);
    }

    /**
     * Parses the response to a request and returns it as a new object.
     *
     * @param Request $request
     * @param string $response
     * @return Response
     */
    public function parseResponse(Request $request, string $response): Response
    {
        $responseClass = $request->getResponseClass();
        
        return new $responseClass($response);
    }
    
}
