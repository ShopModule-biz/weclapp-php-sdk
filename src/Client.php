<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi;

use CurlHandle;
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

    private string $tenant;
    private string $token;
    private string|null $requestUrl = null;
    private int|null $httpStatus = null;
    private string|null $curlError = null;
    private int|null $curlErrno = null;

    public function __construct(string|null $tenant = null, string|null $token = null)
    {
        null === $tenant || $this->setTenant($tenant);
        null === $token || $this->setToken($token);
    }

    public function setTenant(string $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }

    /**
     * @throws MissingPropertyException
     */
    private function getTenant(): string
    {
        if (empty($this->tenant)) {
            throw MissingPropertyException::create(get_class($this), 'tenant');
        }
      
        return $this->tenant;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @throws MissingPropertyException
     */
    private function getToken(): string
    {
        if (empty($this->token)) {
            throw MissingPropertyException::create(get_class($this), 'token');
        }
      
        return $this->token;
    }

    private function setRequestUrl(string|null $url): self
    {
        $this->requestUrl = $url;
        return $this;
    }

    public function getRequestUrl(): string|null
    {
        return $this->requestUrl;
    }

    private function setHttpStatus(int|null $httpStatus): self
    {
        $this->httpStatus = $httpStatus;
        return $this;
    }

    public function getHttpStatus(): int|null
    {
        return $this->httpStatus;
    }

    protected function setCurlError(string|null $curlError): self
    {
        $this->curlError = $curlError;
        return $this;
    }

    public function getCurlError(): string|null
    {
        return $this->curlError;
    }

    protected function setCurlErrno(int|null $curlErrno): self
    {
        $this->curlErrno = $curlErrno;
        return $this;
    }

    public function getCurlErrno(): int|null
    {
        return $this->curlErrno;
    }

    protected function resetStatusProperties(): void
    {
        $this->setRequestUrl(null);
        $this->setHttpStatus(null);
        $this->setCurlErrno(null);
        $this->setCurlError(null);
    }

    /**
     * @throws MissingPropertyException
     */
    private function buildApiUrl(Request $request): string
    {
        $apiUrl = str_replace('<tenant>', $this->getTenant(), self::API_URL);
        return $apiUrl . $request->getPath();
    }

    /**
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
     * @throws MissingPropertyException
     */
    public function sendRequest(Request $request): Response
    {
        $this->resetStatusProperties();

        $url = $this->buildApiUrl($request);
        $this->setRequestUrl($url);

        /** @var CurlHandle $ch */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHttpHeader());
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        if ($request instanceof PostRequest || $request instanceof PutRequest) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getJsonData());
        }
        
        $response = curl_exec($ch);
        
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $this->setHttpStatus((int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE));

        if ('' != curl_error($ch)) {
            $this->setCurlError(curl_error($ch));
            $this->setCurlErrno(curl_errno($ch));
        }
        curl_close($ch);
        
        return $this->parseResponse($request, $response, $headerSize);
    }

    public function parseResponse(Request $request, string $response, int $headerSize): Response
    {
        $headers = $this->parseResponseHeaders(
            substr($response, 0, $headerSize),
        );

        $response = $this->parseResponseBody(
            substr($response, $headerSize),
            $headers,
        );
        
        $responseClass = $request->getResponseClass();
        return new $responseClass($response, $headers);
    }

    public function parseResponseHeaders(string $header): array
    {
        $headers = [];
        $lines = explode("\r\n", trim($header));

        foreach ($lines as $line) {
            if (str_contains($line, ': ')) {
                list($key, $value) = explode(': ', $line, 2);
                $headers[$key] = $value;
            } else {
                $headers['status'] = $line;
            }
        }
        
        return $headers;
    }

    public function parseResponseBody(string $body, array $headers): string
    {
        if (isset($headers[ContentEncoding::HEADER])
            && ContentEncoding::GZIP === $headers[ContentEncoding::HEADER]
        ) {
            $body = gzdecode($body);
        }
        
        return $body;
    }

}
