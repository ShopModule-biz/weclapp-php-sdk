<?php

/**
 * @author Timo Paul <mail@timopaul.biz>
 * @copyright (c) 2020, Timo Paul Dienstleistungen
 * @license GNU General Public License http://www.gnu.de/documents/gpl-2.0.de.html
 */

namespace ShopModule\WeclappApi\Traits\Requests;

trait HasPages
{
    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $pageSize = 100;

    /**
     * @param int $page
     * @return self
     */
    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $size
     * @return self
     */
    public function setPageSize(int $size): self
    {
        $this->pageSize = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Returns the url parameters for the request.
     *
     * @return array
     */
    public function getHasPagesUrlParameter(): array
    {
        return [
            'page'      => $this->getPage(),
            'pageSize'  => $this->getPageSize(),
        ];
    }

}
