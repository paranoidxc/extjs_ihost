<h3 class="lmTitle">会员消费</h3>
<div class="form">
  <input type="hidden" id="query_integral_url" value="<?php echo API_DOMAIN.'api/agent.api.php?do=searchIntegral' ?>" ?>
  
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consumption-form',
	'action'    => API_DOMAIN.'api/agent.api.php?do=consumption',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'card_sn'); ?>
		<?php echo $form->textField($model,'card_sn',array('class'=>'txt','autocomplete' => 'off') ); ?>
		<?php echo CHtml::Button('积分查询',array('class'=>'btn small_btn query_integral_btn')); ?>	  
		<?php echo $form->error($model,'card_sn'); ?>
	</div>
	<div class='result'>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'card_pwd'); ?>
		<?php echo $form->passwordField($model,'card_pwd',array('class'=>'txt') ); ?>
		<?php echo $form->error($model,'card_pwd'); ?>		
	</div>
	
	<div class="row radioform ">
		<?php echo $form->labelEx($model,'支付类型 ',array('class'=>'label')); ?>		
		<input type="radio" id="payment_cash" name="ConsumptionForm[payment]" value="0" checked >
		<label for="payment_cash">现金</label>
    <input type="radio" id="payment_integral" name="ConsumptionForm[payment]" value="1"  >
    <label for="payment_integral">积分</label>
    <input type="radio" id="payment_all" name="ConsumptionForm[payment]" value="2"  >
    <label for="payment_all">混合</label>
		<?php echo $form->error($model,'gender'); ?>		
	</div>
	
	<div id="row_payment_cash" class='row row_payment'>
	  <label>金额 </label>
    <input type="text" class="txt" value="" name="ConsumptionForm[cash]"  /> <span payment_cash_error>元</span>
    <span class='payment_cash_error'></span>
  </div> 
  
  <div id="row_payment_integral" class='row row_payment '>
	  <label>积分 </label>	
    <input type="text" class="txt" value="" name="ConsumptionForm[integral]" id="payment_integral_all" /> 
    <span class='payment_integral_error'></span>
  </div> 

  <div class="row">
		<?php echo $form->labelEx($model,'memo'); ?>
		<?php echo $form->textarea($model,'memo', array( 'class' => 'txt small_area' ) ); ?>
		<?php echo $form->error($model,'memo'); ?>		
	</div>

	<div class="row buttons">
	  <label></label>
	  <input type="hidden" name="autokey" value="<?php echo user()->idata['password'] ?>">
		<?php echo CHtml::submitButton('提交会员消费记录'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
