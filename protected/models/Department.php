<?php

/**
 * This is the model class for table "department".
 *
 * The followings are the available columns in table 'department':
 * @property string $id
 * @property string $name
 * @property string $memo
 * @property integer $p_id
 */
class Department extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Department the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'department';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('memo', 'required'),
			array('p_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>90),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, memo, p_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'memo' => 'Memo',
			'p_id' => 'P',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('p_id',$this->p_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}


	public function tree($pid=0)
	{
		$list = self::FullTree(0);
		return $list;
	}

	public function FullTree($p_id = 0)
	{						
		$models = Department::model()->findAll( "p_id = :p_id ", array(":p_id" => $p_id) );
		$list = array();
		foreach( $models as $model ){
			$sub = self::FullTree($model->id);
			$_t['id'] = $model->id;
			$_t['text'] = $model->name;
			$_t['title'] = $model->name;
			$_t['memo'] = $model->memo;			
			$_t['abc'] 	= 'fffffffff';
			$_t['children'] = $sub;
			$list[] = $_t;
		}	
    return $list;
	}

}