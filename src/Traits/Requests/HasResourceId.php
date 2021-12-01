<?php

/**
 * @author    Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license   GNU General Public License
 *            http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Traits\Requests;

trait HasResourceId
{
    protected $id;
    
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getId()
    {
      return $this->id;
    }
    
}
