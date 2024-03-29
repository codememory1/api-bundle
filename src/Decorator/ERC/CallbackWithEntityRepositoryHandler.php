<?php

namespace Codememory\ApiBundle\Decorator\ERC;

use Codememory\EntityResponseControl\Interfaces\DecoratorHandlerInterface;
use Codememory\EntityResponseControl\Interfaces\DecoratorInterface;
use Codememory\EntityResponseControl\Interfaces\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CallbackWithEntityRepositoryHandler implements DecoratorHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param CallbackWithEntityRepository $decorator
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $context->setValue($context->getResponsePrototype()->{$decorator->callbackMethodName}(
            $this->em->getRepository($decorator->entity),
            $context->getPrototypeObject(),
            $context->getValue()
        ));
    }
}