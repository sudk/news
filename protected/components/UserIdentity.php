<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	const ERROR_USER_FREEZE = 1001; //冻结
	const ERROR_USER_DISABLE = 1002; //注销
	

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {

        return $this->auth_system ();
	}
	
	public function auth_system() {



		$operator = Operator::model ()->find ( 'login_name=:login_name', array (':login_name' => $this->username ) );
		
		if ($operator == null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
			return false;
		}
		if ($operator->status == Operator::STATUS_DISABLE) {
			$this->errorCode = self::ERROR_USER_DISABLE;
			return false;
		}
		if ($operator->status == Operator::STATUS_FREEZE) {
			$this->errorCode = self::ERROR_USER_FREEZE;
			return false;
		}

		if (md5 ( $this->password ) != $operator->password) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
			return false;
		}
		
		Yii::app ()->user->setState ( 'id', $operator->login_name );
		Yii::app ()->user->setState ( 'name', $operator->name );
		Yii::app ()->user->setState ( 'type', $operator->type );
		Yii::app ()->user->setState ( 'op_id', $operator->op_id );

		$this->errorCode = self::ERROR_NONE;
		
		return ! $this->errorCode;
	}

}