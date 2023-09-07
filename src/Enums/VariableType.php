<?php

namespace GraphQL\Enums;

enum VariableType: string
{
    case String = 'String';
    case Int = 'Int';
    case Float = 'Float';
    case Boolean = 'Boolean';
    case ID = 'ID';
}