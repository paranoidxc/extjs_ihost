<?php

class AttachmentController extends GController
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

	public function actionMove() {		
		
		if( isset($_POST['category_id']) ){			
			$category_id = $_POST['category_id'];
			$category = Category::model()->findByPk($category_id);
      $ids =& $_POST['ids'];
			foreach( $ids as $id) {				
				$at = Attachment::model()->findByPk($id);
				if( $at ) {									
					$at->category_id = $category->id;
					$at->save();				
				}
			}			
			echo count($ids)." record(s) are move to ";
			echo $category->name;
			exit;
		}	
    
    $leafs = Category::model()->ileafs(
        array( 'id' => $_GET['top_leaf_id'],'include' => true )
	  );	  
	  
		$this->renderPartial('move', array(
			'leafs' => $leafs,
			'panel_ident' => $panel_ident,
		),false, true);

	}
	
	
	public function actionPick(){		
		$return_id = $_GET['return_id'];
		$this->renderPartial('pick',array('return_id' => $return_id),false,true);
	}
  /**
   * undocumented function
   *
   * @return void
   * @author paranoid
   **/
  public function actionUpload()
  {    
    ini_set('max_execution_time', 120);
	  ini_set("html_errors", "0");
    $category_id  =& $_GET['category_id'];
    $user_id      =& $_GET['user_id'];
    $login_token  =& $_GET['login_token'];
    $watermark    =& $_GET['watermark'];
    $iuser = User::model()->findByPk($user_id);
    if( $iuser == null ) {
      echo '无权限上载;';
      exit;
    }
    if( $iuser->login_token != $login_token ) {
      echo 'login token 不匹配,请重新登录系统.';
      exit;
    }
    if( strlen( trim($category_id) ) ==  0 ) {
      //$category_id = 30;
      $category_id = Category::model()->autoCreate();      
    } else if( is_numeric($category_id)  ) {
      $category = Category::model()->findByPk($category_id);
    }else {
      $category = Category::model()->findByAttributes( array('ident'=>$category_id) );
      $category_id = $category->id;
    }
    
    $upload_name = "Filedata";
    $path_info = pathinfo($_FILES[$upload_name]['name']);
	  $file_extension = $path_info["extension"];
    $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)
    //$screen_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
    $screen_name  =  basename($_FILES[$upload_name]['name']);
    $time = time();
    $mt_rand = mt_rand();
    $file_name = $time.'.'.$file_extension;

    $put_file_path = API::upload_prefix_dir();
    $put_file_to_dir = ATMS_SAVE_DIR.$put_file_path;

    if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $put_file_to_dir.$file_name)) {	  	
      echo 'move_uploaded_file faild';
		  exit(0);
	  }
	  
	  $model=new Attachment;

	  $is_image = false;
	  $w = 0;
	  $h = 0;
	  if( in_array(strtolower($file_extension),API::$IMAGE_EXTENSION) ){
	    $image = Yii::app()->image->load($put_file_to_dir.$file_name);
	    $w = $image->width;	    
	    $h = $image->height;
	    $is_image = true;
	  }
	  
	  $ati = array(
	    'screen_name' => $screen_name,
	    'path'        => $put_file_path.$time.$mt_rand,
	    'w' => $w,
	    'h' => $h,
  	  'c_time' => Time::now(),
  	  'extension' => $file_extension,
	    'category_id' => $category_id,
      'user_id'     => $user_id,
    );
	  $model->attributes=$ati;
	  if($model->save()){
	    if( $is_image ){
	      $model->tips  = str_pad($w, 4, "_", STR_PAD_LEFT).'*'. str_pad($h,4,"_", STR_PAD_RIGHT).',';	      
  	    $default_size_list = false;
  	    if( strlen(trim($category->img_auto_size)) > 0 ) {
          // valid img auto size strong
  	      $auto_size_list = explode("\n", $category->img_auto_size);  	      
	      }else {	      
	        $default_size_list = true;
	        $auto_size_list = API::$ATT_IMG_AUTO_SIZE;
	      }

        // if the first item is not array ,it use category custom img auto size 
        if( !is_array($auto_size_list[0]) ) {
  	      $auto_size_list[] = "160_120";
  	      $auto_size_list[] = "48_48";
  	      $auto_size_list = array_unique($auto_size_list);
        }

        foreach( $auto_size_list as $_value ) {
          if( $default_size_list ){
            $v = $_value;
          }else {            
            if(strlen(trim($_value)) == 0) {
              continue; 
            }
            $v = array();
            $v['size'] = trim($_value);
            $v['radio'] = '';
            $v['is_thumb'] = '';
          }
          $file_path = $put_file_to_dir.$time.$mt_rand.'_'.$v['size'].'.'.$file_extension;
          list($dw , $dh ) = explode('_',$v['size']);
          if( $w > $dw && $h > $dh ) {

            if( ( $h/$w )  > ($dh/$dw) ){
              // 宽度决定
              if( $v['is_thumb'] ) {
                if( strlen($v['radio']) > 0 && $v['radio'] =='h' ) {
                 // $image->resize($dw*1.2, $dh*1.2,Image::HEIGHT)->crop($dw,$dh,'5','center');
                  $image->resize($dw*1.2, $dh*1.2,Image::HEIGHT)->crop($dw,$dh,'5','center');
                }else{
                  $image->resize($dw*1.2, $dh*1.2,Image::WIDTH)->crop($dw,$dh,'5','center');
                }
              }else{
                if( strlen($v['radio']) > 0 && $v['radio'] == 'h' ) {
                    $image->resize($dw, $dh,Image::HEIGHT);
                }else {
                  $image->resize($dw, $dh,Image::WIDTH);
                }
              }
            }else{
              // 高度决定
              if( $v['is_thumb'] ) {
                if( strlen($v['radio']) > 0 && $v['radio'] =='w' ) {
                  $image->resize($dw*1.2, $dh*1.2,Image::WIDTH)->crop($dw,$dh,'5','center');
                  //$image->resize($dw*1.2, $dh*1.2,Image::WIDTH);
                }else{
                  $image->resize($dw*1.2, $dh*1.2,Image::HEIGHT)->crop($dw,$dh,'5','center');
                  //$image->resize($dw*1.2, $dh*1.2,Image::HEIGHT);
                }
              }else{
                if( strlen($v['radio']) > 0 && $v['radio'] == 'w' ) {
                    $image->resize($dw, $dh,Image::WIDTH);
                }else{
                    $image->resize($dw, $dh,Image::HEIGHT);
                }
              }
            }
            $model->tips  .= str_pad($dw, 4, "_", STR_PAD_LEFT).'*'. str_pad($dh,4,"_", STR_PAD_RIGHT).',';

          } else {
            if( strlen($v['radio']) > 0 ) {
              if( $v['radio'] == 'h' ) {
                $image->resize($dw, $dh,Image::HEIGHT);
              }elseif( $v['radio'] == 'w' ) {
                $image->resize($dw, $dh,Image::WIDTH);
              }else {
                $image->resize($dw, $dh);
              }
              $model->tips  .= str_pad($dw, 4, "_", STR_PAD_LEFT).'*'. str_pad($dh,4,"_", STR_PAD_RIGHT).',';
            }
          }

          if($watermark && $dw == 800 ) {
            $image->watermark();
          }
          $image->save($file_path);          	            
        }
        $model->save(); 
        if( $watermark ) {
          $image = Yii::app()->image->load($put_file_to_dir.$file_name);
          $image->watermark();
          $image->save();
        }
        rename($put_file_to_dir.$file_name, $put_file_to_dir.$time.$mt_rand.'_'.$w.'_'.$h.'.'.$file_extension );
      }
	  }
	 
  }
  
	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{	  
    $action =& $_GET['action'];
    $cur_leaf_id =& $_GET['leaf_id'];
    $cur_leaf = Category::model()->findByPk($cur_leaf_id);
    $leafs = Category::model()->findAll( array( 
          'select' => 'id, name',
          'condition'  => ' rgt <= :rgt AND lft >= :lft ',
          'params'    => array( ':rgt' => $cur_leaf->rgt, ':lft' => $cur_leaf->lft )
    ) );
    $all_leafs = '';
    foreach( $leafs as $_leaf ){
      $all_leafs .= $_leaf->id.',';
    }        
    $model = new Attachment;
    $criteria=new CDbCriteria;
    $criteria->condition = ' find_in_set(category_id, :category_id)';
    $criteria->limit = '10000';
    $criteria->order =' c_time DESC ';
    $criteria->params[':category_id'] = $all_leafs;
    $list = $model->findAll( $criteria );

    $leaf_tree = $this->getTree( ATT_CAT_ID );
    //$leaf_tree = $this->getTree(30);
		$this->render('create',array( 'list' => $list,'cur_leaf'=>$cur_leaf,
                          'leaf_tree' => $leaf_tree,'action' => $action) );
	}

  public function actionBatchUpdate(){
    
    $ids = $_POST['ids'];
    $list = explode(',',$ids);
    $resize_w = $_POST['resize_w'];
		$resize_h = $_POST['resize_h'];
		
    foreach( $list as $id){
      $model=Attachment::model()->findbyPk($id);      
      if( !$model->is_image() ){
        continue;  
      }
      unset($tips);
      if( is_array( $resize_w ) && count( $resize_w) > 0 ){
        for( $i=0; $i<count($resize_w); $i++ ){
          if( is_numeric($resize_w[$i]) && is_numeric($resize_h[$i]) ){
            $image = Yii::app()->image->load(ATMS_SAVE_DIR.$model->path.'.'.$model->extension);		        
            $_path= ATMS_SAVE_DIR.$model->path.'_'.$resize_w[$i].'_'.$resize_h[$i].'.'.$model->extension; 
            $image->resize($resize_w[$i], $resize_h[$i]);
            $image->save($_path);
            if( isset($tips) ){
              $tips .= str_pad($resize_w[$i], 4, "_", STR_PAD_LEFT).'*'. str_pad($resize_h[$i],4,"_", STR_PAD_RIGHT).',';                  
            }else{
              $tips = str_pad($resize_w[$i], 4, "_", STR_PAD_LEFT).'*'. str_pad($resize_h[$i],4,"_", STR_PAD_RIGHT).',';
            }
				  }
        }
        if( isset($tips) ){
			    $model->tips .= $tips;
			    $model->save();
				}
      }
    }
    echo 'update';
    exit;
  }
  
  public function actionBatchEdit(){
    $ids = $_GET['ids'];
    $this->renderPartial('batch_edit',array(		  
      'ids' => $ids
		),false,true);
  }
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
    $action       =& $_GET['action'];
    $top_leaf_id  =& $_GET['top_leaf_id'];
		$model=$this->loadModel();
		if(isset($_POST['Attachment']))
		{
			$model->attributes=$_POST['Attachment'];
			if($model->save()){
			  
					//echo 'update attachment suc';
					if($model->is_image()){
  					$resize_w = $_POST['resize_w'];
  					$resize_h = $_POST['resize_h'];
  					if( is_array( $resize_w ) && count( $resize_w) > 0 ){
  					  
  					  for( $i=0; $i<count($resize_w); $i++ ){
  					    if( is_numeric($resize_w[$i]) && is_numeric($resize_h[$i]) ){			      
  					      $image = Yii::app()->image->load(ATMS_SAVE_DIR.$model->path.'_'.$model->w.'_'.$model->h.'.'.$model->extension);
  					      list($y,$m,$d,$iname) =  explode('/',$model->path);
                  $_path= ATMS_SAVE_DIR.$y.'/'.$m.'/'.$d.'/'.$iname.'_'.$resize_w[$i].'_'.$resize_h[$i].'.'.$model->extension; 
                  $image->resize($resize_w[$i], $resize_h[$i]);
                  $image->save($_path);
                  if( isset($tips) ){
                    $tips .= str_pad($resize_w[$i], 4, "_", STR_PAD_LEFT).'*'. str_pad($resize_h[$i],4,"_", STR_PAD_RIGHT).',';                  
                  }else{
                    $tips = str_pad($resize_w[$i], 4, "_", STR_PAD_LEFT).'*'. str_pad($resize_h[$i],4,"_", STR_PAD_RIGHT).',';
                  } 
  					    }
  					  }
  					  if( isset($tips) ){
  					    $model->tips .= $tips;
  					    $model->save();
  					  }
  					}
					}
			
					$str = 'Data Updated Suc On '.Time::now();
					Yii::app()->user->setFlash('success',$str);
					$this->redirect(array('update','id'=>$model->id,'action' => $action, 'top_leaf_id' => $top_leaf_id ));	
			
			}
		}
    $top_leaf = Category::Model()->findByPk($top_leaf_id);
    $leaf_tree =& $this->getTree($top_leaf_id);

    $this->path = Category::model()->getPath($model->category_id,$top_leaf->id);		

    $leafs = Category::model()->ileafs(
        array( 'id' => $top_leaf_id,'include' => true )
	  );
	  
    $this->render('update',array( 'model'=>$model,
      'leafs' =>$leafs, 'leaf_tree' => $leaf_tree,'action' => $action, 'top_leaf' => $top_leaf),false,true);
	}

  
  public function actionBatch() {    
    if(Yii::app()->request->isPostRequest) {
      $type = $_POST['type'];
		  $ids =& $_POST['ids'];
		  $move_to_category_id = $_POST['move_to_category_id'];
		  
			if( count($ids) > 0 && ( $type=="删除" || $type=="delete")) {
			  foreach( $ids as $id) {
				  $imodel = new $this->controllerId;
					$item = $imodel->findByPk($id);
					$item->delete();
				}
        $str = '已删除 '.count($ids).' 个用户数据 '.Time::now();
      }elseif ( count($ids) > 0 && ( $type=="移动" || $type=="move" )) {
        $count =& count($ids);
        $ids = join(',',$ids);        
        $category = Category::model()->findByPk($move_to_category_id);        
        Attachment::model()->updateAll( array('category_id' => $move_to_category_id), " FIND_IN_SET(id,:ids) ", array( ':ids' => $ids) );
			  $str = '已移动'.$count." 个用户数据到 ".$category->name;			  
		  }
			
			Yii::app()->user->setFlash('success',$str);
      $this->redirect( rurl() );
			  
			/*  
		  if( count($_POST['ids']) >0 ) {
				$ids = $_POST['ids'];
				foreach( $ids as $id) {
					$a = Attachment::model()->findByPk($id);
				  $a->delete();  
				}
				$str = '已删除 '.count($ids).' 个用户数据 '.Time::now();
  			Yii::app()->user->setFlash('success',$str);
			}
		  */
    }

    //$this->redirect( array('index') );
    
  }
  

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	 /*
	/**
	 * Lists all models.
	 */
  public function getTree($top_leaf='') {
    if( strlen($top_leaf) > 0 ) {
      return Category::model()->ileafs( array( 'id' => $top_leaf ,'include' => true ) );
    }else{
      return Category::model()->ileafs( array( 'ident' => 'Root' ,'include' => true ) );
    }
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
