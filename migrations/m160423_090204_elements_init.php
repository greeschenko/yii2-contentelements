<?php

use yii\db\Migration;

class m160423_090204_elements_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%elements}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'urld' => $this->string(),
            'user_id' => $this->integer()->notNull(),
            'parent' => $this->integer()->notNull()->defaultValue(0),
            'preview' => $this->text(),
            'content' => $this->text(),
            'tags' => $this->string(),
            'meta_title' => $this->string(),
            'meta_descr' => $this->string(),
            'meta_keys' => $this->string(),
            'atachments' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'type' => $this->smallInteger()->notNull()->defaultValue(1),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%elements}}');
    }
}
