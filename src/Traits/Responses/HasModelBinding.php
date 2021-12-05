<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Traits\Responses;

use stdClass;

trait HasModelBinding
{

    public function bindModel(stdClass $object): object
    {
        if ( ! method_exists($this, 'getModelBinding')) {
            return $object;
        }

        $model = $this->getModelBinding();
        return (new $model)
            ->castFrom($object);
    }

}