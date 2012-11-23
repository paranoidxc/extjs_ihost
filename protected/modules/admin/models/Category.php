<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $name
 * @property integer $lft
 * @property integer $rgt
 * @property string $create_time
 * @property string $update_time
 * @property integer $type
 */
class Category extends CActiveRecord
{
	public $depth;
	public $parent_leaf_id;
	public $parent_leaf;	
	public $is_with_parent_img_size;	
		
  public function __get($name)
  {    
    $getter='get'.$name;
    if(method_exists($this,$getter))
      return $this->$getter();
      
    return parent::__get($name);
  }

  public function ivurl($prefix='node') { 
    if( strlen($this->ident_label) > 0 ) {
      $url = $this->ident_label;
    }else {
      $url = $this->id;      
    }
    return str_replace("//",'/',url($prefix,array('' => $url)));
  }

  public function getContent_Types(){
	  return array(
  	  0 => Yii::t('cp','Album Art Collect'),
    	1 => Yii::t('cp','Include Sub Nodes Content'),
  	  2 => Yii::t('cp','Just Self Content'),
  	  //3 => Yii::t('cp','Outside Category Content'),
  	  4 => Yii::t('cp','Self First Content'),
  	  //5 => 'Outside Single Content',
  	  6 => Yii::t('cp','Internation Link ( URL )'),
  	  7 => Yii::t('cp','Empty'),
	  );
	}
	
  public function getSids() {
    $leafs = Category::model()->findAll( array( 
      'select' => 'id, name',
      'condition'  => ' rgt <= :rgt AND lft >= :lft ',
      'params'    => array( ':rgt' => $this->rgt, ':lft' => $this->lft )
    ) );
    foreach ($leafs as $leaf ) {
      $ids .= $leaf->id.',';
    }
    return $ids;
  }

  public function getUrlArg() {
    if( strlen(trim($this->ident_label)) >0  ) {
      return trim($this->ident_label);
    }
    return $this->id;
  }

  public function getIslove() {
    $category_id  = $this->id;
    $user_id      = User()->id;
    $record = ManyCategoryUser::model()->findByAttributes( 
        array('category_id' => $category_id, 'user_id' => $user_id ) );
    return $record === null ? false : true;
  }
  
  public function getUrl(){      
    switch ( $this->content_type ){
      case 0:
      case 1:
      case 2:
      case 4:
        $r = Yii::app()->urlManager->createUrl('mw/category', array('id' => $this->id) );
        break;
      case 3:
        $r = Yii::app()->urlManager->createUrl('mw/category', array('id' => $this->oct_id) );
        break;
      case 5:
        $r = 'http://www';        
        break;
      case 7:
        $r = '#';
        break;
      case 6: 
        $r = $this->uri;
        break;
      default:
        $r = "http://www.google.com";
    }
    return $r;
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
  
	public function firstArticle()
  {
    $this->articles->getDbCriteria()->mergeWith(array(
      'order' => 'sort_id asc',
      'limit' => 1
      //'condition'=>'CreatedAt BETWEEN :start AND :end',
      //'params'=>array(':start'=>$start, ':end'=>$end)
        ));
    return $this->articles;
  }
    
	
	public function getNavigationBlock($nav,&$r=array()){				
		if( $nav->category ){					
			array_unshift($r, array('name'=>$nav->name, 'id' => $nav->category->id, 'type' => 'category') );
		}else{
			array_unshift($r, array('name'=>$nav->name, 'id' => $nav->id, 'type' => 'nav') );
		}
		if( $nav->parent  ) {
			$this->getNavigationBlock($nav->parent,$r);
		}		
		return $r;
	}
	
	public function getPath($id,$top_id=''){
		$sql = 	" SELECT parent.name, parent.id ".
				 	" FROM category AS node,".
					" category AS parent ".
					" WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
					" AND node.id = $id ";
        if( $top_id != '' ) {
          $record = Category::model()->findByPk( $top_id );
          if( $record != null ) {
					  $sql .= " AND parent.lft  >=  ".$record->lft;
          }
        }
					$sql .= " ORDER BY parent.lft DESC";						
		$parents = $this->findAllBySql($sql);		
		$path = array();
		foreach( $parents as $obj )	{			
				array_push($path, array('name'=>$obj->name, 'id'=>$obj->id, 'type' => 'category'));
		}		
		return $path;
	}
	
	public function directParent($id){
		$sql = 	" SELECT parent.name, parent.id,parent.rgt,parent.lft ".
				 	" FROM category AS node,".
					" category AS parent ".
					" WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
					" AND node.id = $id ".
					" ORDER BY parent.lft ";					
		
		$parents = $this->findAllBySql($sql);		
		$parent;
		foreach( $parents as $obj ) {								
			if( $obj->id  == $id ) {					
				break;
			}
			$parent = $obj;			
		}		
		return $parent;
		
	}
		
	/**
	 * undocumented function
	 *
	 * @return html
	 * @author paranoid
	 **/	
	public function ileafs($opt) {	  
	  $sql =  " SELECT node.* ,  (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth".
				" FROM category AS node, ".
				" category AS parent, ".
				" category AS sub_parent, ".
				" ( ".
				" SELECT node.name, (COUNT(parent.name) - 1) AS depth ".
				" FROM category AS node, ".
				" category AS parent ".
  			" WHERE node.lft BETWEEN parent.lft AND parent.rgt ";
			if( $opt['id'] > 0 ) {
			  $sql .= " AND node.id = '$opt[id]' ";
			}else if( strlen( $opt['ident'] ) > 0  ){
			  $sql .= " AND node.ident = '$opt[ident]' ";
			}else if( strlen( $opt['ident_label'] ) > 0 ){
			  $sql .= " AND node.ident_label = '$opt[ident_label]' ";
			}
			$sql .= " GROUP BY node.name ".
				" ORDER BY node.lft ".
				" ) AS sub_tree ".
				" WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
				" AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt ".
  			" AND sub_parent.name = sub_tree.name ";
  		$sql.= " GROUP BY node.id ";
  		
  		if( $opt['include'] ) {  		  
    		if( $opt['depth'] ) {
    		  $sql .= " HAVING depth >= 0 AND depth <= ".$opt['depth'];
    		}else {
    		  $sql .= " HAVING depth >= 0 ";
  		  }
  		}else{
  		  if( $opt['depth'] ) {
    		  $sql .= " HAVING depth > 0 AND depth <= ".$opt['depth'];
    		}else {
    		  $sql .= " HAVING depth > 0 ";
  		  }  		  
  		}
				//" HAVING depth <= $depth ".
				$sql .= " ORDER BY node.lft, node.sort_id desc ";			
		$leaf = $this->findAllBySql($sql);
		return $leaf;
		
		if( strpos($opt['ident'],',') === false ){		  
		  if( count($leaf) > 0 ) {
		    if( $opt['include'] ) {
		      return $leaf;
	      }else{
	        return $leaf[0];
	      }
		  }
		}else{
		  return $leaf;
		}
	}
	
	
	/**
	 * node function
	 *
	 * @return category obj or category list obj
	 * @author paranoid
	 **/	
	public function ileaf($opt) {	  	  
	  $id     = $opt['id'];
	  $ident  = $opt['ident'];
	  $ident_label  = $opt['ident_label'];
	  $criteria=new CDbCriteria;        
    $is_list = false;
	  if( strlen($id) > 0 ){
	    if( strpos($id,',') === false ){
	      $criteria->condition  = 'id =:id';
      }else{
        $is_list  = true;
        $criteria->condition  = 'find_in_set(id, :id)';
      }
      $criteria->params     = array(':id'=>$id);      
	  }else if( strlen($ident) > 0 ){	    
	    if( strpos($ident,',') === false ){
	      $criteria->condition  = 'ident =:ident';
      }else{
        $is_list  = true;
        $criteria->condition  = 'find_in_set(ident, :ident)';
      }
      $criteria->params     = array(':ident'=>$ident);
	  }else if( strlen($ident_label) > 0 ){	    
	    if( strpos($ident_label,',') === false ){
	      $criteria->condition  = 'ident_label =:ident_label';
      }else{
        $is_list  = true;
        $criteria->condition  = 'find_in_set(ident_label, :ident_label)';
      }
      $criteria->params     = array(':ident_label'=>$ident_label);
    }    
    if( $is_list ){
      return Category::model()->findall( $criteria );  
    }else{
      return Category::model()->find( $criteria );        
    }
	}
	
	
  /*
     deprecated funciton 
	public function vleafs($id=1,$depth=1){
		$sql =  " SELECT node.* ,  (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth".
				" FROM category AS node, ".
				" category AS parent, ".
				" category AS sub_parent, ".
				" ( ".
				" SELECT node.name, (COUNT(parent.name) - 1) AS depth ".
				" FROM category AS node, ".
				" category AS parent ".
				" WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
				" AND node.id = $id ".
				" GROUP BY node.name ".
				" ORDER BY node.lft ".
				" ) AS sub_tree ".
				" WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
				" AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt ".
				" AND sub_parent.name = sub_tree.name ".
				" GROUP BY node.id ".
				" HAVING depth <= $depth ".
				" ORDER BY node.lft, node.sort_id desc ";
		 return $this->findAllBySql($sql);	
	}
  */

	public function articles_to_s() {		
		$r = '';
		foreach( $this->articles as $article ) {
			$r .= $article->title . '<br/>';
		}
		return $r;
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author paranoid
	 **/
	public static function getCategorySelectData() {
		$_data = Category::model()->getTreeById();
		$leafs = CHtml::listdata($_data, 'id','name');
		return $leafs;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTreeById() {
		 $sql = " SELECT node.id AS id, ".
		 	 	" CONCAT( REPEAT('.', COUNT(parent.name) - 1), node.name) AS name ".      			
        		" FROM category AS node,         ".
        		" category AS parent ".
			    " WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
        		" GROUP BY node.id ".
	        	" ORDER BY node.lft ";
	    return $this->findAllBySql($sql);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('partial,memo, album_tpl,list_tpl,topic_tpl,content_type,uri,oct_id,ost_id,ident,
			ident_label,model_type,parent_leaf_id,img_auto_size,is_with_parent_img_size', 'default'),
			array('ident_label','unique','allowEmpty' => true, 'caseSensitive' => false ),
			array('ident',      'unique','allowEmpty' => true, 'caseSensitive' => false ),
			array('name,parent_leaf_id', 'required'),
			array('lft, rgt', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, lft, rgt, create_time, update_time, type', 'safe', 'on'=>'search'),
		);
	}

  public function sub_nodes(){    
    $sub_nodes = Category::model()->findAll( array(
      'condition' => 'lft >= :lft AND rgt <= :rgt ',
      'params'    => array( ':lft' => $this->lft, ':rgt' => $this->rgt )
    ) );        
    $ids = '';
    foreach( $sub_nodes as $n ){        
      $ids .= $n->id.',';
    }      
    return $ids;
  }
  
  public function first($opt=null) {
    $order   = empty($opt['order']) ? ' id DESC ' : $opt['order'];
    $include = empty($opt['include']) ? false : true;
    if( $include ){
      $ids = $this->sub_nodes();
      return Article::model()->find( array(                        
        'condition'=>'find_in_set(category_id,:category_ids)',
        'order'    => $order,
        'params'=>array(':category_ids'=>$ids),
      ) );
    }else{
      return  Article::model()->find(array(    
        'condition'=>'category_id=:category_id',
        'order'    => $order,
        'params'=>array(':category_id'=>$this->id),
      ));
    }
  }
  
  public function last($opt=null) {
    $order   = empty($opt['order']) ? ' id DESC ' : $opt['order'];
    $include = empty($opt['include']) ? false : true;
    if( $include ){
       $ids = $this->sub_nodes();
      return Article::model()->find( array(                        
        'condition'=>'find_in_set(category_id,:category_ids)',
        'order'    => $order,
        'params'=>array(':category_ids'=>$ids),
      ) );
    }else{
      return  Article::model()->find(array(    
        'condition'=>'category_id=:category_id',
        'order'    => $order,
        'params'=>array(':category_id'=>$this->id),
      ));  
    }
  }
  
  public function essays($opt=null){    
    $include = empty($opt['include']) ? false : true;
    $split   = empty($opt['split']) ? false : true;
    $order   = empty($opt['order']) ? ' id DESC ' : $opt['order'];
    $page_size   = empty($opt['page_size']) ? ' 20 ' : $opt['page_size'];
    $criteria=new CDbCriteria;
    $criteria->condition = ' 1=1 ';
    $criteria->order     = $order;
    
    if( $include ){
      $category_ids = $this->sub_nodes();       
      $criteria->condition .= ' AND find_in_set(category_id,:category_ids)';
      $criteria->params[':category_ids'] = $category_ids;
    }else {
      $criteria->condition .= ' AND category_id = :category_ids ';
      $criteria->params[':category_ids'] = $this->id;      
    }
    
    /* find articles in multiple category */
    $mul_category =  ManyCategoryArticle::model()->findAllByAttributes( array('category_id' => $this->id) ); 
    foreach( $mul_category as $_mc ){
      $article_ids .= $_mc->article_id.',';
    }    
    
    if( $article_ids != '' ) {
      $criteria->condition  .= ' OR find_in_set(id,:article_ids)';
      $criteria->params[':article_ids'] = $article_ids;      
    }      
      
    if( $split ){
      $item_count = Article::model()->count($criteria);
      $pages =new CPagination($item_count);       
      $pages->pageVar = empty($opt['page']) ? 'page': $opt['page'];
      $pages->setPageSize($page_size);
      $pagination = new CLinkPager();
      
      $pagination->setPages($pages);    
      $pagination->init();
      //$pagination->run(); // display the html pagination
      $criteria->limit        =  $page_size;
      $criteria->offset       = $pages->offset;        
      $articles = Article::model()->findall( $criteria );       
      
      return array( $articles, $pagination);
    }else{
      if( !empty($opt['limit']) && is_numeric($opt['limit']) ) {
        if( !empty($opt['offset']) && is_numeric($opt['offset']) ) {
          $criteria->offset        =  $opt['offset'];
        }
        $criteria->limit        =  $opt['limit'];
      }
      $articles = Article::model()->findall( $criteria );  
      return $articles;
    }    
  }
  
  public function first_article() {    
    return  Article::model()->find(array(    
          'condition'=>'category_id=:category_id',
          'order'    => 'sort_id asc',
          'params'=>array(':category_id'=>$this->id),
    ));        
  }
  public function last_article(){
    return  Article::model()->find(array(    
          'condition'=>'category_id=:category_id',
          'order'    => 'sort_id desc',
          'params'=>array(':category_id'=>$this->id),
    ));
  }
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		  'forumarticles' 	    => array( self::HAS_MANY,     'Article', 'category_id' , 'order'=>' forumarticles.reply_time DESC '),
		  'forumarticlesCount' 	=> array( self::STAT,         'Article', 'category_id'  ),
			'articles' 	          => array( self::HAS_MANY,     'Article', 'category_id' , 'order'=>' articles.create_time DESC '),
			'attachments'         => array( self::HAS_MANY,     'Attachment', 'category_id' ),			
			'images'              => array( self::HAS_MANY,     'Attachment', 'category_id' ),
      'modelext'            => array( self::BELONGS_TO,   'ModelExt',   'model_id'),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'              => 'ID',
  		'partial'         => Yii::t('cp','Partial'),
			'name'            => Yii::t('cp','Name'),
			'lft'             => Yii::t('cp','Lft'),
			'rgt'             => Yii::t('cp','Rgt'),
			'create_time'     => Yii::t('cp','Create_time'),
			'update_time'     => Yii::t('cp','Update_time'),
  		'ident_label'     => Yii::t('cp','Ident Label'),
			'content_type'    => Yii::t('cp','Node Content Type'),
    	'tpl'             => Yii::t('cp','Tpl'),
      'album_tpl'       => Yii::t('cp','Album Tpl'),
      'list_tpl'        => Yii::t('cp','List Tpl'),
      'topic_tpl'       => Yii::t('cp','Content Tpl'),
      'parent_leaf_id'  => Yii::t('cp','Parent Leaf Id'),
      'uri'             => Yii::t('cp', 'URL'),
      'img_auto_size'   => '图片缩放尺寸',
      'is_with_parent_img_size'   => '使用上级节点图片缩放尺寸',
      'memo'            => "描述",
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

		$criteria->compare('lft',$this->lft);

		$criteria->compare('rgt',$this->rgt);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('update_time',$this->update_time,true);

		//$criteria->compare('type',$this->type);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	
	
	public function leafMoveToAnother($s,$t){
	  $s_leaf = Category::model()->findByPk($s);
    $t_leaf = Category::model()->findByPk($t);         
    if( $s_leaf->id == $t_leaf->id ){      
      return false;
    }
		$width  = $s_leaf->rgt - $s_leaf->lft + 1;
		$pwidth = $t_leaf->rgt - $t_leaf->lft ;
			
		if( $pwidth < $width ) {
			$pwidth = $pwidth + $width;
		}
		
		$width = abs($width);
		$pwidth = abs($pwidth);
			
		$cmodel = Category::model();
		$transaction = $cmodel->dbConnection->beginTransaction();		
    try{
			// step 1: temporary "remove" moving node
	    $sql 	 = " UPDATE category ";
			$sql	.= " SET lft = -lft, rgt = -rgt ";
			$sql	.= " WHERE lft >= $s_leaf->lft AND rgt <= $s_leaf->rgt ";
			//_debug($sql);
			$cmodel->dbConnection->createCommand($sql)->execute(); 	    			

			// step 2: decrease left and/or right position values of currently 'lower' items (and parents)			
    	$sql = " UPDATE category  SET lft = lft  -  $width WHERE lft > $s_leaf->rgt ";	   
    	$cmodel->dbConnection->createCommand($sql)->execute(); 	
	    $sql = " UPDATE category SET rgt = rgt- $width WHERE rgt >  $s_leaf->rgt ";
	    $cmodel->dbConnection->createCommand($sql)->execute();
	    
	    //// step 3: increase left and/or right position values of future 'lower' items (and parents)
			$parent_rgt = $t_leaf->rgt;
			$parent_lft = $t_leaf->lft;
		
		  $t1 = $parent_rgt > $s_leaf->rgt ? $parent_rgt -$width : $parent_rgt;
		  $sql = " UPDATE category SET lft = lft + $width  WHERE lft >= $t1 ";  	    	  
  	  $cmodel->dbConnection->createCommand($sql)->execute();    	    	    	  	
    	$sql = " UPDATE category  SET rgt = rgt +  $width WHERE rgt >=  $t1";
    	$cmodel->dbConnection->createCommand($sql)->execute();	    	    	    	    
    	
    	// step 4 move the temporary "remove" leaf to parent
    	$_lft = $parent_rgt > $s_leaf->rgt ? $parent_rgt - $s_leaf->rgt -1 : $parent_rgt-$s_leaf->rgt -1 + $width;
    	$_rgt = $parent_rgt > $s_leaf->rgt ? $parent_rgt - $s_leaf->rgt -1 : $parent_rgt-$s_leaf->rgt -1 + $width;
    	$sql = " UPDATE category SET lft = -lft + $_lft , rgt = -rgt + $_rgt WHERE lft < 0 ";    	    	
    	//_debug( $sql );
    	$cmodel->dbConnection->createCommand($sql)->execute();
	    $transaction->commit();		
	      
    }catch(Exception $e) {
  	  _debug($e);
    	$transaction->rollBack();
    	return false;
    }
    return true;
	}
	
	public function autoCreate($opt=null){	  
	  $model=new Category();
	  $name = date('Ymd');
	  $category = Category::model()->find('name = :name', array( ':name'=> $name) );
	  if( $category ){
	    return $category->id; 
	  }
	  $model->name = $name;
	  $model->parent_leaf = $parent_leaf = Category::model()->findByPk(ATT_CAT_ID);
	  $cmodel = Category::model();
		$transaction = $cmodel->dbConnection->beginTransaction();				
		try {          
			$leaf_model = Category::model();								
			
			$update_rgt = " UPDATE category SET rgt = rgt + 2 WHERE rgt > $parent_leaf->lft ";
			$cmodel->dbConnection->createCommand($update_rgt)->execute();
      
			$update_lft = " UPDATE category SET lft = lft + 2 WHERE  lft > $parent_leaf->lft ";					
			$cmodel->dbConnection->createCommand($update_lft)->execute();					
		
			$model->lft = $parent_leaf->lft + 1;
			$model->rgt = $parent_leaf->lft + 2;
		
			$model->create_time = date("Y-m-d H:i:s");
		
			$model->save();
			$transaction->commit();	
			
		}catch(Exception $e) {				
			print(" exception ");					
			$transaction->rollBack();
		}
		
		return $model->id;
		
	}
	
}
