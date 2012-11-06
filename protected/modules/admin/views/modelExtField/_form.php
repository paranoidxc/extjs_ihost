<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'model-ext-field-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'model_id'); ?>
		<?php echo $form->textField($model,'model_id'); ?>
		<?php echo $form->error($model,'model_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'e_type'); ?>
		<?php echo $form->textField($model,'e_type'); ?>
		<?php echo $form->error($model,'e_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'field_name'); ?>
		<?php echo $form->textField($model,'field_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'field_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'display_name'); ?>
		<?php echo $form->textField($model,'display_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'display_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tip'); ?>
		<?php echo $form->textField($model,'tip',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'tip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'default_value'); ?>
		<?php echo $form->textField($model,'default_value',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'default_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'config'); ?>
		<?php echo $form->textArea($model,'config',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'config'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->textField($model,'sort'); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_blank'); ?>
		<?php echo $form->textField($model,'is_blank'); ?>
		<?php echo $form->error($model,'is_blank'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->