<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statistics`.
 */
class m171208_100105_create_statistics_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('statistics', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer(),
            'unique_clicks' => $this->integer(),
            'clicks' => $this->integer(),
            'country_code' => $this->string(),
            'date' => $this->timestamp()->notNull()
        ]);

        $this->addForeignKey(
            'fk-statistics-news_id',
            'statistics',
            'news_id',
            'news',
            'id',
            'CASCADE'
        );
    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-statistics-news_id',
            'statistics'
        );

        $this->dropTable('statistics');

    }
}
