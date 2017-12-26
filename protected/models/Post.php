<?php

/**
 * This is the model class for table "posts_table".
 *
 * The followings are the available columns in table 'posts_table':
 * @property integer $id
 * @property string $author
 * @property string $title
 * @property string $content
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class PostsTable extends CActiveRecord {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'posts_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		/*return array(
	            array('author, title, content, created_at, updated_at', 'required'),
	            array('category_id, created_at, updated_at', 'numerical', 'integerOnly'=>true),
	            array('author, title', 'length', 'max'=>255),
	            // The following rule is used by search().
	            // @todo Please remove those attributes that should not be searched.
	            array('id, author, title, content, category_id, created_at, updated_at', 'safe', 'on'=>'search'),
*/
		return array(
			array('author, title, content', 'required'),
			array('category_id', 'numerical', 'integerOnly' => true),
			array('author, title', 'length', 'max' => 255),
			array('title', 'checkUniqueness'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, author, title, category_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'CommentsTable', 'post_id'),
			'category' => array(self::BELONGS_TO, 'CategoryTable', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'author' => 'Author',
			'title' => 'Title',
			'content' => 'Content',
			'category_id' => 'Category',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
			'comments' => 'Comments',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('author', $this->author, true);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('category_id', $this->category_id);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PostsTable the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function primaryKey() {
		return 'id';
	}

	public function beforeSave() {
		if ($this->isNewRecord) {
			$this->created_at = time();
		}
		$this->updated_at = time();
		return parent::beforeSave();
	}

	public function updateColumns($column_value_array) {
		$column_value_array['updated_at'] = time();
		foreach ($column_value_array as $column_name => $column_value) {
			$this->$column_name = $column_value;
		}
		$this->update(array_keys($column_value_array));
	}

	public static function create($attributes) {
		$model = new PostsTable;
		$model->attributes = $attributes;
		$model->save();
		return $model;
	}

	public function checkUniqueness($attribute, $params) {
		$model = PostsTable::model()->find('title=:title', array(':title' => $this->title));
		if ($model != null) {
			$this->addError('title', "This Title already exists");
		}
	}
}