<?php
$this->breadcrumbs=array(
	'Model Ext Fields'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ModelExtField', 'url'=>array('index')),
	array('label'=>'Create ModelExtField', 'url'=>array('create')),
	array('label'=>'Update ModelExtField', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ModelExtField', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ModelExtField', 'url'=>array('admin')),
);
?>

<h1>View ModelExtField #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'model_id',
		'e_type',
		'field_name',
		'display_name',
		'tip',
		'default_value',
		'config',
		'sort',
		'is_blank',
		'status',
	),
)); ?>
