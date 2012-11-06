<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('login','logout','error','active'),
				'users'=>array('*'),
			),
	
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','signup','card_active','member'),
				'users'=>array('@'),
			),			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	public function actionActive(){	  
	  $this->renderPartial('active');
	}
	
	public function actionIndex()
	{	  	 
	print("F") ;
		exit;
		$model=new ConsumptionForm;
		$this->render('index',array('model'=>$model));	
	}
	
	public function actionCard_Active() {	  
	  $model=new CardActiveForm;
		$this->render('card_active',array('model'=>$model));	  
	}
	
	public function actionSignup() {
	  $model=new SignUpForm;
		$this->render('signup',array('model'=>$model));	  
	}
	
	public function actionMember() {
	  $this->render('member');	  
	}
	
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	public function actionLogin() {
		$this->render('login');
	}

	public function actionILogin()
	{		
	  if( !user()->isGuest ){
	    $this->redirect( array('index') ); 
	    exit;
	  }
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {		
			  Yii::app()->user->setState('idata',$_POST['LoginForm']);
			  //Yii::app()->user->current_user = $_POST['loginForm'];
			  $r['msg'] = 'success';
			  $r['success'] = true;
			  //$this->redirect(Yii::app()->user->returnUrl);
			}else {
			  $r['success'] = false;
			}
			echo json_encode($r);
			exit;
		}
		// display the login form
		$this->render('ilogin',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}