<?php

namespace Codememory\ApiBundle\Decorator\ControllerArgumentValueResolver;

use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorHandlerInterface;
use Codememory\ApiBundle\AttributeHandler\Interfaces\ControllerArgumentValueResolverDecoratorInterface;
use Codememory\ApiBundle\Exceptions\ClassMethodNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class MapEntityFromRouteHandler implements ControllerArgumentValueResolverDecoratorHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ContainerInterface $container
    ) {
    }

    /**
     * @param MapEntityFromRoute $decorator
     */
    public function handle(ControllerArgumentValueResolverDecoratorInterface $decorator, Request $request, ArgumentMetadata $argumentMetadata): iterable
    {
        $entityRepository = $this->getRepository($decorator, $argumentMetadata);
        $routeParamValue = $this->getRouteParameter($request, $decorator);

        if (null === $value = $this->findEntity($decorator, $entityRepository, $routeParamValue)) {
            $this->throwIfEntityNotFound($decorator, $routeParamValue);
        }

        yield $value;
    }

    private function getRepository(MapEntityFromRoute $decorator, ArgumentMetadata $argumentMetadata): EntityRepository
    {
        if (null !== $decorator->repository) {
            return $this->container->get($decorator->repository);
        }

        return $this->em->getRepository($decorator->entityClass ?? $argumentMetadata->getType());
    }

    private function getRouteParameter(Request $request, MapEntityFromRoute $decorator): mixed
    {
        return $request->attributes->get('_route_params')[$decorator->routeParam] ?? throw new LogicException("The {$decorator->routeParam} parameter was not found in the current route");
    }

    private function findEntity(MapEntityFromRoute $decorator, EntityRepository $entityRepository, mixed $routeParamValue): ?object
    {
        if (null !== $decorator->repositoryMethod) {
            return $entityRepository->{$decorator->repositoryMethod}($routeParamValue);
        }

        return $entityRepository->findOneBy([$decorator->entityKey => $routeParamValue]);
    }

    private function throwIfEntityNotFound(MapEntityFromRoute $decorator, mixed $routeParamValue): void
    {
        if ($decorator->throwClass && $decorator->throwStaticMethod) {
            if (!method_exists($decorator->throwClass, $decorator->throwStaticMethod)) {
                throw new ClassMethodNotFoundException($decorator->throwClass, $decorator->throwStaticMethod);
            }

            throw $decorator->throwClass::{$decorator->throwStaticMethod}($routeParamValue);
        }
    }
}