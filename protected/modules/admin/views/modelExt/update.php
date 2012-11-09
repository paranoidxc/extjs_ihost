<?php
$this->breadcrumbs=array(
	'Model Exts'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ModelExt', 'url'=>array('index')),
	array('label'=>'Create ModelExt', 'url'=>array('create')),
	array('label'=>'View ModelExt', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ModelExt', 'url'=>array('admin')),
);
?>

<h1>Update ModelExt <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>