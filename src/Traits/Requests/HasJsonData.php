<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License 
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

// /includes/external/shopmodule/weclapp_api/src/Traits/Requests/HasJsonData.php

namespace ShopModule\WeclappApi\Traits\Requests;

trait HasJsonData
{
  
    public function getJsonData()
    {
        return json_encode($this->getData());
    }
  
}
