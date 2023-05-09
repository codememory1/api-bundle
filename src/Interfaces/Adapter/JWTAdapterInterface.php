<?php

namespace Codememory\ApiBundle\Interfaces\Adapter;

interface JWTAdapterInterface
{
    public function __construct(?string $privateKey, ?string $publicKey, int $ttl);

    public function setAlg(string $alg): self;

    public function encode(array $payload): string;

    public function decode(string $token): array|bool;
}