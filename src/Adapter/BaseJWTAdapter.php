<?php

namespace Codememory\ApiBundle\Adapter;

use Codememory\ApiBundle\Interfaces\Adapter\JWTAdapterInterface;
use DateTimeImmutable;
use Firebase\JWT\Key;
use LogicException;
use Firebase\JWT\JWT;
use Throwable;

class BaseJWTAdapter implements JWTAdapterInterface
{
    protected string $alg = 'RS256';

    public function __construct(
        private readonly ?string $privateKey,
        private readonly ?string $publicKey,
        private readonly int $ttl
    ) {
    }

    public function setAlg(string $alg): self
    {
        $this->alg = $alg;

        return $this;
    }

    public function encode(array $payload): string
    {
        if (null === $this->privateKey) {
            throw new LogicException(sprintf(
                'The private key is not specified for the %s adapter, check if the path to the key is correctly specified in services.yaml',
                self::class
            ));
        }

        return JWT::encode($this->payloadBuilder($payload), $this->privateKey, $this->alg);
    }

    public function decode(string $token): array|bool
    {
        if (null === $this->publicKey) {
            throw new LogicException(sprintf(
                'The public key is not specified for the %s adapter, check if the path to the key is correctly specified in services.yaml',
                self::class
            ));
        }

        try {
            return json_decode(
                json_encode(JWT::decode($token, new Key($this->publicKey, $this->alg))),
                true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (Throwable) {
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