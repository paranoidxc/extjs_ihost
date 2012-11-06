<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'action'  => API_DOMAIN.'api/agent.api.php?do=login',
	//'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<?php echo $form->hiddenField($model,'currentMoney',array('class'=>'txt')); ?>
	<?php echo $form->hiddenField($model,'currentPoint',array('class'=>'txt')); ?>
  <div class="loginBG">
    <div class="loginCont">
      <h3>系统登录</h3>
      <table border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td rowspan="2" width="70">
            <img src="images/loginIco.jpg" width="65" height="65" />
          </td>
          <td width="90" align="right">
            <?php echo $form->labelEx($model,'username'); ?>
          </td>
          <td>
            <?php echo $form->textField($model,'username',array('class'=>'inputText') ); ?>            
          </td>
          <td width="160" class='error_username'>            
          </td>
        </tr>
        <tr>
          <td align="right">
            <?php echo $form->labelEx($model,'password'); ?>                       
          </td>
          <td>
            <?php echo $form->passwordField($model,'password',array('class'=>'inputText')); ?>            
          </td>
          <td class='error_password'>
            <!--<span>请正确填写商家密码！</span>-->
          </td>
        </tr>
        <tr>
          <td colspan="2"></td>
          <td colspan="2">
            <?php echo CHtml::submitButton('',array('class'=>'loginBtn')); ?>            
            <input type="reset" value="" name="reset" class="resetBtn" />
          </td>
        </tr>
      </table>
      <div class="clear"></div>
    </div>
  </div>
<?php $this->endWidget(); ?>