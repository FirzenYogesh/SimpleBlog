<?php

/**
 * This is the model class for table "comments_table".
 *
 * The followings are the available columns in table 'comments_table':
 * @property integer $id
 * @property string $author
 * @property string $comment
 * @property integer $post_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommentsTable extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'comments_table';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('author, comment, created_at, updated_at', 'required'),
            array('post_id, created_at, updated_at', 'numerical', 'integerOnly'=>true),
            array('author, comment', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, author, comment, post_id, created_at, updated_at', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array('post'=>array(self::HAS_ONE,'PostsTable','id'));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'author' => 'Author',
            'comment' => 'Comment',
            'post_id' => 'Post',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('author',$this->author,true);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('post_id',$this->post_id);
        $criteria->compare('created_at',$this->created_at);
        $criteria->compare('updated_at',$this->updated_at);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CommentsTable the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave() {
        if($this->isNewRecord) { 
            $this->created_at = time();
        }
        $this->updated_at = time();
        return parent::beforeSave();
    }

    public function updateColumns($column_value_array) {
        $column_value_array['updated_at'] = time();
        foreach($column_value_array as $column_name => $column_value)
            $this->$column_name = $column_value;
        $this->update(array_keys($column_value_array));
    }

    public static function create($attributes) {
        $model = new CommentsTable;
        $model->attributes = $attributes;
        $model->save();
        return $model;
    }
}