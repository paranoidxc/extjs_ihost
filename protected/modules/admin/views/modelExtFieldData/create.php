<?php
$this->breadcrumbs=array(
	'Model Ext Field Datas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ModelExtFieldData', 'url'=>array('index')),
	array('label'=>'Manage ModelExtFieldData', 'url'=>array('admin')),
);
?>

<h1>Create ModelExtFieldData</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>