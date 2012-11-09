<?php
$this->breadcrumbs=array(
	'Model Exts'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ModelExt', 'url'=>array('index')),
	array('label'=>'Create ModelExt', 'url'=>array('create')),
	array('label'=>'Update ModelExt', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ModelExt', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ModelExt', 'url'=>array('admin')),
);
?>

<h1>View ModelExt #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'memo',
	),
)); ?>
