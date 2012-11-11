<?php

class SysLdItemController extends Controller
{	
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
	public function actionCreate()
	{
		$model=new SysLdItem;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SysLdItem']))
		{ 
			$model->attributes=$_POST['SysLdItem'];
			//$parent = SysLdItem::model()->findByPk( $model->parent_id );
			if( $model->parent ) {
				$model->ilevel = $model->parent->ilevel + 1;
			}
			if($model->save()) {				
				user()->setFlash('success','数据添加成功!');
				$this->redirect(array('create'));
			}				
		}
		$data['parent'] = CHtml::listData(SysLdItem::model()->valid_ldtree(),'id','_foramt_name');		
		$this->renderPartial('create',array(
			'model'=>$model,
			'data' => $data
		));
	}

	public function actionSave()
	{		
		$r = array( 'success' => false, 'msg' => '操作失败' );		
		if( isset($_POST['id']) && !empty($_POST['id']) ) {
			$id = $_POST['id'];
			$model = $this->loadModel($id);
			$model->attributes = $_POST;
			$model->parent_id = empty($model->parent_id) ? 0 : $model->parent_id;
			$msg = 'update suc';
		}else {
			if( $_POST['create_way'] == 0 ){
				$config = $_POST['SysLdItem']['config'];
				$items = explode("\n",$config);				
				foreach($items as $item ){					
				  list($name,$ident,$iorder,$value,$status) = explode("|",$item);
				  $arr['name'] 		= $name;
				  $arr['ident'] 	= $ident;
				  $arr['iorder'] 	= $iorder;
				  $arr['status'] 	= $status;
				  $arr['value'] 	= $value;
				  $arr['parent_id'] = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;
					$model = new SysLdItem;
				  $model->attributes = $arr;
				  if( $model->save() ) {
				  	$model->make_level();				  	
				  }
				}
				$r = array( 'success' => true, 'msg' => '批量添加成功' );
				echo CJSON::encode($r);
				exit;	
			}else {
				$model = new SysLdItem;
				$model->attributes = $_POST;		
				$model->parent_id = empty($model->parent_id) ? 0 : $model->parent_id;					
				$msg = 'add suc';
			}			
		}
		if($model->save()) {
			$model->make_level();
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

		if(isset($_POST['SysLdItem']))
		{
			$model->attributes=$_POST['SysLdItem'];
			if($model->save()) {
				$model->make_level();
				//Yii::app()->cache->set('common/ldlist',null);
				user()->setFlash('success','数据更新成功!');
				$this->redirect(array('update','id'=>$model->id));
			}				
		}				
		$data['parent'] = CHtml::listData(SysLdItem::model()->valid_ldtree(),'id','_foramt_name');
		$data['record_status'] = helpfn::dd('record_status');
		$this->render('update',array(
			'model'=>$model,
			'data' => $data
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id=0)
	{
		$result = array( 'success' => false , 'msg' => 'error' );
		$ids = $_REQUEST['id'];
		$ids = explode(',',$ids);
		if( is_array( $ids ) ) {
			foreach( $ids as $id )	{				
				if( (int)$id != 0 ){
					$model = $this->loadModel($id);
					if( !empty($model) ) {
						$model->status = (int)(!$model->status);
						$model->save();						
					};
				}
			}
			$result['msg'] = '操作成功';
			$result['success'] = true;
		}		
		echo json_encode($result);
		exit;
		$record = $this->loadModel($id);
		$record->status = 1;
		$record->save();		
		user()->setFlash('success','记录 '.$record->name.' 停用成功！');		
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		exit;


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

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$list = SysLdItem::model()->ldtree();			
		$r = '';
		if ($list){
			foreach( $list as & $model ){				
				$model->parent_id = $model->parent ? $model->parent->name :'无';
				$model->name = '|--'.str_repeat('-', $model->ilevel*2).$model->name;			
				//echo $model->format_name;
			}
    	$r['data'] = $list;	
    }		    
		echo CJSON::encode($r);
	}

	public function actionTree()
	{
		$list = SysLdItem::model()->FullTree(0);		
		echo json_encode($list);
		exit;
		$list = SysLdItem::model()->ldtree();			
		$r = '';
		if ($list){
			foreach( $list as & $model ){				
				$r[] = array(
					'id' 			=> $model->id, 
					'text'		=>$model->name,
					'ident' 	=> $model->ident,
					'iorder'	=> $model->iorder,
					'value'		=> $model->value,
					'status'	=> $model->status
				);
				//$model->parent_id = $model->parent ? $model->parent->name :'无';								
			}    				
    }		    
		echo CJSON::encode($r);
	}

	public function actionIndex()
	{
		if( isset($_REQUEST['id']) ) {
			$id = $_REQUEST['id'];	
			header('Content-type: application/json');
			if( $id == -1 ){
				$model = new SysLdItem;				
			}else {
				$model = $this->loadModel($id);				
			}			
			$this->renderPartial('create',array('model' => $model),false,true);
			//echo CJSON::encode($model);
			//exit;
		}else {
			$this->renderPartial('index',array(),false,true);
		}
	}

	public function loadModel($id)
	{
		$model=SysLdItem::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SysLdItem('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SysLdItem']))
			$model->attributes=$_GET['SysLdItem'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sys-ld-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
