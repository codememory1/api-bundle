<?php

namespace Codememory\ApiBundle\Controller;

use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaFactoryInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ResponseSchemaInterface;
use Codememory\ApiBundle\ResponseSchema\Interfaces\ViewInterface;
use Codememory\ApiBundle\ResponseSchema\View\SuccessView;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\EntityResponseControl\Interfaces\ResponsePrototypeInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ResponseSchemaFactoryInterface $responseSchemaFactory
    ) {
    }

    protected function getRequestData(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = json_decode($request->getContent(), true) ?: [];

        return array_merge($data, $request->request->all(), $request->files->all());
    }

    protected function response(int $httpCode, ViewInterface $view): ResponseSchemaInterface
    {
        $responseSchema = $this->responseSchemaFactory->createResponseSchema();

        $responseSchema->setHttpCode($httpCode);
        $responseSchema->setView($view);

        return $responseSchema;
    }

    protected function prototypeResponse(int $httpCode, ResponsePrototypeInterface $prototype): ResponseSchemaInterface
    {
        $responseSchema = $this->responseSchemaFactory->createResponseSchema();

        $responseSchema->setHttpCode($httpCode);
        $responseSchema->setView(new SuccessView($prototype->toArray()));

        return $responseSchema;
    }

    protected function prepareDTO(DataTransferObjectInterface $dto, ?object $object = null, array $extraData = []): void
    {
        if (null !== $object) {
            $dto->setHarvestableObject($object);
        }

        $dto->collect(array_merge($this->getRequestData(), $extraData));
    }
}