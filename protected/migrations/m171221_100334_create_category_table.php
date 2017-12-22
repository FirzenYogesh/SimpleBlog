<?php

class m171221_100334_create_category_table extends CDbMigration
{
	
	public function safeUp()
	{
		$this->createTable('category_table', array(
			'id'=>'pk',
			'category_name'=>'string NOT NULL',
			'created_at'=>'integer NOT NULL',
			'updated_at'=>'integer NOT NULL',
		));
	}

	public function safeDown()
	{
		$this->dropTable('category_table');
	}
	
}