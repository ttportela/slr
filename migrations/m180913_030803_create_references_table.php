<?php

use yii\db\Migration;

/**
 * Handles the creation of table `references`.
 */
class m180913_030803_create_references_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reference', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'active' => $this->integer()->defaultValue(1)->notNull(),
            'bib' => $this->text(),
            'base' => $this->string(),
            'title' => $this->string(),
            'author' => $this->string(),
            'citation' => $this->text(),
            'year' => $this->integer(),
            'publisher' => $this->string(),
            'abstract' => $this->text(),
            'description' => $this->text(),
            'threat' => $this->text(),
            'conclusion' => $this->text(),
            'data' => $this->text(),
            'comment' => $this->text(),
            'exclusion' => $this->text(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-reference-user_id',
            'reference',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-reference-user_id',
            'reference',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reference');
    }
}
