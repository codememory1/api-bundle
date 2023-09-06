<?php

namespace Codememory\ApiBundle\JWT;

use Codememory\ApiBundle\JWT\Interfaces\JWTInterface;
use DateTimeImmutable;
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use const JSON_THROW_ON_ERROR;
use Throwable;

class JWT implements JWTInterface
{
    protected string $alg = 'RS256';

    protected function payloadBuilder(array $payload, int $expire): array
    {
        $now = new DateTimeImmutable();

        return [
            'exp' => $now->getTimestamp() + $expire,
            'lat' => $now->getTimestamp(),
            ...$payload
        ];
    }

    public function setAlg(string $alg): JWTInterface
    {
        $this->alg = $alg;

        return $this;
    }

    public function encode(array $payload, string $privateKey, int $expire): string
    {
        return FirebaseJWT::encode($this->payloadBuilder($payload, $expire), $privateKey, $this->alg);
    }

    public function decode(string $token, string $publicKey): array|bool
    {
        try {
            return json_decode(
                json_encode(FirebaseJWT::decode($token, new Key($publicKey, $this->alg))),
                true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (Throwable) {
            return false;
        }
    }
}