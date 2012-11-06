<?php
$this->breadcrumbs=array(
	'Model Ext Field Datas',
);

$this->menu=array(
	array('label'=>'Create ModelExtFieldData', 'url'=>array('create')),
	array('label'=>'Manage ModelExtFieldData', 'url'=>array('admin')),
);
?>

<h1>Model Ext Field Datas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
