<?php

use yii\db\Migration;

class m250705_123624_create_fk_author_book extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-book-author_id', '{{%book}}', 'author_id', '{{%author}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-book-author_id', '{{%book}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250705_123624_create_fk_author_book cannot be reverted.\n";

        return false;
    }
    */
}
