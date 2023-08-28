<?php

namespace Codememory\ApiBundle\Decorator\DTO;

use function call_user_func;
use Codememory\Dto\Exceptions\MethodNotFoundException;
use Codememory\Dto\Interfaces\DataTransferObjectInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;
use function is_array;

final readonly class ToListEntitiesHandler implements DecoratorHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param ToListEntities $decorator
     *
     * @throws MethodNotFoundException
     */
    public function handle(DecoratorInterface $decorator, ExecutionContextInterface $context): void
    {
        $repository = $this->em->getRepository($decorator->entity);
        $value = $context->getDataTransferObjectValue();
        $dto = $context->getDataTransferObject();

        if (is_array($value)) {
            $items = $decorator->unique ? array_unique($value) : $value;

            if (null === $decorator->whereCallback) {
                $entities = $repository->findBy([$decorator->column => $items]);
            } else {
                $entities = $this->callDataTransferObjectMethod($dto, $decorator->whereCallback, [$repository, $items, $context]);
            }

            $context->setDataTransferObjectValue($entities);
            $context->setValueForHarvestableObject($entities);
        }
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