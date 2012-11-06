<?php

/**
 * This is the model class for table "attachment".
 *
 * The followings are the available columns in table 'attachment':
 * @property integer $id
 * @property string $screen_name
 * @property string $path
 * @property integer $w
 * @property integer $h
 */
class Attachment extends CActiveRecord
{    
	/**
	 * Returns the static model of the specified AR class.
	 * @return Attachment the static model class
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
		return 'attachment';
	}
  
	public function __get($name){    
    $getter='get'.$name;
    if(method_exists($this,$getter))
      return $this->$getter();
      
    return parent::__get($name);
  }

  public function getV_download() {
    return UPFILES_DIR.'/'.$this->path.'.'.$this->extension;
  }

  public function getV_ext_image() {
    $r = '/default_image/'.strtolower($this->extension).'.png';
    if( file_exists( WEBSITE_DIR.$r ) ) {
      return $r;
    }else {
      return '/default_image/unknown.png';
    }
  }
  
  public function getImageRange( $opt=array() ){
    //$ele = $opt['type'];
    //$ele_id = $opt['type_id'];
    $str  = "<select class='dN' >";
    $tips = explode(',',$this->tips);
    foreach($tips as $tip ){
      if( strlen( trim($tip) ) > 0 ){
        $tip=str_replace('_','',$tip);
        $str .= "<option value='_".str_replace('*','_',$tip)."' >".$tip."</option>";
      }
    }
    $str .= "</select>";
    return $str;
  }
  
  public function is_image(){
    return in_array(strtolower($this->extension),API::$IMAGE_EXTENSION);
  }
  
  public function getCimage($opt){
    if( strpos($opt,'_') === false ){
      
    }else{
      return UPFILES_DIR.'/'.$this->path.'_'.$opt.'.'.$this->extension;
    }
  }
  
  public function getimage(){
    //return UPFILES_DIR.'/'.$this->path.'.'.$this->extension;        
    return UPFILES_DIR.'/'.$this->path.'_'.$this->w.'_'.$this->h.'.'.$this->extension;
  }
  
  public function getlarge(){
    return UPFILES_DIR.'/'.$this->path.'_'.LARGE_SIZE.'.'.$this->extension;
  }
  public function getPhoto_size(){
    return UPFILES_DIR.'/'.$this->path.'_'.PHOTO_SIZE.'.'.$this->extension;
  }
  public function getgavatar(){
    return UPFILES_DIR.'/'.$this->path.'_'.GAVATAR_SIZE.'.'.$this->extension;
  }
  public function getthumb(){
    //return '/upfiles/1293301237.jpg';
    //Yii::app()->request->baseUrl.'/upfiles/'.$t->path."
    //echo UPFILES_DIR.'/'.$this->path.'.'.$this->extension;
    return UPFILES_DIR.'/'.$this->path.'_'.THUMB_SIZE.'.'.$this->extension;
  }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		  array('category_id,extension,memo,tips,c_time,user_id', 'default'),
			array('screen_name, path, w, h', 'required'),
			array('w, h', 'numerical', 'integerOnly'=>true),
			array('screen_name, path', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, screen_name, path, w, h', 'safe', 'on'=>'search'),
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
			'id'          => 'ID',
			'screen_name' => Yii::t('cp','Screen Name'),
			'memo'        => Yii::t('cp','Memo'),
			'path'        => 'Path',
			'w'           => Yii::t('cp','Width'),
			'h'           => Yii::t('cp','Height'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
	}
}
