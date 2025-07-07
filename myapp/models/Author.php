<?php

namespace app\models;

use Yii;

use \yii\db\ActiveRecord;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'Author',
    description: 'Author Model',
    required: ['name', 'birth_year', 'country']
)]
class Author extends ActiveRecord
{

    #[OAT\Property(
        property: 'id',
        type: 'integer',
        readOnly: true,
        description: 'Unique identifier of the author'
    )]

    #[OAT\Property(
        property: 'name',
        type: 'string',
        maxLength: 65535,
        description: 'Name of the author'
    )]

    #[OAT\Property(
        property: 'birth_year',
        type: 'integer',
        description: 'Year the author was born'
    )]

    #[OAT\Property(
        property: 'country',
        type: 'string',
        maxLength: 65535,
        description: 'Country of the author'
    )]

    public static function tableName()
    {
        return 'author';
    }


    public function rules()
    {
        return [
            [['name', 'birth_year', 'country'], 'required'],
            [['birth_year'], 'default', 'value' => null],
            [['birth_year'], 'integer'],
            [['name', 'country'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'birth_year' => 'Birth Year',
            'country' => 'Country',
        ];
    }


    public function getBooks()
    {
        return $this->hasMany(Book::class, ['author_id' => 'id']);
    }
}
