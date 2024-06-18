<?php

namespace Codememory\ApiBundle\Http\ResponseBuilder\Components\Messages;

use Codememory\ApiBundle\Http\ResponseBuilder\Components\AbstractComponent;
use Codememory\ApiBundle\Http\ResponseBuilder\Exceptions\ResponseComponentDoesNotSupportAddingSubcomponentException;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseComponentInterface;
use Codememory\ApiBundle\Http\ResponseBuilder\Interfaces\ResponseSubcomponentInterface;
use Override;

class GroupMessages extends AbstractComponent implements ResponseComponentInterface
{
    public const string NAME = 'messages';
    public const string SUCCESS_TYPE = 'success';
    public const string FAIL_TYPE = 'fail';
    public const string WARNING_TYPE = 'warning';
    public const string INFO_TYPE = 'info';
    protected array $messages = [];

    public function setSuccess(string $text): self
    {
        $this->doAdd(self::SUCCESS_TYPE, $text);

        return $this;
    }

    public function setFail(string $text): self
    {
        $this->doAdd(self::FAIL_TYPE, $text);

        return $this;
    }

    public function setWarning(string $text): self
    {
        $this->doAdd(self::WARNING_TYPE, $text);

        return $this;
    }

    public function setInfo(string $text): self
    {
        $this->doAdd(self::INFO_TYPE, $text);

        return $this;
    }

    public function setCustom(string $type, string $text): self
    {
        $this->doAdd($type, $text);

        return $this;
    }

    public function getKey(): string
    {
        return self::NAME;
    }

    public function exists(string $type): bool
    {
        return count(array_filter($this->messages, static fn (array $message) => $message['type'] === $type)) > 0;
    }

    public function getValue(): array
    {
        return $this->messages;
    }

    #[Override]
    public function addSubcomponent(ResponseSubcomponentInterface $subcomponent): static
    {
        throw new ResponseComponentDoesNotSupportAddingSubcomponentException(self::class);
    }

    private function doAdd(string $type, string $text): void
    {
        if (!$this->exists($type)) {
            $this->messages[] = [
                'type' => $type,
                'text' => $text
            ];
        }
    }
}