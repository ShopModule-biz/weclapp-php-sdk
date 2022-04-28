<?php

namespace ShopModule\WeclappApi\Traits\Requests;

trait IgnoresMissingProperties
{
    /**
     * @var bool
     */
    protected $ignoreMissingProperties = true;

    /**
     * @param bool $ignore
     * @return $this
     */
    public function ignoreMissingProperties(bool $ignore = true): self
    {
        $this->ignoreMissingProperties = $ignore;
        return $this;
    }

    /**
     * Returns the url parameters for the request.
     *
     * @return array
     */
    public function getIgnoresMissingPropertiesUrlParameter(): array
    {
        return [
            'ignoreMissingProperties' => $this->ignoreMissingProperties ? 'true' : 'false',
        ];
    }

}