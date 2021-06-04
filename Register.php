<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Rgirls;

class Register extends Model
{
  public $username;
  public $email;
  public $pass;
  public $passr;

  public function rules()
  {
    return [
      [['email', 'username', 'pass', 'passr'], 'required'],
      [['email', 'pass', 'passr'], 'trim'],
      [['email'], 'email'],
      [['email'], 'unique', 'targetClass' => Rgirls::className(), 'message' => 'Пользователь с таким email адресом уже зарегистрирован!'],
      [['username'], 'unique', 'targetClass' => Rgirls::className(), 'message' => 'Пользователь с таким логином уже зарегистрирован!'],
      [['username'], 'validateUsername'],
      [['pass'], 'compare', 'compareAttribute' => 'passr', 'message' => 'Пароли не совпадают!'],
      [['pass'], 'string', 'min' => 5, 'message' => 'Пароль должен содержать минимум 5 символов!'],
      [['pass'], 'validatePassword1'],
    ];
  }

  public function userRegister($email, $pass, $username)
  {
    $rgirls = new Rgirls();

    $rgirls->email = $email;
    $rgirls->username = $username;
    $rgirls->created_at = $time = time();
    $rgirls->updated_at = $time;
    $rgirls->password = $pass;
    $rgirls->status = Rgirls::NEW_REGISTRED_GIRLS;

    return  $rgirls->save();
  }

  public function getRegistredId($email)
  {
    $sql = "SELECT id FROM rgirls WHERE email = :email";
    return Yii::$app->db->createCommand($sql)->bindValue(':email', $email)->queryScalar();
  }

  public function validatePassword1($attribute, $params)
  {
    $pattern = '/([A-Za-z0-9]+)/';
    if(!preg_match($pattern, $this->pass)){
      $this->addError($this->pass, 'Пароль должен содержать только латинские буквы, и(или) цифры');
    }

  }

  public function validatePassword2($attribute, $params)
  {
    $pattern = '/([A-Z]+)([a-z]+)([0-9]+)/';
    if(!preg_match($pattern, $this->pass)){
      $this->addError($this->pass, 'Пароль должен содержать хотябы одну заглавную букву и цифру');
    }

  }

  public function validateUsername()
  {
    $pattern = '/([A-Za-z0-9]+)/';
    if(!preg_match($pattern, $this->username)){
      $this->addError($this->username, 'Логин должен содержать только латинские буквы, и(или) цифры');
    }
  }

}
