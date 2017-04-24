<?php

//use yii\helpers\Html;
//use yii\grid\GridView;
//use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\home\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">
    <div class="row">
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-content text-center p-md">
                    <h4 class="m-b-lg">个人中心</h4>
                    <div class="text-left">
                        <p class="m-t-lg">管理个人昵称、联系电话</p>
                        <p class="m-t-lg">账号：<?php echo $model->account; ?></p>
                        <p class="m-t-lg">昵称：<?php echo $model->nickname; ?><a href="<?php echo Url::to(['/home/user/set-nickname']) ?>" class="btn btn-primary btn-sm pull-right">去设置</a></p>
                        <p class="m-t-lg">联系电话：<?php echo $model->phone_number ? $model->phone_number : '无'; ?><a href="<?php echo Url::to(['/home/user/set-phone-number']) ?>" class="btn btn-primary btn-sm pull-right">去绑定</a></p>
                        <a class="btn btn-primary full-width m-t-lg" href="<?php echo Url::to(['/home/user/password'])?>">修改密码</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-content text-center p-md">
                        <h4 class="m-b-lg">账号绑定</h4>
                        <div class="text-left">
                            <p class="m-t-lg">绑定telegram或potato，正式启用离线呼叫提醒功能，让人可以找到您，同时也能让您找到别人！</p>
                            <p class="m-t-lg">Potato：无<a class="btn btn-primary btn-sm pull-right">立即绑定</a></p>
                            <p class="m-t-lg">Telegram：无<a class="btn btn-primary btn-sm pull-right">立即绑定</a></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox float-e-margins">
                    <div class="ibox-content text-center p-md">
                        <h4 class="m-b-lg">紧急联系人</h4>
                        <div class="text-left">
                            <p class="m-t-lg">为账号设置2个紧急联系人，便于自己联系电话无法使用时其他人可以联系到自己</p>
                            <div class="fa-border p-sm">
                                <p class="m-t-sm">联系人：无</p>
                                <p class="m-t-sm">联系电话：无</p>
                            </div>
                            <div class="help-block"></div>
                            <div class="fa-border p-sm">
                                <p class="m-t-sm">联系人：无</p>
                                <p class="m-t-sm">联系电话：无</p>
                            </div>
                            <div class="text-right">
                                <a class="btn btn-primary m-t-md" href="">立即添加</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>