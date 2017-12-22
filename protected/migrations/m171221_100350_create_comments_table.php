<?php

class m171221_100350_create_comments_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('comments_table', array(
			'id'=>'pk',
			'author'=>'string NOT NULL',
			'comment'=>'string NOT NULL',
			'post_id'=>'integer',
			'created_at'=>'integer NOT NULL',
			'updated_at'=>'integer NOT NULL'
		));
	}

	public function safeDown()
	{
		$this->dropTable('comments_table');
	}
}