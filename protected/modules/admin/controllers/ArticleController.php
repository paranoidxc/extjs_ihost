<?php

class ArticleController extends GController
{
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('upload'),
				'users'=>array('*'),
			),
	
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','list','view','upload','pick','move','BatchEdit','BatchUpdate','leaf_create','leaf_update','leaf_del'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','batch'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	
  


	/**
   * Lists all models.
   */
  public function actionList()
  {
    $criteria = new CDbCriteria();
    if( isset($_REQUEST['Search']) ){     
      $search = $_REQUEST['Search'];
      if( strlen($search['username']) > 0 ) {
        $criteria->addCondition("username= '$search[username]' ");        
      }
    }
    if( strlen( $_REQUEST['category_id']) > 0 ){
    	$criteria->addCondition("category_id = '$_REQUEST[category_id]' ");    	
    }
    $criteria->order = ' id DESC ';
    $r['total'] = Article::model()->count($criteria);   
    $pager = new CPagination($r['total']);
    $pager->pageSize = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10 ;
    $pager->applyLimit($criteria);
    $models = Article::model()->findAll($criteria);    
    $r['data'] = $models;   
    echo CJSON::encode($r);
  }

  public function actionIndex($top_leaf_id='',$cur_leaf_id='')
  {   
    if( isset($_REQUEST['id']) ) {
      $id = $_REQUEST['id'];  
      header('Content-type: application/json');
      if( $id == -1 ){
        $model = new User;        
        $model->status = '1'; 
        $model->itype = '0';  
      }else {
        $model = $this->loadModel($id);       
      }     
      $this->renderPartial('create',array('model' => $model),false,true);
    }else {
      $this->renderPartial('index',array(),false,true);
    }    
  }

	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Attachment::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='attachment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
