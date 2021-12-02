<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Requests;

use ShopModule\WeclappApi\Traits\Requests\HasSeveralResponses;
use ShopModule\WeclappApi\Traits\Requests\IsArticleRequest;

use ShopModule\WeclappApi\Responses\ArticleResponse;

class ArticlesGetRequest extends GetRequest
{
    use HasSeveralResponses;
    use IsArticleRequest;

    protected $responseClass = ArticleResponse::class;
}