<?php

namespace Codememory\ApiBundle\Event;

use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;

final readonly class ResponsePreBuildEvent
{
    public const string NAME = 'codememory.api.response_pre_build';

    public function __construct(
        public ResponseBuilderInterface $responseBuilder
    ) {
    }
}