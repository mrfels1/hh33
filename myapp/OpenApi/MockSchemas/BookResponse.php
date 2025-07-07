<?php

namespace app\models;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'BookResponse',
    description: 'Book Model without id field',
    required: ['title', 'author_id', 'pages', 'language', 'genre', 'description']
)]
class BookResponse
{
    #[OAT\Property(
        property: 'title',
        type: 'string',
        maxLength: 65535,
        description: 'Book title',
        nullable: false
    )]
    #[OAT\Property(
        property: 'author_id',
        type: 'integer',
        description: 'Author of the book',
        nullable: false
    )]
    #[OAT\Property(
        property: 'pages',
        type: 'integer',
        description: 'Number of pages in the book',
        nullable: false
    )]
    #[OAT\Property(
        property: 'language',
        type: 'string',
        maxLength: 65535,
        description: 'Language of the book',
        nullable: false
    )]
    #[OAT\Property(
        property: 'genre',
        type: 'string',
        maxLength: 65535,
        description: 'Genre of the book',
        nullable: false
    )]
    #[OAT\Property(
        property: 'description',
        type: 'text',
        description: 'Short summary of the book'
    )]
    public int $id;
    public string $title;
    public int $author_id;
    public int $pages;
    public string $language;
    public string $genre;
    public string $description;
}
