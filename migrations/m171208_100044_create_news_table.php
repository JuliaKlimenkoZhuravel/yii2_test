<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m171208_100044_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'image' => $this->string(1000)->notNull(),
            'text' => $this->string(2000)->notNull(),
            'author' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->defaultValue(null)
        ]);
    }
    public function relations()
    {
        return array(
            'statistics'=>array(self::HAS_MANY, 'Statistics', 'news_id'),

        );
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('subscriber');
    }
}
