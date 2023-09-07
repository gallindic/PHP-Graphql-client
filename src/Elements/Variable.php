<?php

namespace GraphQL\Elements;

use GraphQL\Enums\VariableType;

class Variable
{
    public function __construct(
        private readonly string $name,
        private readonly VariableType $type,
        private readonly bool $required,
        private readonly mixed $defaultValue = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}