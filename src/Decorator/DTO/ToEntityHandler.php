<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ToEntityHandler implements DecoratorHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToEntity $decorator
     *
     * @throws MethodNotFoundException
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $repository = $this->em->getRepository($context->getProperty()->getType()->getName());
        $value = $context->getDataTransferObjectValue();
        $dto = $context->getDataTransferObject();

        if (null === $decorator->whereCallback) {
            $entity = $repository->findOneBy([$decorator->column => $value]);
        } else {
            $entity = $this->callDataTransferObjectMethod($dto, $decorator->whereCallback, [$repository, $value, $context]);
        }

        if (null !== $decorator->entityNotFoundCallback && null === $entity) {
            $this->callDataTransferObjectMethod($dto, $decorator->entityNotFoundCallback, [$value, $context]);
        }

        $context->setDataTransferObjectValue($entity);
        $context->setValueForHarvestableObject($entity);
    }

    /**
     * @throws MethodNotFoundException
     */
    private function callDataTransferObjectMethod(DataTransferObjectInterface $dto, string $method, array $args): mixed
    {
        if (!method_exists($dto::class, $method)) {
            throw new MethodNotFoundException($dto::class, $method);
        }

        return call_user_func([$dto, $method], ...$args);
    }
}