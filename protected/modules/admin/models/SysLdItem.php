<?php

/**
 * This is the model class for table "sys_ld_item".
 *
 * The followings are the available columns in table 'sys_ld_item':
 * @property integer $id
 * @property string $name
 * @property string $ident
 * @property integer $parent_id
 * @property string $value
 * @property integer $iorder
 * @property integer $status
 * @property string $memo
 */
class SysLdItem extends CActiveRecord
{
	public $_format_name;
	public $format_name;
	public $text;
	/**
	 * Returns the static model of the specified AR class.
	 * @return SysLdItem the static model class
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
		return 'sys_ld_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, iorder, status,ilevel', 'numerical', 'integerOnly'=>true),
			array('name, ident', 'length', 'max'=>100),
			array('value', 'length', 'max'=>255),
			array('name','required'),
			array('ident','unique', 'allowEmpty' => true ),

			array('parent_id', 'compare', 'allowEmpty' => true, 'compareAttribute' => 'id', 'operator' => '!=', 'strict' => true,'message' =>' 上级类别不能为自己本身' ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, ident, parent_id, value, iorder, status, memo', 'safe', 'on'=>'search'),
		);
	}	

	private function _ldtree($list,&$r,&$condition='')
	{
		foreach( $list as $_inst ){			
			$_inst->format_name = '|--'.str_repeat('-', $_inst->ilevel*2).$_inst->name;
			$r[] = $_inst;
			if( $condition == '' ){
				$subs = $_inst->subs();
			}else {				
				$_condition = str_replace('t.','subs.',$condition);				
				$subs = $_inst->subs( array('condition'=> $_condition ) );
			}			
			if( $subs ) {
				$this->_ldtree($subs,$r,$condition);
			}
		}
		return $r;
	}

	public function ldtree($ident= '', $condition = '')
	{
		$r = '';		
		$criteria = new CDbCriteria();  
		$criteria->condition = '';
		$criteria->order = ' t.iorder DESC ';	
		if( strlen($ident) > 0 ) {
			$criteria->addCondition(" t.ident = :ident");
			$criteria->params[':ident'] = $ident;
		}else {			
			$criteria->addCondition(" t.parent_id = :parent_id");
			$criteria->params[':parent_id'] = 0;
		}
		if( $condition == '' ){			
			$criteria->with = array('subs');
		}else {			
			$criteria->addCondition( $condition );			
			$criteria->with = array('subs' => array('condition' => $condition) );
		}		
		$list = SysLdItem::model()->findall($criteria);
		foreach( $list as $_inst ){				
			$_inst->format_name = '|--'.str_repeat('-', $_inst->ilevel*2).$_inst->name;
			$r[] = $_inst;
			if( $_inst->subs ) {				
				$this->_ldtree($_inst->subs, $r, $condition);
			}
		}
		if( strlen($ident) > 0 ){
			array_shift($r);
		}
		return $r;
	}
	public function valid_ldtree()
	{		
		return $this->ldtree('',' t.status = 0 ');
	}

	public function ident_ldtree($ident)
	{		
		return $this->ldtree(trim($ident),' t.status = 0 ');
	}

	public function make_level()
	{
		if( $this->parent ){
			$this->ilevel = $this->parent->ilevel + 1;
			$this->save(false);
		}else {
			$this->ilevel = 0;
			$this->save(false);
		}
		foreach( $this->subs as $_inst ){
			$_inst->make_level();
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'subs' => array(self::HAS_MANY, 'SysLdItem', 'parent_id','order' => 'subs.iorder DESC' ),
			'parent'=> array(self::BELONGS_TO,'SysLdItem','parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(		
			'id' => 'ID',
			'name' => '名称',
			'memo' => '备注',
			'parent_id' => '上级',
			'iorder' => '排序值',
			'status' => '状态',
			'value' => '自定义值',
			'ident' => '唯一标识',
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

		$criteria->compare('id',$this->id);

		$criteria->compare('name',$this->name,true);

		$criteria->compare('ident',$this->ident,true);

		$criteria->compare('parent_id',$this->parent_id);

		$criteria->compare('value',$this->value,true);

		$criteria->compare('iorder',$this->iorder);

		$criteria->compare('status',$this->status);

		$criteria->compare('memo',$this->memo,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}