<?php

namespace Codememory\ApiBundle\JWT\Interfaces;

interface JWTInterface
{
    public function setAlg(string $alg): self;

    public function encode(array $payload, string $privateKey, int $expire): string;

    public function decode(string $token, string $publicKey): array|bool;
}