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
     * @param string $tenant
     * @param string $token
     */
    public function __construct(string $tenant, string $token)
    {
        $this->setTenant($tenant);
        $this->setToken($token);
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->buildApiUrl($request));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHttpHeader());
        
        if ($request instanceof PostRequest || $request instanceof PutRequest) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getJsonData());
        }
        
        $response = curl_exec($ch);
        
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
