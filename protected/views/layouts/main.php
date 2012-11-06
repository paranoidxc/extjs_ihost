<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">    
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
<?php    
	$baseUrl = Yii::app()->baseUrl; 
  if( strlen($baseUrl)>0 ) {
    $fbaseUrl = substr($baseUrl,1);
  }else {
    $fbaseUrl = '';
  }
	$cs = Yii::app()->getClientScript();	  
	//echo Yii::app()->request->scriptFile;
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>    
<!--<link type="text/css" rel="stylesheet" href="<?php echo $baseUrl;?>/extjs/resources/css/ext-all-unmin.css" />-->
<link type="text/css" rel="stylesheet" href="<?php echo $baseUrl;?>/extjs/resources/css/ext-all.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $baseUrl;?>/extjs/resources/css/fontsize12.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $baseUrl;?>/css/all.css" />
<!--<script type="text/javascript" src="<?php echo $baseUrl;?>/extjs/bootstrap.js"></script>-->
<script type="text/javascript" src="<?php echo $baseUrl;?>/extjs/ext-all.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/extjs/locale/ext-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/config.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/plus.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/global.js"></script>

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/jquery-1.4.2.min.js"></script>  
</head>
<body>
  <div class="container" id="page">  
    <div>
  	  <?php echo $content; ?>
    </div>
  </div>
</body>
</html>