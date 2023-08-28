<?php

namespace Codememory\ApiBundle\DependencyInjection\Compiler;

use Codememory\ApiBundle\ApiBundle;
use Codememory\ApiBundle\Exceptions\ServiceTagHasNoKeyException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AddJWTAdapterPass implements CompilerPassInterface
{
    /**
     * @throws ServiceTagHasNoKeyException
     */
    public function process(ContainerBuilder $container): void
    {
        $definitions = $container->findTaggedServiceIds(ApiBundle::JWT_ADAPTER_TAG);

        foreach ($definitions as $id => $tags) {
            $tag = $tags[0];

            if (!array_key_exists('private', $tag)) {
                throw new ServiceTagHasNoKeyException(ApiBundle::JWT_ADAPTER_TAG, $id, 'private');
            }

            if (!array_key_exists('public', $tag)) {
                throw new ServiceTagHasNoKeyException(ApiBundle::JWT_ADAPTER_TAG, $id, 'public');
            }

            if (!array_key_exists('ttl', $tag)) {
                throw new ServiceTagHasNoKeyException(ApiBundle::JWT_ADAPTER_TAG, $id, 'ttl');
            }

            $private = $container->getParameterBag()->resolveValue($tag['private']);
            $public = $container->getParameterBag()->resolveValue($tag['public']);

            $container
                ->getDefinition($id)
                ->setArguments([
                    '$privateKey' => file_exists($private) ? file_get_contents($private) : null,
                    '$publicKey' => file_exists($public) ? file_get_contents($public) : null,
                    '$ttl' => $tag['ttl']
                ]);
        }
    }
}