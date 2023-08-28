<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class HttpPatchHandler implements DecoratorHandlerInterface
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    /**
     * @param HttpPatch $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $dto = $context->getDataTransferObject();
        $requestData = $this->getRequestData();

        if ($this->isPatch()) {
            if (!array_key_exists($context->getDataKey(), $requestData)) {
                $context->setSkipThisProperty(true);
                $context->setIgnoredSetterCallForHarvestableObject(true);
            } else {
                $dto->addPropertyConstraints($dto, $context->getProperty()->getName(), $decorator->assert);
            }
        }
    }

    private function isPatch(): bool
    {
        return Request::METHOD_PATCH === $this->requestStack->getCurrentRequest()->getMethod();
    }

    private function getRequestData(): array
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest->isXmlHttpRequest()) {
            return $currentRequest->toArray();
        }

        $requestData = $currentRequest->request->all() ?: [];
        $queryData = $currentRequest->query->all() ?: [];

        return array_merge($requestData, $queryData);
    }
}