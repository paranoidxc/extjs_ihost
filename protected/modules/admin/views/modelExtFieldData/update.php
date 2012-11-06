<?php
$this->breadcrumbs=array(
	'Model Ext Field Datas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ModelExtFieldData', 'url'=>array('index')),
	array('label'=>'Create ModelExtFieldData', 'url'=>array('create')),
	array('label'=>'View ModelExtFieldData', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ModelExtFieldData', 'url'=>array('admin')),
);
?>

<h1>Update ModelExtFieldData <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>