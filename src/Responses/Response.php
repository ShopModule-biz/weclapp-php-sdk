<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Responses/Response.php

namespace ShopModule\WeclappApi\Responses;

class Response
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = json_decode($data);
    }
    
    public function hasError()
    {
        return property_exists($this->data, 'error');
    }
    
    public function getError()
    {
        return $this->data->error;
    }
    
    public function getdata()
    {
        return $this->data;
    }
    
}