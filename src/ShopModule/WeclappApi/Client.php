<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi;

use ShopModule\WeclappApi\Exceptions\MissingPropertyException;

use ShopModule\WeclappApi\Traits\IsSingleton;

use ShopModule\WeclappApi\Requests\PostRequest;
use ShopModule\WeclappApi\Requests\PutRequest;

class Client
{
    use IsSingleton;
    
    const API_URL = 'https://<tenant>.weclapp.com/webapp/api/v1/';
    
    private $tenant = null;
    
    private $token = null;
    
    public function __construct($tenant = null, $token = null)
    {
        $this->setTenant($tenant);
        $this->setToken($token);
    }
    
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
        return $this;
    }
    
    private function getTenant()
    {
        if (empty($this->tenant)) {
            throw MissingPropertyException::create(get_class($this), 'tenant');
        }
      
        return $this->tenant;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
    private function getToken()
    {
        if (empty($this->token)) {
            throw MissingPropertyException::create(get_class($this), 'token');
        }
      
        return $this->token;
    }
    
    private function buildApiUrl($request)
    {
      $apiUrl = str_replace('<tenant>', $this->getTenant(), self::API_URL);
      return $apiUrl . $request->getPath();
    }
    
    private function buildHttpHeader()
    {
      $token = $this->getToken();
      
      if (empty($token)) {
        throw new Exception('Missing Authentication Token!');
      }
      
      $header = [
          'AuthenticationToken: ' . $token,
          'Content-Type: application/json',
      ];
      
      return $header;
    }
    
    public function sendRequest($request)
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
    
    public function parseResponse($request, $response)
    {
        $responseClass = $request->getResponseClass();
        
        return new $responseClass($response);
    }
    
    
    
    
}
