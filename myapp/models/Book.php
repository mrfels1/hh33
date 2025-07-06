<?php

namespace app\models;

use Yii;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property int $pages
 * @property string $language
 * @property string $genre
 * @property string $description
 *
 * @property Author $author
 */
class Book extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
