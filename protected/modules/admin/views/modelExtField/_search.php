<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'model_id'); ?>
		<?php echo $form->textField($model,'model_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'e_type'); ?>
		<?php echo $form->textField($model,'e_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'field_name'); ?>
		<?php echo $form->textField($model,'field_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'display_name'); ?>
		<?php echo $form->textField($model,'display_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tip'); ?>
		<?php echo $form->textField($model,'tip',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_value'); ?>
		<?php echo $form->textField($model,'default_value',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'config'); ?>
		<?php echo $form->textArea($model,'config',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sort'); ?>
		<?php echo $form->textField($model,'sort'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_blank'); ?>
		<?php echo $form->textField($model,'is_blank'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->