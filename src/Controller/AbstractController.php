<?php

namespace Codememory\ApiBundle\Controller;

use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractController
{
    public function __construct(
        protected readonly RequestStack $requestStack
    ) {
    }

    protected function getRequestData(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = json_decode($request->getContent(), true) ?: [];

        return array_merge($data, $request->request->all(), $request->files->all());
    }

    protected function prepareDTO(DataTransferObjectInterface $dto, ?object $object = null, array $extraData = []): void
    {
        if (null !== $object) {
            $dto->setHarvestableObject($object);
        }

        $dto->collect(array_merge($this->getRequestData(), $extraData));
    }
}