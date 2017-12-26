<?php

class m171226_105039_table_columns_rename extends CDbMigration {
	public function safeUp() {
		$this->renameColumn('category', 'category_name', 'name');
	}

	public function safeDown() {
		$this->renameColumn('category', 'name', 'category_name');
	}
}