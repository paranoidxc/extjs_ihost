<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'model-ext-field-data-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'field_1'); ?>
		<?php echo $form->textField($model,'field_1',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'field_1'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->