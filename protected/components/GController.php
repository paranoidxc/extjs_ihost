<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class GController extends Controller
{
  /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	public $_model;
  public $tpl_params;
  public $path;
  public $back_url;

	public function init() {	  	  
    /*
	  if( !Yii::app()->user->isGuest ){
	    if( !Yii::app()->user->getState('current_user') ){
	      Yii::app()->user->setState('current_user', User::model()->findbyPk(Yii::app()->user->id ));
	    }	   	    
	    $u =Yii::app()->user->getState('current_user');		    
	    if( !$u->account_type ) {
        echo '非管理员帐号...';
	      exit; 
	      $this->redirect( array( '/' ));
	      exit; 
	    }
    }    
    */
	  parent::init();
	}
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
		/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id'])){
  			$controllerId = $this->controllerId;
	  		$imodel = new $controllerId;
				$this->_model=$imodel->findbyPk($_GET['id']);  
			}			
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

  public function actionLeaf_update() {

    if( !isset($_GET['rem']) ){
      $back_url = $_POST['back_url'] ? $_POST['back_url'] : rurl();
      if( stripos( $back_url,'create') == false && stripos( $back_url,'update') == false ) {
        setState('back_url', $back_url);
      }
    }

    $action      =& $_GET['action'];
    $cur_leaf_id =& $_GET['cur_leaf_id'];
    $top_leaf_id =& $_GET['top_leaf_id'];
    $top_leaf    =& Category::model()->findByPk($top_leaf_id);
    $model       = Category::model()->findByPk($cur_leaf_id);
    
    $sql = 	" SELECT parent.name, parent.id ".
        " FROM category AS node,".
        " category AS parent ".
        " WHERE node.lft BETWEEN parent.lft AND parent.rgt ".
        " AND node.id = $model->id ".
        " ORDER BY parent.lft ";					
    $path = Category::model()->findAllBysql($sql);
    $temp_parent;
    foreach( $path as $obj ) {								
      if( $obj->id  == $model->id ) {					
        break;
      }				
      $model->parent_leaf     = $obj;		
      $model->parent_leaf_id  = $obj->id;
    }			


		if(isset($_POST['Category']))
		{		  
			$model->attributes=$_POST['Category'];		
			$model->update_time = date("Y-m-d H:i:s");
			if($model->save()){
        if( ( $model->parent_leaf_id != $_POST['Category']['parent_leaf_id']  ) &&
            isset( $_POST['Category']['parent_leaf_id'] ) &&  strlen($_POST['Category']['parent_leaf_id']) > 0) {
          
          $model->parent_leaf_id = $_POST['Category']['parent_leaf_id'];						
			    $model->parent_leaf = Category::model()->findByPk($_POST['Category']['parent_leaf_id']);				

          $cmodel = Category::model();
          $transaction = $cmodel->dbConnection->beginTransaction();				
          try{
            $parent_leaf = $cmodel->find('id = :id', array( ':id'=> $_POST['Category']['parent_leaf_id']) );
            $update_rgt = " UPDATE category SET rgt = rgt + 2 WHERE rgt > $parent_leaf->lft ";
            $cmodel->dbConnection->createCommand($update_rgt)->execute();
            $update_lft = " UPDATE category SET lft = lft + 2 WHERE  lft > $parent_leaf->lft ";					
            $cmodel->dbConnection->createCommand($update_lft)->execute();					

            $model->lft = $parent_leaf->lft + 1;
            $model->rgt = $parent_leaf->lft + 2;
            $model->save();
            $transaction->commit();	
          }catch(Exception $e ) {
            $transaction->rollBack();
          }
        }

    	  $str = Yii::t('cp','Data saved success On ').Time::now();
				Yii::app()->user->setFlash('success',$str);
    	  $this->redirect(array('leaf_update','top_leaf_id'=>$top_leaf->id,'cur_leaf_id' => $model->id,'action' => $action));
			}
		}

    $this->path = Category::model()->getPath($cur_leaf_id ? $cur_leaf_id : $top_leaf->id,$top_leaf->id);
    $this->render('//cp/category/create',array( 'model'=>$model,'top_leaf' => $top_leaf,
        'cur_leaf_id' => $cur_leaf_id,'action' => $action ),false,true );
  }
	

  public function actionLeaf_create() {
    $model=new Category();
   
    if( !isset($_GET['rem']) ){
      $back_url = $_POST['back_url'] ? $_POST['back_url'] : rurl();
      if( stripos( $back_url,'create') == false && stripos( $back_url,'update') == false ) {
        setState('back_url', $back_url);
      }
    }

    $action      =& $_GET['action'];
    $top_leaf_id =& $_GET['top_leaf_id'];
    $top_leaf    =& Category::model()->findByPk($top_leaf_id);

    $parent_leaf_id = $_GET['parent_leaf_id'];
    $parent_leaf = Category::model()->findByPk($parent_leaf_id);
    $model->parent_leaf_id =& $parent_leaf_id;
    $model->parent_leaf =& $parent_leaf;

		if(isset($_POST['Category']))
		{
		  $model->attributes=$_POST['Category'];
			// ==2 is pass the validate 
			// validate reutrn "[]" string
			if( strlen(CActiveForm::validate($model)) == 2 ) {
				
				$cmodel = Category::model();
				$transaction = $cmodel->dbConnection->beginTransaction();				
				try {          
					$leaf_model = Category::model();								
					
					$parent_leaf = $leaf_model->find('id = :id', array( ':id'=> $_POST['Category']['parent_leaf_id']) );

					$update_rgt = " UPDATE category SET rgt = rgt + 2 WHERE rgt > $parent_leaf->lft ";
					$cmodel->dbConnection->createCommand($update_rgt)->execute();
          
					$update_lft = " UPDATE category SET lft = lft + 2 WHERE  lft > $parent_leaf->lft ";					
					$cmodel->dbConnection->createCommand($update_lft)->execute();					
				
					$model->lft = $parent_leaf->lft + 1;
					$model->rgt = $parent_leaf->lft + 2;
				
					$model->create_time = date("Y-m-d H:i:s");
				
					$model->save();
					$transaction->commit();	
					
			    $str = Yii::t('cp','Create Success On ').Time::now();
			    Yii::app()->user->setFlash('success',$str);
          $this->redirect( array('leaf_update', 'cur_leaf_id' => $model->id,'action' => $action,'top_leaf_id'=>$top_leaf_id ) );
				}catch(Exception $e) {
					$transaction->rollBack();
				}				
			}
		}
    $this->path = Category::model()->getPath($model->parent_leaf_id,$top_leaf->id);
    $this->render('//cp/category/create',array( 'model'=>$model,'top_leaf' => $top_leaf,'action' => $action ),false,true );
  }

  public function actionLeaf_del() {
    $cur_leaf_id =& $_GET['cur_leaf_id'];
    $top_leaf_id =& $_GET['top_leaf_id'];
    $action      =& $_GET['action'];
    if( $top_leaf_id == $cur_leaf_id ) {
      $str = ' 根类别 '.$model->name.' 不可删除 '.Time::now();
		  Yii::app()->user->setFlash('success',$str);
 	    $this->redirect(array($action,'category_id' => $cur_leaf_id));
      exit;
    }
    $top_leaf    =& Category::model()->findByPk($top_leaf_id);
    $model       =& Category::model()->findByPk($cur_leaf_id);
    $cmodel = Category::model();
		$transaction = $cmodel->dbConnection->beginTransaction();		
    try {
      $str = ' 成功删除类别 '.$model->name.' '.Time::now();
			$width = $model->rgt - $model->lft + 1;
			// delete all leaf where lft between lft and rgt 
			// this will delete the leaf and children
			$del_sql = ' DELETE FROM '.$model->tableName().' WHERE lft BETWEEN '.$model->lft.' AND '.$model->rgt;			
			$cmodel->dbConnection->createCommand($del_sql)->execute();			
			$update_sql = ' UPDATE '.$model->tableName().' SET rgt = rgt - '.$width.' WHERE rgt > '.$model->rgt;
			$cmodel->dbConnection->createCommand($update_sql)->execute();
			$update_sql = ' UPDATE '.$model->tableName().' SET lft = lft - '.$width.' WHERE lft > '.$model->rgt;
			$cmodel->dbConnection->createCommand($update_sql)->execute();
			$transaction->commit();	
		  Yii::app()->user->setFlash('success',$str);
 	    $this->redirect(array($action));
    }catch( Exception $e ) {	
			$transaction->rollBack();
    }
  }
	
	/**
	 * Lists all models.
	 */
	public function actionIndex($opt=null) {
	  $controllerId = strlen($opt['controllerId']) > 0 ? $opt['controllerId'] : $this->controllerId;	  
	  if( !is_array($opt) ){
	    $criteria=new CDbCriteria;
	    $keyword = trim($_GET['keyword']);      
	  }else{
	    extract($opt);
	  }

    $criteria->select = 'id';

    if( isset($_GET['id_start']) && !empty($_GET['id_start']) ) {
      $criteria->condition .= " AND t.id >= :id_start ";
      $criteria->params[':id_start'] =& $_GET['id_start'];
      $tpl_params['id_start'] =& $_GET['id_start'];
    }
    if( isset($_GET['id_end']) && !empty($_GET['id_end']) ) {
      $criteria->condition .= " AND t.id <= :id_end ";
      $criteria->params[':id_end'] =& $_GET['id_end'];
      $tpl_params['id_end'] =& $_GET['id_end'];
    }

    $_times = array( 
        'c_start_time'  => '>=',
        'c_end_time'    => '<=',
        'create_start_time'  => '>=',
        'create_end_time'    => '<=',
        'update_start_time'  => '>=',
        'update_end_time'    => '<=',
        'q_start_time'  => '>=',
        'q_end_time'    => '<=',
        'a_start_time'  => '>=',
        'a_end_time'    => '<=',
    );

    foreach( $_times as $_time_k=>$_time_v ) {
      if( isset($_GET[$_time_k]) && !empty($_GET[$_time_k]) ) {
        $_temp_time = str_replace('_start','',$_time_k);
        $_temp_time = str_replace('_end','',$_temp_time);
        $criteria->condition .= " AND ".$_temp_time.' '.$_time_v.' :'.$_time_k;
        $criteria->params[':'.$_time_k] =& $_GET[$_time_k];
        $tpl_params[$_time_k] =& $_GET[$_time_k];
      }
    }
    /*
    if( isset($_GET['c_start_time']) && !empty($_GET['c_start_time']) ) {
      $criteria->condition .= " AND c_time >= :c_start_time ";
      $criteria->params[':c_start_time'] =& $_GET['c_start_time'];
      $tpl_params['c_start_time'] =& $_GET['c_start_time'];
    }

    if( isset($_GET['c_end_time']) && !empty($_GET['c_end_time']) ) {
      $criteria->condition .= " AND c_time >= :c_end_time ";
      $criteria->params[':c_end_time'] =& $_GET['c_end_time'];
      $tpl_params['c_end_time'] =& $_GET['c_end_time'];
    }
    */

    //if( isset($_GET['account_type']) && !empty($_GET['account_type']) ) {
    if( strlen( $_GET['account_type'] ) > 0 ) {
      $criteria->condition .= " AND account_type = :account_type ";
      $criteria->params[':account_type'] =& str_replace('.html','', $_GET['account_type']);
      $tpl_params['account_type'] =& str_replace('.html','',$_GET['account_type']);
    }

    $tpl_params['is_hide_adv'] =& $_GET['is_hide_adv'];

    $imodel = new $controllerId;
    $item_count = call_user_func( array( $imodel, 'count') , $criteria );    
    $page_size = strlen($opt['page_size']) > 0 ? $opt['page_size'] : 10;          
    $pages =new CPagination($item_count);
    $pages->setPageSize($page_size);      
    $pagination = new CLinkPager();
    $pagination->maxButtonCount = 5;
    $pagination->header='';
    $pagination->cssFile=false;
    $pagination->setPages($pages);    
    $pagination->init();      
    $criteria->limit        =  $page_size;
    $criteria->offset       = $pages->offset;
    $select_pagination = new  CListPager();
    $select_pagination->header = '<span>跳到:</span>';
//    $select_pagination->htmlOptions['onchange']="";
    $select_pagination->setPages($pages);    
    $select_pagination->init();    

    $list = call_user_func( array( $imodel, 'findAll') , $criteria );

    $_ids = '';

    foreach( $list as $inst ) {
      $_ids .= $inst->id.',';
    }

    $icriteria  = new CDbCriteria;
    $icriteria->condition  = ' find_in_set( id , "'.$_ids.'")';  
    $list = call_user_func( array( $imodel, 'findAll') , $icriteria );
    
    if( !is_array($opt['tpl_params']) ){
      $opt['tpl_params'] = array();
    }

    $opt['tpl_params']['item_count']  = '<span class="fwB">第&nbsp;'.($page_size*($pagination->currentPage)+1).' - '.$page_size*($pagination->currentPage+1);
    $opt['tpl_params']['item_count'] .= ',</span><span class="fwB" >共&nbsp;'.$item_count.' 条</span>';
    $opt['tpl_params']['list']        = $list;
    $opt['tpl_params']['pagination']  = $pagination;
    $opt['tpl_params']['select_pagination']  = $select_pagination;

    // echo Chtml::listBox('category_id',1,$leafs) 
    //print_r($opt['tpl_params']);
    
    $this->tpl_params =& $tpl_params;
	  if( $is_partial ){
	    $this->renderPartial('_index', $opt['tpl_params'], false, true );
	  }else{
	    $tpl = strlen( $opt['tpl'] ) > 0 ? $opt['tpl'] : 'index';
	    if( $_GET['ajax'] == 'ajax'){	      
	      $this->renderPartial($tpl, $opt['tpl_params'], false, true );  
	    }else{
	      $this->render($tpl, $opt['tpl_params'], false, true );  
	    }
	  } 
	}
	
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
    // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST[$this->controllerId]))
		{
			$model->attributes=$_POST[$this->controllerId];
			$model->a_time = Time::now();
			if($model->save()){
			  $str = Yii::t('cp','Data saved success On ').Time::now();
			  Yii::app()->user->setFlash('success',$str);
			  $this->redirect(array('Update','id'=>$model->id));	
			}
		}

 		$this->render('update',array( 'model'=>$model,));  
	}	
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */

  public function actionBatch() {
  	if(Yii::app()->request->isPostRequest) {
      $type = $_POST['type'];
			if( count($_POST['ids']) > 0 && ( $type=="删除" || $type='delete' )) {
				$ids =& $_POST['ids'];
				foreach( $ids as $id) {
				  $imodel = new $this->controllerId;
					$item = $imodel->findByPk($id);
					$item->delete();
				}
        $str = '已删除 '.count($ids).' 个用户数据 '.Time::now();
      	Yii::app()->user->setFlash('success',$str);
			}
      $this->redirect( rurl() );
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

  }

	public function actionDelete()
	{
    print("!---");
    exit;
		if(Yii::app()->request->isPostRequest)
		{
			if( strlen($_POST['ids']) >0 ) {
				$ids = explode(',',$_POST['ids']);
				foreach( $ids as $id) {
				  $imodel = new $this->controllerId;
					$item = $imodel->findByPk($id);
					$item->delete();
					//echo $item->title;
				} 
        $str = '已删除 '.count($ids).' 个用户数据 '.Time::now();
      	Yii::app()->user->setFlash('success',$str);
        $this->redirect( array('index') );
			}			
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}
?>
