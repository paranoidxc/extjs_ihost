<?php

/**
 * This is the model class for table "model_ext_field".
 *
 * The followings are the available columns in table 'model_ext_field':
 * @property string $id
 * @property integer $model_id
 * @property integer $e_type
 * @property string $field_name
 * @property string $display_name
 * @property string $tip
 * @property string $default_value
 * @property string $config
 * @property integer $sort
 * @property integer $is_blank
 * @property integer $status
 */
class ModelExtField extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ModelExtField the static model class
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
		return 'model_ext_field';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model_id, e_type, field_name, display_name, tip, default_value, config,real_field_name', 'default'),
			array('model_id, e_type, sort, is_blank, status', 'numerical', 'integerOnly'=>true),
			array('field_name, display_name, tip, default_value', 'length', 'max'=>255),
			array('model_id,field_name,display_name,tip','required'),
			array('real_field_name','unique', 'criteria'=> array( 'model_id' =>$this->model_id )  ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, model_id, e_type, field_name, display_name, tip, default_value, config, sort, is_blank, status', 'safe', 'on'=>'search'),
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
			'model_id' => 'Model',
			'e_type' => 'E Type',
			'field_name' => 'Field Name',
			'display_name' => 'Display Name',
			'tip' => 'Tip',
			'default_value' => 'Default Value',
			'config' => 'Config',
			'sort' => 'Sort',
			'is_blank' => 'Is Blank',
			'status' => 'Status',
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
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('e_type',$this->e_type);
		$criteria->compare('field_name',$this->field_name,true);
		$criteria->compare('display_name',$this->display_name,true);
		$criteria->compare('tip',$this->tip,true);
		$criteria->compare('default_value',$this->default_value,true);
		$criteria->compare('config',$this->config,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('is_blank',$this->is_blank);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}