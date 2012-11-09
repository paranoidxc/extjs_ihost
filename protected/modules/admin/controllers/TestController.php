<?php

class TestController extends GController
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
				'actions'=>array('upload','createdata'),
				'users'=>array('*'),
			),
	
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','upload','pick','move','BatchEdit','BatchUpdate','leaf_create','leaf_update','leaf_del'),
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
	public function actionCreate()
	{
		$this->renderPartial('create', array(),false,true);
	}

	public function actionCreateData()
	{
		$fields = ModelExtField::model()->findAllByAttributes( array('model_id' => 1),array('order'=>'sort DESC'));
		$this->renderPartial('createdata', array('fields' => $fields ),false,true);
	}
	

	public function actionIndex($top_leaf_id='',$cur_leaf_id='') {
    	$this->renderPartial('index',array(),false,true);
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
