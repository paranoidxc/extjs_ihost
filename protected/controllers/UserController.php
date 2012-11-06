<?php

class UserController extends Controller
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
				'actions'=>array('photo','index','show','view','gridgroup','demo','form','list','ok','save'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete','del','search'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionPhoto()
	{
		echo '{"total":"297","items":[{"id":"1605","url":"url","thumb":"\/upfiles\/2012\/10\/05\/13494497131115833227_160_120.jpg","screen_name":"wallpaper-1336483.jpg","type":"xx"},{"id":"1604","url":"url","thumb":"\/upfiles\/2012\/10\/05\/13494497011509154656_160_120.jpg","screen_name":"wallpaper-2189406.jpg","type":"xx"},{"id":"1603","url":"url","thumb":"\/upfiles\/2012\/10\/05\/1349449699687388168_160_120.jpg","screen_name":"Water lilies.jpg","type":"xx"},{"id":"1602","url":"url","thumb":"\/upfiles\/2012\/10\/05\/1349449169840443744_160_120.jpg","screen_name":"wallpaper-185672.jpg","type":"xx"},{"id":"1601","url":"url","thumb":"\/upfiles\/2012\/10\/05\/1349449164220336558_160_120.jpg","screen_name":"wallpaper-65375.jpg","type":"xx"},{"id":"1600","url":"url","thumb":"\/upfiles\/2012\/10\/05\/1349449156723121966_160_120.jpg","screen_name":"wallpaper-1336483.jpg","type":"xx"},{"id":"1599","url":"url","thumb":"\/upfiles\/2012\/10\/05\/13494491471614343970_160_120.jpg","screen_name":"wallpaper-1311749.jpg","type":"xx"}]}';
		exit;
	}

	public function actionSearch()
	{

		print_r($_REQUEST);
		exit;
		//http://127.0.0.1/extjs/index.php?r=user/search&_dc=1342000079907&format=json&data=&Search%255Busername%255D%3Ddddd&page=1&start=0&limit=20
		//http://127.0.0.1/extjs/index.php?r=user/search&_dc=1341999931454&format=json&data=Search%255Busername%255D%3D&page=1&start=0&limit=20

		$models = User::model()->findAll( array('limit' => 10 ));
		$r['total'] = 1000;//User::model()->count();		
		if ($models){
    	$r['data'] = $models;		
    }
    echo CJSON::encode($r);	
	}

	public function actionOk()
	{
		$arr['success'] = true;
		$arr['msg'] = 'okay';
		echo json_encode($arr);
	}

	public function actionDenied()
	{
		$arr['success'] = false;
		$arr['msg'] = 'falid';
		echo json_encode($arr);
	}

	public function actionList()
	{
		$r['total'] = User::model()->count();				
		$criteria = new CDbCriteria();
		if( isset($_REQUEST['Search']) ){			
			$search = $_REQUEST['Search'];
			if( strlen($search['username']) > 0 ) {
				$criteria->addCondition("username= '$search[username]' "); 				
			}
		}
		$criteria->order = ' id DESC ';
		$pager = new CPagination($r['total']);
		$pager->pageSize = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10 ;
		$pager->applyLimit($criteria);
		$models = User::model()->findAll($criteria);
		if ($models){
			foreach( $models as & $model ){
				$model->itype = 'xxxxxxx';				
			}
    	$r['data'] = $models;		
    }
    echo CJSON::encode($r);
	}
	
	public function actionForm()
	{
	  $this->render('form');
	}
	public function actionDemo()
	{
	  $this->render('demo');
	}
	
	public function actionGridGroup()
	{
	  $this->render('grid_group');
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionShow()
	{
		header('Content-type: application/json');
		$id = $_REQUEST['id'];
		$model = $this->loadModel($id);
		//$r['data'] = $model;
		$r['data']['User[id]'] 				= $model->id;
		$r['data']['User[username]'] 	= $model->username;
		$r['data']['User[password]'] 	= $model->username;
		echo CJSON::encode($r);
	}

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
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionSave()
	{		
		$r = array( 'success' => false, 'msg' => '操作失败' );		
		if( isset($_POST['id']) && !empty($_POST['id']) ) {
			$id = $_POST['id'];
			$model = $this->loadModel($id);
			$model->attributes = $_POST;
			$msg = 'update suc';
		}else {
			$model = new User;
			$model->attributes = $_POST;
			$msg = 'add suc';
		}
		if($model->save()) {
			$r = array( 'success' => true, 'msg' => $msg );
		}else {
			$r['msg'] = 'ffff';
			$r['r'] = CActiveForm::validate($model);
		}
		echo CJSON::encode($r);
		exit;		
	}

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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
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

	public function actionDel()
	{
		$result = array( 'success' => false , 'msg' => 'error' );
		$ids = $_REQUEST['id'];
		$ids = explode(',',$ids);
		if( is_array( $ids ) ) {
			foreach( $ids as $id )	{				
				if( (int)$id != 0 ){
					$this->loadModel($id)->delete();
				}
			}
			$result['msg'] = 'okay';
			$result['success'] = true;
		}		
		echo json_encode($result);
		exit;
	}

	public function actionDelete()
	{
		$result['msg'] = 'okay';
		$result['success'] = true;
		echo json_encode($result);
		exit;
		/*
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request			
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(403,'Invalid request. Please do not repeat this request again.');
			*/
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
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
			//echo CJSON::encode($model);
			//exit;
		}else {
			//$this->render('index');
			$this->renderPartial('index',array(),false,true);
		}
		/*
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
		*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

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
		$model=User::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
