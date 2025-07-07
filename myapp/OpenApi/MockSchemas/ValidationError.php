<?php

namespace app\models;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'ValidationError',
    description: 'Ответ ошибки валидации',
)]
class ValidationError
{
    #[OAT\Property(
        property: 'errors',
        type: 'object',
        additionalProperties: new OAT\AdditionalProperties(
            type: 'array',
            items: new OAT\Items(type: 'string')
        ),
        description: 'Список ошибок по каждому полю'
    )]
    public array $errors;
}
