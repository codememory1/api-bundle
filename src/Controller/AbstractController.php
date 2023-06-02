<?php

namespace Codememory\ApiBundle\Controller;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;
use Codememory\ApiBundle\ResponseSchema\ResponseSchema;
use Codememory\ApiBundle\ResponseSchema\View\SuccessView;
use Codememory\Dto\Interfaces\DataTransferInterface;
use Codememory\EntityResponseControl\Interfaces\ResponseControlInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    protected function getRequestData(): array
    {
        $data = json_decode($this->requestStack->getCurrentRequest()->getContent(), true) ?: [];

        return array_merge($data, $this->requestStack->getCurrentRequest()->request->all());
    }

    protected function response(int $httpCode, ViewInterface $view): ResponseSchemaInterface
    {
        $response = new ResponseSchema();

        $response->setHttpCode($httpCode);
        $response->setView($view);

        return $response;
    }

    protected function responseControl(int $httpCode, ResponseControlInterface $responseControl): ResponseSchemaInterface
    {
        $response = new ResponseSchema();

        $response->setHttpCode($httpCode);
        $response->setView(new SuccessView($responseControl->collect()->toArray()));

        return $response;
    }

    protected function prepareDTO(DataTransferInterface $dto, ?object $object = null, array $extraData = []): void
    {
        if (null !== $object) {
            $dto->setObject($object);
        }

        $dto->collect(array_merge($this->getRequestData(), $extraData));
    }
}