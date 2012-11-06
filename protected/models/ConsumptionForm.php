<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ConsumptionForm extends CFormModel
{
  public $card_sn;
  public $card_pwd;
  public $payment;
	public $memo;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(					  
			array('card_sn,card_pwd,payment', 'required'),			
			// password needs to be authenticated			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		  'card_sn'       => '会员卡卡号',
		  'card_pwd'      => '会员卡密码',
		  'pwd_confirm'   => '再次输入密码',
		  'payment'       => '支付类型',
		  'memo'  => '备注',
		);
	}

}
