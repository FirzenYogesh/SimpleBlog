<?php

class m171221_100248_create_posts_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('posts_table', array(
			'id'=>'pk',
			'author'=>'string NOT NULL',
			'title'=>'string NOT NULL UNIQUE',
			'content'=>'text NOT NULL',
			'category_id'=>'integer',
			'created_at'=>'integer NOT NULL',
			'updated_at'=>'integer NOT NULL'
		));
	}

	public function safeDown()
	{
		$this->dropTable('posts_table');
	}
}