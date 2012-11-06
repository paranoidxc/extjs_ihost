<?php
$this->breadcrumbs=array(
	'Model Ext Fields'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ModelExtField', 'url'=>array('index')),
	array('label'=>'Manage ModelExtField', 'url'=>array('admin')),
);
?>

<h1>Create ModelExtField</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>