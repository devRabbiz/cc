<?php

namespace app\modules\home\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $rePassword;
    public $code;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['rePassword', 'password', 'username'], 'required'],
            [['rePassword', 'password'], 'string', 'length' => [8,15]],
            ['rePassword', 'compare', 'compareAttribute'=>'password'],
            ['username', 'email'],
            ['username', 'validateExist'],
            /*[
                'username',//要检验的字段
                'exist',
                'targetClass' => 'app\modules\home\models\User', // 如果是本表内的字段, 这行就不用写
                'targetAttribute' => ['username' => 'account'], // username字段 必须存在于targetClass的account列
                'message' => '账号已占用'
            ],*/

            ['password', 'match', 'pattern' => '/(?-i)(?=^.{8,}$)((?!.*\s)(?=.*[A-Z])(?=.*[a-z]))(?=(1)(?=.*\d)|.*[^A-Za-z0-9])^.*$/', 'message'=>'密码必须包含大写字母、小写字母和数字。'],
            ['code', 'captcha', 'message'=>'验证码输入不正确', 'captchaAction'=>'/home/register/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'password' => '新密码',
            'rePassword' => '重复密码输入',
            'code'     => '验证码',
        ];
    }

    public function register()
    {
        $user = new User();
        $user->account = $this->username;
        $user->password = $this->password;
        return $user->save();
    }

    public function validateExist($attribute)
    {
        $rows = User::find()->select(['account'])->indexBy('id')->column();

        $accounts = [];
        foreach ($rows as $i => $v)
        {
            $accounts[] = Yii::$app->security->decryptByKey(base64_decode($v), Yii::$app->params['inputKey']);
        }

        if(in_array($this->username, $accounts)){
            $this->addError($attribute, '此账号已被占用');
        }
    }

}
