<h3 class="lmTitle">会员积分查询</h3>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'integral-query-form',
	'action' => API_DOMAIN.'api/agent.api.php?do=searchIntegral',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="row">
	  <input type="hidden" name="autokey" value="<?php echo user()->idata['password'] ?>">
	  <label for="card_sn" class="required" >会员卡卡号</label>
	  <input type="text" name="sn" class='txt' id="card_sn" autocomplete='off'>
	  <?php echo CHtml::submitButton('积分查询',array('class'=>'btn small_btn')); ?>
	</div>

  <div class='result dN'>
  </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
