<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'department-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>90)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'memo'); ?>
		<?php echo $form->textArea($model,'memo',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'memo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'p_id'); ?>
		<?php echo $form->textField($model,'p_id'); ?>
		<?php echo $form->error($model,'p_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->