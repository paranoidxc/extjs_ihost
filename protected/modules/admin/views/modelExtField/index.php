<?php
$this->breadcrumbs=array(
	'Model Ext Fields',
);

$this->menu=array(
	array('label'=>'Create ModelExtField', 'url'=>array('create')),
	array('label'=>'Manage ModelExtField', 'url'=>array('admin')),
);
?>

<h1>Model Ext Fields</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
