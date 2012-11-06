<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SignUpForm extends CFormModel
{
  public $username;
  public $pwd;
  public $pwd_confirm;
  public $realname;
  public $gender;
	public $tel;
	public $memo;
	public $id_no;
	public $recommend_sn;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(			
		  array('memo', 'default'),			
			array('username,pwd,pwd_confirm, realname, gender,tel', 'required'),			
			// password needs to be authenticated			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		  'username'      => '我的会员账号',
		  'pwd'           => '创建密码',
		  'pwd_confirm'   => '再次输入密码',
		  'realname'      => '姓名',
		  'gender'        => '性别',
		  'id_no'         => '身份证号',
		  'tel'           => '联系电话',
		  'recommend_sn'  => '推荐人卡号',
		);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function active()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
