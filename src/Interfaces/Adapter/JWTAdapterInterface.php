<?php

namespace Codememory\ApiBundle\Interfaces\Adapter;

interface JWTAdapterInterface
{
    public function __construct(?string $privateKey, ?string $publicKey, int $ttl);

    public function encode(array $payload, string $alg = 'RS256'): string;

    public function decode(string $token, string $alg = 'RS256'): array|bool;
}