<h3 class="lmTitle">会员卡激活</h3>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'card-active-form',	
	'enableClientValidation'=>false,
	'action'  => API_DOMAIN.'api/agent.api.php?do=activation',
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('class'=>'txt') ); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'password'); ?>		
	</div>


  <div class="row">
		<?php echo $form->labelEx($model,'card_sn'); ?>
		<?php echo $form->textField($model,'card_sn',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'card_sn'); ?>		
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'active_code'); ?>
		<?php echo $form->textField($model,'active_code',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'active_code'); ?>		
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'card_pwd'); ?>
		<?php echo $form->passwordField($model,'card_pwd',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'card_pwd'); ?>		
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'card_pwd_confirm'); ?>
		<?php echo $form->passwordField($model,'card_pwd_confirm',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'card_pwd_confirm'); ?>		
	</div>
	
	<div class="row buttons">
	  <label></label>
	  <input type="hidden" name="autokey" value="<?php echo user()->idata['password'] ?>">
		<?php echo CHtml::submitButton('激活会员卡'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
