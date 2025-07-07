<?php

namespace app\models;

use \yii\db\ActiveRecord;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'Book',
    description: 'Book Model',
    required: ['title', 'author_id', 'pages', 'language', 'genre', 'description']
)]
class Book extends ActiveRecord
{

    #[OAT\Property(
        property: 'id',
        type: 'integer',
        readOnly: true,
        description: 'Unique identifier of the book'
    )]
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

    public static function tableName()
    {
        return 'book';
    }


    public function rules()
    {
        return [
            [['title', 'author_id', 'pages', 'language', 'genre', 'description'], 'required'],
            [['author_id', 'pages'], 'default', 'value' => null],
            [['author_id', 'pages'], 'integer'],
            [['title', 'language', 'genre', 'description'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'author_id' => 'Author ID',
            'pages' => 'Page Count',
            'language' => 'Language',
            'genre' => 'Genre',
            'description' => 'Description',
        ];
    }
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
