<?php

/**
 * This is the model class for table "model_ext_field_data".
 *
 * The followings are the available columns in table 'model_ext_field_data':
 * @property string $id
 * @property string $field_1
 */
class ModelExtFieldData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ModelExtFieldData the static model class
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
		return 'model_ext_field_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('field_1,field_2,field_3,field_4,field_5,field_6,field_7,field_8,field_9', 'length', 'max'=>255),
			array('field_2,field_3,field_5', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, field_1', 'safe', 'on'=>'search'),
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
			'field_1' => 'Field 1',
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
		$criteria->compare('field_1',$this->field_1,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}