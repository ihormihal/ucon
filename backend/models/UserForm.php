<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class UserForm extends Model
{
	public $id = null;
	public $username = '';
	public $email = 'mail@example.com';
	public $password;
	public $role = 'vendor';

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['username', 'trim'],
			['username', 'required'],
			['username', 'unique', 
				'targetClass' => '\common\models\User', 
				'message' => 'This username has already been taken.', 
				'when' => function($model) {
					return $model->id == null;
				}
			],
			['username', 'string', 'min' => 2, 'max' => 255],

			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 
				'targetClass' => '\common\models\User', 
				'message' => 'This email address has already been taken.',
				'when' => function($model) {
					return $model->id == null;
				}
			],

			['password', 'required'],
			['password', 'string', 'min' => 6],
			['role', 'required'],
		];
	}

	public function attributeLabels()
	{
		return [
			'username' => 'Username',
			'email' => 'E-mail',
			'password' => 'Password',
			'role' => 'User role'
		];
	}


	public function save()
	{
		if (!$this->validate()) {
			//валидацию сделали, так что потом делаем save(false)
			return null;
		}
		if($this->id == null){
			$user = new User();
		}else{
			$user = User::findOne($this->id);
		}
		
		$user->username = $this->username;
		$user->email = $this->email;
		$user->setPassword($this->password);
		$user->generateAuthKey();

		if($user->save(false)){
			//назначим юзеру роль
			$auth = Yii::$app->authManager;
			$auth->revokeAll($user->id);
			$userRole = $auth->getRole($this->role);
			$auth->assign($userRole, $user->id);
			return $user;
		}
		return null;
	}
}
