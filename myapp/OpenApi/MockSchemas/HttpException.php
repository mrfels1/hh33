<?php

namespace app\models;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'HttpException',
    description: 'HttpException',
)]
class HttpException
{

    #[OAT\Property(
        property: 'name',
        type: 'string',
        description: 'Краткое название ошибки'
    )]
    public string $name;

    #[OAT\Property(
        property: 'message',
        type: 'string',
        description: 'Сообщение об ошибке'
    )]
    public string $message;

    #[OAT\Property(
        property: 'code',
        type: 'integer',
        description: 'Код внутренней ошибки'
    )]
    public int $code;

    #[OAT\Property(
        property: 'status',
        type: 'integer',
        description: 'HTTP-статус ответа'
    )]
    public int $status;

    #[OAT\Property(
        property: 'type',
        type: 'string',
        description: 'Тип исключения'
    )]
    public string $type;
}
