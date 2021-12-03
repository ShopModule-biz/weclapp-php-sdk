<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Responses;

use ShopModule\WeclappApi\Models\Article;

class ArticlesResponse extends SeveralResponse
{
    public function getModelBinding(): string
    {
        return Article::class;
    }
}