<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Links;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;

class LinksComponent extends AbstractComponent implements ResponseComponentInterface
{
    public const string NAME = 'links';
    protected array $links = [];

    public function __construct(string $self)
    {
        $this->links['self'] = $self;
    }

    public function addRelated(string $key, string $link): self
    {
        if (!array_key_exists('related', $this->links)) {
            $this->links['related'] = [];
        }

        if (!array_key_exists($key, $this->links['related'])) {
            $this->links['related'][$key] = $link;
        }

        return $this;
    }

    public function setPrevious(string $link): self
    {
        if (!array_key_exists('previous', $this->links)) {
            $this->links['previous'] = $link;
        }

        return $this;
    }

    public function setNext(string $link): self
    {
        if (!array_key_exists('next', $this->links)) {
            $this->links['next'] = $link;
        }

        return $this;
    }

    public function getKey(): string
    {
        return self::NAME;
    }

    public function getValue(): array
    {
        return $this->links;
    }
}