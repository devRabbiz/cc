<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "manager".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $password
 * @property string $account
 * @property string $nickname
 * @property integer $role_id
 * @property integer $status
 * @property string $remark
 * @property string $login_ip
 * @property integer $create_id
 * @property integer $update_id
 * @property integer $create_at
 * @property integer $update_at
 */
class Manager extends CActiveRecord implements IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account','nickname', 'role_id','password'], 'required'],
            ['account', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/','message'=>'账号至少包含8个字符，至少包括以下2种字符：大写字母、小写字母、数字、符号'],
            ['password', 'match', 'pattern' => '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{8,}$/','message'=>'密码至少包含8个字符，至少包括以下2种字符：大写字母、小写字母、数字、符号'],
            [['nickname'],'string','length'=>[2,20],'message'=>'昵称至少输入2个汉字'],
            [['account', 'nickname', 'remark', 'auth_key','password'], 'string'],
            [['role_id', 'status', 'create_id', 'update_id', 'create_at', 'update_at'], 'integer'],
            [['login_ip'], 'string', 'max' => 64],
            ['account','validateExist'],
        ];
    }

    public function validateExist($attribute)
    {
        $rows = Manager::find()->select(['account'])->indexBy('id')->column();

        $accounts = [];
        foreach ($rows as $i => $v)
        {
            $accounts[] = Yii::$app->security->decryptByKey(base64_decode($v), Yii::$app->params['inputKey']);
        }

        if(in_array($this->account, $accounts)){
            $this->addError($attribute, '管理员账号已存在');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => '管理员账号',
            'password' => '密码',
            'nickname' => '管理员昵称',
            'role_id' => '管理员角色',
            'status' => '管理员状态',
            'remark' => '备注',
            'login_ip' => '本次登录IP',
            'create_id' => 'Create ID',
            'update_id' => 'Update ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    public function getStatuses(){
        return [
            0 => '正常',
            1 => '作废',
            2 => '冻结',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $uid = Yii::$app->user->id ? Yii::$app->user->id : 0;
            if ($this->isNewRecord) {
                $this->create_at = $_SERVER['REQUEST_TIME'];
                $this->update_at = $_SERVER['REQUEST_TIME'];
                $this->create_id = $uid;
                $this->auth_key  = Yii::$app->security->generateRandomString();
                $this->account  = base64_encode(Yii::$app->security->encryptByKey($this->account, Yii::$app->params['inputKey']));
                $this->password && $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                $this->nickname && $this->nickname = base64_encode(Yii::$app->security->encryptByKey($this->nickname, Yii::$app->params['inputKey']));
            }else{
                if(!empty(array_column(Yii::$app->request->post(),'password'))){
                    $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
                }
                $this->account = base64_encode(Yii::$app->security->encryptByKey($this->account, Yii::$app->params['inputKey']));
                $this->nickname = base64_encode(Yii::$app->security->encryptByKey($this->nickname, Yii::$app->params['inputKey']));
                $this->update_at = $_SERVER['REQUEST_TIME'];
                $this->update_id = $uid;
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     * @return ManagerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ManagerQuery(get_called_class());
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->account = Yii::$app->security->decryptByKey(base64_decode($this->account), Yii::$app->params['inputKey']);
        $this->nickname && $this->nickname = Yii::$app->security->decryptByKey(base64_decode($this->nickname), Yii::$app->params['inputKey']);
    }

    public function getRoles()
    {
        return Role::find()->select(['name','id'])->where(['status'=>0])->indexBy('id')->column();
    }

    /**
     * 获取创建人
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne($this::className(), ['id' => 'create_id'])->alias('creator');
    }

    /**
     * 获取最后修改人
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne($this::className(), ['id' => 'update_id'])->alias('updater');
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id'=>'role_id']);
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}
