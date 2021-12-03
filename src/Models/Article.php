<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Models;

class Article extends Model
{
    public function getCustomAttributes(): array
    {
        return $this->customAttributes ?? [];
    }

    public function getCustomAttribute(int $attributeDefinitionId): ?stdClass
    {
        foreach ($this->getCustomAttributes() as $attribute) {
            if ($attributeDefinitionId == $attribute->attributeDefinitionId) {
                return $attribute->numberValue;
            }
        }
        return null;
    }


}