<?php

namespace Codememory\ApiBundle\Controller;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\Data\DataComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Components\Status\StatusComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseBuilderInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractController
{
    public function __construct(
        protected readonly RequestStack $requestStack,
        protected readonly ResponseBuilderInterface $responseBuilder
    ) {
    }

    protected function getRequestData(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = json_decode($request->getContent(), true) ?: [];

        return array_merge($data, $request->request->all(), $request->files->all());
    }

    /**
     * @param array<int, ResponseComponentInterface> $components
     */
    protected function buildResponse(array $data, int $statusCode = 200, array $headers = [], array $components = []): ResponseBuilderInterface
    {
        $this->responseBuilder
            ->setStatus($statusCode)
            ->setHeaders($headers)
            ->addComponent(new StatusComponent('success'))
            ->addComponent(new DataComponent($data));

        foreach ($components as $component) {
            $this->responseBuilder->addComponent($component);
        }

        return $this->responseBuilder;
    }
}