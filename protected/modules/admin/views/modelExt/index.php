<?php
$this->breadcrumbs=array(
	'Model Exts',
);

$this->menu=array(
	array('label'=>'Create ModelExt', 'url'=>array('create')),
	array('label'=>'Manage ModelExt', 'url'=>array('admin')),
);
?>

<h1>Model Exts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
