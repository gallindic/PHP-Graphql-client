<?php

namespace GraphQL\Operations;

abstract class GraphqlOperation
{
    protected array $selectFields;

    protected array $vars;

    protected array $arguments;

    public function __construct(
        protected ?string $fieldName = null,
        protected ?string $operationAlias = null
    ) {
        $this->vars = [];
        $this->selectFields = [];
        $this->arguments = [];
    }

    public function alias(string $alias): self
    {
        $this->operationAlias = $alias;

        return $this;
    }

    public function args(array $arguments): self
    {
        $this->arguments = array_merge($this->arguments, $arguments);

        return $this;
    }

    public function variables(array $variables): self
    {
        $this->vars = array_merge($this->vars, $variables);

        return $this;
    }

    public function fields(array $fields): self
    {
        foreach ($fields as $field) {
            array_push($this->selectFields, $field);
        }

        return $this;
    }

    public function getOperationName(): ?string
    {
        return $this->fieldName;
    }

    public function getAlias(): ?string
    {
        return $this->operationAlias;
    }

    public function getVariables(): array
    {
        return $this->vars;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getFields(): array
    {
        return $this->selectFields;
    }

    abstract public function hasSetRequired(): bool;

    abstract public function getOperationType(): string;
}