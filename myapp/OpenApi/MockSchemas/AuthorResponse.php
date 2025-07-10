<?php

namespace app\models;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'AuthorResponse',
    description: 'Автор без поля id',
    required: ['name', 'birth_year', 'country']
)]
class AuthorResponse
{
    #[OAT\Property(
        property: 'name',
        type: 'string',
        maxLength: 65535,
        description: 'Name of the author'
    )]
    public string $name;

    #[OAT\Property(
        property: 'birth_year',
        type: 'integer',
        description: 'Year the author was born'
    )]
    public int $birth_year;

    #[OAT\Property(
        property: 'country',
        type: 'string',
        description: 'Country of the author'
    )]
    public string $country;
}
