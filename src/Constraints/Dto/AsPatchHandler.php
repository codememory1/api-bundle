<?php

namespace Codememory\ApiBundle\Constraints\Dto;

use Codememory\Dto\DataTransferCollection;
use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class AsPatchHandler implements ConstraintHandlerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @param AsPatch $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl): void
    {
        if ($this->isPatch() && !$this->dataKeyExistInRequest($dataTransferControl)) {
            $dataTransferControl->setIsSkipProperty(true);
            $dataTransferControl->setIsIgnoreSetterCall(true);
        } else {
            /** @var DataTransferCollection $collection */
            $collection = $dataTransferControl->dataTransfer->getListDataTransferCollection()[$dataTransferControl->dataTransfer::class];

            $collection->addPropertyValidation($dataTransferControl->property->getName(), $constraint->assert);
        }
    }

    private function isPatch(): bool
    {
        return Request::METHOD_PATCH === $this->requestStack->getCurrentRequest()->getMethod();
    }

    private function dataKeyExistInRequest(DataTransferControl $dataTransferControl): bool
    {
        return array_key_exists($dataTransferControl->getDataKey(), $this->requestData());
    }

    private function requestData(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->isXmlHttpRequest()) {
            return $request->toArray();
        }

        $contentData = json_decode($request->getContent(), true) ?: [];

        return array_merge($contentData, $request->request->all());
    }
}