<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileExpectedHandler implements DecoratorHandlerInterface
{
    /**
     * @param FileExpected $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $dtoValue = $context->getDataTransferObjectValue();

        if (!$decorator->multiple) {
            $context->setDataTransferObjectValue($dtoValue instanceof UploadedFile ? $dtoValue : null);
        } else {
            $files = [];

            foreach ($dtoValue as $value) {
                if ($value instanceof UploadedFile) {
                    $files[] = $value;
                }
            }

            $context->setDataTransferObjectValue($files);
        }
    }
}