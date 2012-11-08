<?php

/**
 * This is the model class for table "article".
 *
 * The followings are the available columns in table 'article':
 * @property integer $id
 * @property string $title
 * @property string $subtitle
 * @property string $desc
 * @property string $content
 * @property string $create_datetime
 * @property string $update_datetime
 * @property integer $sort_id
 * @property integer $category_id
 */
class Article extends CActiveRecord
{    
  /*
  function behaviors() {
    return array(
        'tags' => array(
            'class' => 'ext.yiiext.behaviors.model.taggable.ETaggableBehavior',
            // Table where tags are stored
            'tagTable' => 'tag',
            // Cross-table that stores tag-model connections.
            // By default it's your_model_tableTag
            'tagBindingTable' => 'ArticleTag',
            // Foreign key in cross-table.
            // By default it's your_model_tableId
            'modelTableFk' => 'articleId',
            // Tag table PK field
            'tagTablePk' => 'id',
            // Tag name field
            'tagTableName' => 'name',
            // Tag counter field
            // if null (default) does not write tag counts to DB
            'tagTableCount' => 'count',
            // Tag binding table tag ID
            'tagBindingTableTagId' => 'tagId',
            // Caching component ID.
            // false by default.
            'cacheID' => 'cache',
 
            // Save nonexisting tags.
            // When false, throws exception when saving nonexisting tag.
            'createTagsAutomatically' => true,
 
            // Default tag selection criteria
            //'scope' => array(
            //    'condition' => ' t.user_id = :user_id ',
            //    'params' => array( ':user_id' => Yii::app()->user->id ),
            //),
 
            // Values to insert to tag table on adding tag
            //'insertValues' => array(
            //    'user_id' => Yii::app()->user->id,
            //),
        )
    );
  }
  */


  public function __get($name)
  {    
    $getter='get'.$name;
    if(method_exists($this,$getter))
      return $this->$getter();
      
    return parent::__get($name);
  }  

  public $content;

  /*
  public function getContent() {
    return $this->ibody->icontent;
  }
  
  public function setContent() {
  }
  */

  public function getPage($page) {
    return API::content_by_page($this->content,$page);
  }
    
  public function userFav () {
	  return UserFavThing::model()->findByAttributes( array('user_id' => User()->id, 'type' =>2, 'model_id' => $this->id ) );	  
	}
	
  public function getV_tags() {    
    $tags = $this->getTags();
    return join(' ',$tags);    
  }
  
  public function getScontent(){
    return ereg_replace('<script.*</script>', '', $this->content);  
  }
  public function getClearContent() {
    return strip_tags($this->ibody->icontent);
  }

  public function getUrlArg() {
    if( strlen(trim($this->ident_label)) >0  ) {
      return trim($this->ident_label);
    }
    return $this->id;
  }
  
  public function getV_url() {
    if( strlen($this->ident_label) > 0 ) {
      $url = $this->ident_label;
    }else {
      $url = $this->id;      
    }
    return str_replace("//",'/',url('article',array('' => $url)));
  }

  public function iturl($prefix='page') {
    return str_replace("//",'/',url('topic/index',array('id' => $this->id)));
  }

  public function ivurl($prefix='page') {
    if( strlen($this->ident_label) > 0 ) {
      $url = $this->ident_label;
    }else {
      $url = $this->id;      
    }
    return str_replace("//",'/',url($prefix,array('' => $url)));
  }
  
  public function iprint() {    
    echo '<table style="border-collapse:collapse; border: 1px solid #4A525A; font-size: 12px;">';
    echo '<tr>';
    foreach( $this->attributes as $k => $v ){      
      echo '<th style="border: 1px solid #4A525A; text-align:left;">'.$k.'</th>';      
    }
    echo '</tr>';
    echo '<tr>';
    foreach( $this->attributes as $k => $v ){    
      if( $k == 'content'){
        echo '<td style="border: 1px solid #4A525A; text-align: left;">'.cnSub($v,100).'</td>';
      }else{
        echo '<td style="border: 1px solid #4A525A; text-align: left;">'.$v.'</td>';
      }
    }
    echo '</table>';
  }
  
  public function getPrev($opt=null) {
    $order_name   = empty($opt['order_name']) ? ' id ' : $opt['order_name'];
    $node_id = empty($opt['node_id']) ? '' : $opt['node_id'];      
    if( $node_id == '' ){            
      $_article = self::model()->find(array(
        'condition' => 'category_id=:category_id and sort_id < :sort_id',
        'order'     => 'sort_id desc',
        //'order'     => $order,
        'params'   => array( ':category_id' => $this->category_id, ':sort_id' => $this->sort_id) ));
      return $_article;
    }else{
      $node = Category::model()->findByPK($node_id);      
      $sub_nodes = Category::model()->findAll( array(
        'condition' => 'lft >= :lft AND rgt <= :rgt ',
        'params'    => array( ':lft' => $node->lft, ':rgt' => $node->rgt )
      ) );        
      $node_ids = '';
      foreach( $sub_nodes as $n ){        
        $node_ids .= $n->id.',';
      }      
      if( $node_ids != '' ){            
        
        $con=new CDbConnection(Yii::app()->db->connectionString, Yii::app()->db->username ,Yii::app()->db->password);
        $con->active = true;        
        $command=$con->createCommand("SET @num=0;");
        $command->execute();
        $find_current_virtual_number = " SELECT  number FROM ( 
                 SELECT @num := @num + 1 AS number, id,  title, sort_id FROM  article WHERE  FIND_IN_SET(category_id,'$node_ids') 
                  ORDER BY $order_name DESC 
                ) AS tbl 
                WHERE 
            id = $this->id";          
        /*$find_current_virtual_number = " SELECT  number FROM ( 
                 SELECT @num := @num + 1 AS number, id,  title, sort_id FROM  article WHERE  FIND_IN_SET(category_id,'$node_ids') 
                  ORDER BY sort_id DESC,create_time DESC
                ) AS tbl 
                WHERE 
            id = $this->id";          
            */
        $command=$con->createCommand($find_current_virtual_number);
        $row = $command->queryRow();
        $current_number = $row['number'];
        
        $command=$con->createCommand("SET @num=0;");
        $command->execute();
        $find_next_sql = "SELECT  number, id,  title, sort_id  
                          FROM ( 
                            SELECT @num := @num + 1 AS number, id,  title, sort_id FROM  article WHERE  FIND_IN_SET(category_id,'$node_ids') 
                            ORDER BY $order_name DESC
                            ) AS tbl 
                          WHERE number > $current_number ORDER BY number asc LIMIT 1 ";
        $command=$con->createCommand($find_next_sql);
        $row = $command->queryRow();
        if( count($row) > 0 ){
          return Article::model()->findByPk($row['id']);
        }
      }
      return array();
    }
  }
  
  public function getNext( $opt=null ) {        
    $order_name   = empty($opt['order_name']) ? ' id ' : $opt['order_name'];
    $node_id = empty($opt['node_id']) ? '' : $opt['node_id'];
    
    if( $node_id == '' ){
      $_article = self::model()->find(array(
        'condition' => 'category_id=:category_id and sort_id > :sort_id',
        'order'     => 'sort_id asc',
        'params'   => array( ':category_id' => $this->category_id, ':sort_id' => $this->sort_id) ));    
      return $_article;
    }else{
      
      $node = Category::model()->findByPK($node_id);      
      $sub_nodes = Category::model()->findAll( array(
        'condition' => 'lft >= :lft AND rgt <= :rgt ',
        'params'    => array( ':lft' => $node->lft, ':rgt' => $node->rgt )
      ) );        
      $node_ids = '';
      
      foreach( $sub_nodes as $n ){        
        $node_ids .= $n->id.',';
      }
      if( $node_ids != '' ){
        $con=new CDbConnection(Yii::app()->db->connectionString, Yii::app()->db->username ,Yii::app()->db->password);
        $con->active = true;        
        $command=$con->createCommand("SET @num=0;");
        $command->execute();
        $find_current_virtual_number = " SELECT  number FROM ( 
                 SELECT @num := @num + 1 AS number, id,  title, sort_id FROM  article WHERE  FIND_IN_SET(category_id,'$node_ids') 
                  ORDER BY $order_name DESC
                ) AS tbl 
                WHERE 
            id = $this->id";      
        $command=$con->createCommand($find_current_virtual_number);
        $row = $command->queryRow();
        $current_number = $row['number'];
        $command=$con->createCommand("SET @num=0;");
        $command->execute();        
        $find_next_sql = "SELECT  number, id,  title, sort_id  
                          FROM ( 
                            SELECT @num := @num + 1 AS number, id,  title, sort_id FROM  article WHERE  FIND_IN_SET(category_id,'$node_ids') 
                            ORDER BY $order_name DESC
                            ) AS tbl 
                          WHERE number < $current_number ORDER BY number DESC LIMIT 1 ";
        $command=$con->createCommand($find_next_sql);
        $row = $command->queryRow();
        if( count($row) > 0 ){
          return Article::model()->findByPk($row['id']);
        }          
      }
      return array();
    }
  }
  
  public function scopes(){      
    return array(
      'most_page_view' => array(
        'order' => ' pv DESC ',
        'limit' => '10',
      ),
      'first' => array(
        'order' => ' create_time DESC ',
        'limit' => 1
      ),
      'latest'  => array(
        'condition' => 'reply_count <=5 ',
        'order' => 'update_time DESC',
        'limit' => '10',
      ),

    );            
  }
	/**
	 * Returns the static model of the specified AR class.
	 * @return Article the static model class
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
		return 'article';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attachment_id, gallery_id,content,is_star,rich,tpl,pv,seo_keywords,
        seo_description,user_id,reply_count,reply_time,allow_reply,is_lock,is_mute,is_del,is_sticky,page_count','default'),
      array('ident_label','unique','allowEmpty' => true, 'caseSensitive' => false ),
			array('title, category_id', 'required'),
			array('sort_id, category_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			array('link', 'length', 'max'=>255),
			array('desc', 'default'),			
			array('create_time, update_time', 'safe'),
			
			array('content', 'required', 'on' => 'forum' ),

			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, subtitle, desc, content, create_time, update_time, sort_id, category_id', 'safe', 'on'=>'search'),
		);
	}

  public function getV_latest_posts() {
    $count = $this->posts_count;
    if( $count > 5 ) {
      $offset = $count -5;
      return $this->latest_posts( array('order'=>'c_time ASC ','offset'=> $offset, 'limit'=> 5) ) ;
    }else {
      return $this->latest_posts;
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
			'leaf' 			   => array( self::BELONGS_TO , 'Category', 	'category_id' ),
			'attachment'	 => array( self::BELONGS_TO,  'Attachment',	'attachment_id'),
			'gallery'		   => array( self::BELONGS_TO,  'Category',	'gallery_id'),
			'auther'       => array( self::BELONGS_TO, 'User', 'user_id'),
      'categorys'    => array( self::MANY_MANY, 'Category', 'many_category_article(article_id,category_id)' ),
      'posts_count'  => array( self::STAT, 'Post', 'article_id' ),
      'latest_posts' => array( self::HAS_MANY, 'Post', 'article_id', 'order' => 'c_time DESC','limit' => 5 ),
      'posts'        => array( self::STAT, 'Post', 'article_id' ),
      
      'ibody'        => array( self::HAS_ONE, 'Ibody', 'itype_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'          => Yii::t('cp','ID'),
			'title'       => Yii::t('cp','Title'),
			'subtitle'    => Yii::t('cp','Subtitle'),
			'desc'        => Yii::t('cp','Desc'),			
			'content'     => Yii::t('cp','Content'),
			'create_time' => Yii::t('cp','Create_time'),
			'update_time' => Yii::t('cp','Update_time'),
			'sort_id'     => Yii::t('cp','Sort'),
			'tpl'         => Yii::t('cp','Tpl'),
			'link'        => Yii::t('cp','Link'),
			'pv'          => Yii::t('cp','Pv'),
			'v_tags'      => '标签',
			'category_id' => Yii::t('cp','Category'),
			'is_star'     => Yii::t('cp','stared?'),
  		'seo_keywords'      => Yii::t('cp', 'Seo Keywords'),
  		'seo_description'   => Yii::t('cp', 'Seo Description'),
  		'allow_reply'       => Yii::t('cp', 'Allow Reply'),
  		'user_id'           => Yii::t('cp', 'User Name'),
  		'ident_label'       => Yii::t('cp', 'Ident Label'),
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
