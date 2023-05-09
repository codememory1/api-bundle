<?php

namespace Codememory\ApiBundle\Adapter;

use Codememory\ApiBundle\Interfaces\Adapter\JWTAdapterInterface;
use DateTimeImmutable;
use Firebase\JWT\Key;
use LogicException;
use Firebase\JWT\JWT;
use SebastianBergmann\Diff\Exception;

class BaseJWTAdapter implements JWTAdapterInterface
{
    public function __construct(
        private readonly ?string $privateKey,
        private readonly ?string $publicKey,
        private readonly int $ttl
    ) {
    }

    public function encode(array $payload, string $alg = 'RS256'): string
    {
        if (null === $this->privateKey) {
            throw new LogicException(sprintf(
                'The private key is not specified for the %s adapter, check if the path to the key is correctly specified in services.yaml',
                self::class
            ));
        }

        return JWT::encode($this->payloadBuilder($payload), $this->publicKey, $alg);
    }

    public function decode(string $token, string $alg = 'RS256'): array|bool
    {
        if (null === $this->publicKey) {
            throw new LogicException(sprintf(
                'The public key is not specified for the %s adapter, check if the path to the key is correctly specified in services.yaml',
                self::class
            ));
        }

        try {
            return (array) JWT::decode($token, new Key($this->publicKey, $alg));
        } catch (Exception) {
            return false;
        }
    }

    protected function payloadBuilder(array $payload): array
    {
        $now = new DateTimeImmutable();

        return [
            'exp' => $now->getTimestamp() + $this->ttl,
            'lat' => $now->getTimestamp(),
            ...$payload
        ];
    }
}