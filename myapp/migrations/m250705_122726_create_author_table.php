<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m250705_122726_create_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'birth_year' => $this->integer()->notNull(),
            'country' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author}}');
    }
}
