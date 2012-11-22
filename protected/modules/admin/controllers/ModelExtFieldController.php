<?php

class ModelExtFieldController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','save','list'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ModelExtField']))
		{
			$model->attributes=$_POST['ModelExtField'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionCreate()
	{
		$model=new ModelExtField;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ModelExtField']))
		{
			$model->attributes=$_POST['ModelExtField'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionSave()
	{
		$r = array( 'success' => false, 'msg' => '222' );				
		if( isset($_POST['Form']) ){			
			$data = $_POST['Form'];
			if( isset($data['id']) && strlen($data['id']) > 0 ){
				$model = $this->loadModel( $data['id'] );
				$model->attributes = $data;
				$msg = 'update suc';
			}else {										
				$model = new ModelExtField;
				$model->attributes = $data;
				$msg = 'add suc';
			}
			if($model->save()) {
				$r = array( 'success' => true, 'msg' => $msg );			
			}else {
				$r['msg'] = 'ffff';
				$r['r'] = CActiveForm::validate($model);
			}	
		}
		echo CJSON::encode($r);
		exit;
	}

	public function actionList() {
		$model_id = $_GET['model_id'];
		$model_id = 1;
		
		
		//$r['count'] = ModelExtField::model()->count( );
		
		$fields = ModelExtField::model()->findAllByAttributes( array('model_id' => $model_id ));
		$t = array();
		foreach($fields as $field){			
		}		
		$r['data'] = $fields;
		$r['count'] = 1;
    	echo CJSON::encode($r);		
		exit;
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if(isset($_REQUEST['id'])) {
			$id = $_REQUEST['id'];
			if( $id == -1 ){
				$model = new ModelExtField;
				$model->model_id = $_REQUEST['model_id'];
			}else {
				$model = $this->loadModel($id);
				// print_r("<PRE>");
				// print_r($model->config);
				// $items = explode("\n",$model->config);  
				// print_r($items);
				// print_r("</PRE>");
				// exit;
				$model->config = addslashes($model->config);
				$model->config = str_replace(array("\n","\r"),array("\\n","\\r"),$model->config); 
			}
			$this->renderPartial('create',array('model' => $model),false,true);	
		}else {
			$model_id = $_GET['model_id'];
			// $fields = ModelExtField::model()->findAllByAttributes( array('model_id' => $model_id ));
			// $r = array();
			// foreach($fields as $field){			
			// 	$r[] = array( 'id'	=>$field->id,
			// 				'model_id' => $field->model_id,
			// 				'field_name' => $field->field_name,
			// 				'display_name'=>$field->display_name );
			// }		
			// $fields = array_to_json($r);		
			$this->renderPartial('index',array('model_id'=>$model_id,'fields'=>$fields),false,true);
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ModelExtField('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ModelExtField']))
			$model->attributes=$_GET['ModelExtField'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ModelExtField::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='model-ext-field-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
