<?php

namespace ShopModule\WeclappApi\Traits\Requests;

trait IsFilterable
{
    /**
     * @var array $filters
     */
    private $filters = [];

    /**
     * Adds a new filterable property for the request.
     *
     * @param string $property
     * @param string $operator
     * @param mixed $query
     * @return $this
     */
    public function filter(string $property, string $operator, $query): self
    {
        $attribute = urlencode($property . '-' . $operator);
        $this->filters[$attribute] = urlencode($query);
        return $this;
    }

    /**
     * Returns the url parameters for the request.
     *
     * @return array
     */
    public function getIsFilterableUrlParameter(): array
    {
        return $this->filters;
    }

}