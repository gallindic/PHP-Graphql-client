<?php

namespace GraphQL\Builder;

use GraphQL\Elements\Variable;
use GraphQL\Operations\GraphqlOperation;
use GraphQL\Operations\Query;

class QueryBuilder
{
    public function build(GraphqlOperation $operation): string
    {
        $rawQuery = $this->getTemplate();

        $rawQuery = str_replace('%OPERATION_TYPE%', $operation->getOperationType(), $rawQuery);
        $rawQuery = str_replace('%VARIABLES%', $this->buildVariables($operation->getVariables()), $rawQuery);
        $rawQuery = str_replace('%QUERY%', $this->buildQuery($operation), $rawQuery);

        return $rawQuery;
    }

    private function buildArgs(GraphqlOperation &$operation): string
    {
        $args = $operation->getArguments();
        $variables = array_map(function (Variable $variable) {
            return $variable->getName();
        }, $operation->getVariables());

        if (empty($args)) {
            return '';
        }

        $argsTemplate = $this->getBracedTemplate();
        $argsString = '';

        foreach ($args as $name => $val) {
            $argsString .= ' ' . $name . ': ';

            if (is_array($val)) {
                $argsString .= '{' . $this->buildArgs($val) . ' }';
            } else {
                $argsString .= $this->prepareArgumentValue($val, $variables);
            }
        }

        return str_replace('%DATA%', $argsString, $argsTemplate);
    }

    private function prepareArgumentValue(mixed $arg, iterable $variables): mixed
    {
        $type = gettype($arg);

        if ($type === 'string' && in_array(str_replace('$', '', $arg), $variables)) {
            return $arg; // Variable
        }

        return match ($type) {
            'string' => '"' . $arg . '"',
            'double', 'integer', 'boolean' => $arg,
        };
    }

    private function buildFields(GraphqlOperation &$operation): string
    {
        $fieldsString = '';

        foreach ($operation->getFields() as $field) {
            if ($field instanceof Query) {
                $fieldsString .= ' ' . $this->buildQuery($field);
            } else {
                $fieldsString .= ' ' . $field;
            }
        }

        return $fieldsString;
    }

    private function buildQuery(GraphqlOperation &$operation): string
    {
        $queryTemplate = $this->getQueryTemplate();

        if (is_null($operation->getOperationName())) {
            return $this->buildFields($operation);
        }

        $queryTemplate = str_replace('%ALIAS%', $operation->getAlias() ? $operation->getAlias() . ':' : '', $queryTemplate);
        $queryTemplate = str_replace('%OPERATION_NAME%', $operation->getOperationName() ?? '', $queryTemplate);
        $queryTemplate = str_replace('%ARGUMENTS%', $this->buildArgs($operation), $queryTemplate);
        $queryTemplate = str_replace('%FIELDS%', $this->buildFields($operation), $queryTemplate);

        return $queryTemplate;
    }

    private function buildVariables(iterable $variables): string
    {
        if (empty($variables)) {
            return '';
        }

        $variablesTemplate = $this->getBracedTemplate();
        $variablesString = '';

        $first = true;

        foreach ($variables as $vars) {
            if (!$first) {
                $variablesString .= ',';
            } else {
                $first = false;
            }

            $variablesString .= '$' . $vars->getName() . ': ' . $vars->getType();

            if ($vars->isRequired()) {
                $variablesString .= '!';
            }
        }

        return str_replace('%DATA%', $variablesString, $variablesTemplate);
    }

    private function getTemplate(): string
    {
        return <<<TEMPLATE
        %OPERATION_TYPE% %VARIABLES%{ %QUERY% }
        TEMPLATE;
    }

    private function getQueryTemplate(): string
    {
        return <<<TEMPLATE
        %ALIAS% %OPERATION_NAME% %ARGUMENTS%{%FIELDS% }
        TEMPLATE;
    }

    private function getBracedTemplate(): string
    {
        return <<<TEMPLATE
        ( %DATA% )
        TEMPLATE;
    }
}