<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CActiveRecord;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property string $remark
 * @property integer $create_id
 * @property integer $update_id
 * @property integer $create_at
 * @property integer $update_at
 */
class Role extends CActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'unique'],
            [['name'],'required'],
            [['name','remark'], 'string', 'length' => [2, 8]],
            [['create_id', 'update_id', 'create_at', 'update_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色名',
            'remark' => '角色备注',
            'create_id' => 'Create ID',
            'update_id' => 'Update ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @inheritdoc
     * @return RoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoleQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        $uid = Yii::$app->user->id ? Yii::$app->user->id : 0;
        if($this->isNewRecord){
            $this->create_id = $uid;
            $this->update_id = $uid;
            $this->create_at = $_SERVER['REQUEST_TIME'];
            $this->update_at = $_SERVER['REQUEST_TIME'];
        }else{
            $this->update_id = $uid;
            $this->update_at = $_SERVER['REQUEST_TIME'];
        }
        return true;
    }

    /**
     * 获取创建人
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Manager::className(), ['id' => 'create_id'])->alias('creator');
    }

    /**
     * 获取最后修改人
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(Manager::className(), ['id' => 'update_id'])->alias('updater');
    }
}
