<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn', 'header'=>'序号'],

                'account:ntext',
                'nickname:ntext',
                'un_call_number',
                'un_call_by_same_number',

                ['header'=>'时间(min)', 'value'=>function($data){
                    return $data['long_time'];
                }],

                'phone_number',
                'telegram_number',
                'potato_number',

                ['header'=>'紧急联系人一/电话', 'format'=>'html', 'value'=>function($data){
                    if(!empty($data->urgent_contact_number_one)){
                        return $data->urgent_contact_person_one. '<br> +' . $data->urgent_contact_one_country_code
                            . $data->urgent_contact_number_one;
                    }
                    return '';
                }],

                ['header'=>'紧急联系人二/电话', 'format'=>'html', 'value'=>function($data){
                    if(!empty($data->urgent_contact_number_two)){
                        return $data->urgent_contact_person_two. '<br> +' . $data->urgent_contact_two_country_code
                            . $data->urgent_contact_number_two;
                    }
                    return '';
                }],

                ['header'=>'注册IP/注册时间', 'format'=>'html', 'value'=>function($data){
                    return $data->reg_ip. '<br> ' . date('Y-m-d H:i:s' , $data->reg_time);
                }],

                /*['header'=>'最后登录IP/最后登录时间', 'format'=>'html', 'value'=>function($data){
                    return $data->urgent_contact_person_two. '<br> +' . $data->urgent_contact_two_country_code
                        . $data->urgent_contact_number_two;
                }],*/

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{harassment}',
                    'buttons' => [
                        'harassment' => function($url){
                            return Html::a('防骚扰',$url);
                        },
                    ],
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>

</div>
