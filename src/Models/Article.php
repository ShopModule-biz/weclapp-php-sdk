<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Models;

class Article extends Model
{
    /**
     * @var array
     */
    private $articlePrices = [];

    public function getCustomAttributes(): array
    {
        return $this->customAttributes ?? [];
    }

    public function getCustomAttribute(int $attributeDefinitionId, string $type = 'number'): ?string
    {
        foreach ($this->getCustomAttributes() as $attribute) {
            if ($attributeDefinitionId == $attribute->attributeDefinitionId) {
                $field = $type . 'Value';
                return $attribute->$field ?? null;
            }
        }
        return null;
    }

    public function setArticlePrices(array $prices): self
    {
        $this->articlePrices = [];

        foreach ($prices as $price) {
            $this->articlePrices[] = (new ArticlePrice())
                ->castFrom($price);
        }

        return $this;
    }

    public function getArticlePrices(?string $salesChannel = null, ?string $date = null, ?float $quantity = null): array
    {
        $prices = [];

        foreach ($this->articlePrices as $price) {
            /** @var ArticlePrice $price **/

            // check sales channel
            if (null !== $salesChannel && ! $price->isSalesChannel($salesChannel)) {
                continue;
            }

            // check time
            if (null !== $date && ! $price->isValid($date)) {
                continue;
            }

            // check quantity
            if (null !== $quantity && ! $price->isForQuantity($quantity)) {
                continue;
            }

            $prices[] = $price;
        }

        return $prices;
    }

}