<?php
$this->breadcrumbs=array(
	'Model Ext Field Datas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ModelExtFieldData', 'url'=>array('index')),
	array('label'=>'Create ModelExtFieldData', 'url'=>array('create')),
	array('label'=>'Update ModelExtFieldData', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ModelExtFieldData', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ModelExtFieldData', 'url'=>array('admin')),
);
?>

<h1>View ModelExtFieldData #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'field_1',
	),
)); ?>
