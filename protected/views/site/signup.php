<h3 class="lmTitle">会员注册</h3>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'signup-form',
	'enableClientValidation'=>false,	
	'action'  => API_DOMAIN.'api/agent.api.php?do=reg',
	'htmlOptions'=>array('class'=>'ajax-submit','rel'=>"{ type: 'signup'}"),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pwd'); ?>
		<?php echo $form->passwordField($model,'pwd',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'pwd'); ?>		
	</div>

  <div class="row">
		<?php echo $form->labelEx($model,'pwd_confirm'); ?>
		<?php echo $form->passwordField($model,'pwd_confirm',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'pwd_confirm'); ?>		
	</div>

  <div class="row">
		<?php echo $form->labelEx($model,'realname'); ?>
		<?php echo $form->textField($model,'realname',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'realname'); ?>		
	</div>
	
	<div class="row radioform">
		<?php echo $form->labelEx($model,'gender',array('class'=>'label')); ?>		
		<span id="gende_wrap">
		<?php
      echo $form->radioButtonList($model, 'gender',
                  array(  0 => '男',1 => '女' ),
                  array( 'separator' => "  " ) );
    ?>    
    </span>
		<?php echo $form->error($model,'gender'); ?>		
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tel'); ?>
		<?php echo $form->textField($model,'tel',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'tel'); ?>		
	</div>
            
	<div class="row">
		<?php echo $form->labelEx($model,'id_no'); ?>
		<?php echo $form->textField($model,'id_no',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'id_no'); ?>		
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recommend_sn'); ?>
		<?php echo $form->passwordField($model,'recommend_sn',array('class'=>'txt')); ?>
		<?php echo $form->error($model,'recommend_sn'); ?>		
	</div>
	
	<div class="row buttons">
	  <label></label>
	  <input type="hidden" name="autokey" value="<?php echo user()->idata['password'] ?>">
		<?php echo CHtml::submitButton('会员注册'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
