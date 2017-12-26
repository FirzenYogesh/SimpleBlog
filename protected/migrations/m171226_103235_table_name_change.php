<?php

class m171226_103235_table_name_change extends CDbMigration {

	public function safeUp() {
		$this->renameTable('posts_table', 'post');
		$this->renameTable('category_table', 'category');
		$this->renameTable('comments_table', 'comment');
	}

	public function safeDown() {
		$this->renameTable('post', 'post_table');
		$this->renameTable('category', 'category_table');
		$this->renameTable('comment', 'comments_table');
	}

}