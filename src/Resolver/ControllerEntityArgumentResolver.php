<?php

namespace Codememory\ApiBundle\Resolver;

use Codememory\ApiBundle\Entity\Interfaces\EntityInterface;
use Codememory\ApiBundle\Services\Decorator\Decorator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ControllerEntityArgumentResolver implements ValueResolverInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ContainerInterface $container,
        private Decorator $decorator
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($this->supports($argument)) {
            $argumentAttributes = $argument->getAttributes();
            $routeParameter = $this->getRouteParameter($request, $argument);
            $entityRepository = $this->em->getRepository($argument->getType());
            $finedEntity = $entityRepository->findOneBy([$routeParameter['property'] => $routeParameter['value']]);

            $this->decorator->handleByAttributeInstances($argumentAttributes, $this->getController($request), $finedEntity);

            yield $finedEntity;
        } else {
            return [];
        }
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return class_exists($argument->getType()) && in_array(EntityInterface::class, class_implements($argument->getType()), true);
    }

    private function getRouteParameter(Request $request, ArgumentMetadata $argument): array|bool
    {
        $entityClassName = mb_substr($argument->getName(), mb_strrpos($argument->getName(), '\\'));

        foreach ($request->attributes->get('_route_params') as $name => $value) {
            if (str_starts_with($name, lcfirst($entityClassName) . '_')) {
                return [
                    'property' => explode('_', $name, 2)[1],
                    'value' => $value
                ];
            }
        }

        throw new LogicException("In the current controller method, the {$argument->getType()} entity was passed as a dependency, but there is no route parameter for it");
    }

    private function getController(Request $request): object
    {
        return $this->container->get(explode('::', $request->attributes->get('_controller'), 2)[0]);
    }
}