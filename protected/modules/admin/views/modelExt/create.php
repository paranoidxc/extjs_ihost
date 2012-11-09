<?php
$this->breadcrumbs=array(
	'Model Exts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ModelExt', 'url'=>array('index')),
	array('label'=>'Manage ModelExt', 'url'=>array('admin')),
);
?>

<h1>Create ModelExt</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>