<?php
$this->breadcrumbs=array(
	'Model Ext Fields'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ModelExtField', 'url'=>array('index')),
	array('label'=>'Create ModelExtField', 'url'=>array('create')),
	array('label'=>'View ModelExtField', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ModelExtField', 'url'=>array('admin')),
);
?>

<h1>Update ModelExtField <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>