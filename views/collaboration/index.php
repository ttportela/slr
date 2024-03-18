<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Collaboration;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Collaborations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collaboration-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Collaboration'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'project.name',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->profile->name? 
                        $model->user->profile->name :
                        $model->user->username;
                },
            ],
            [
                'attribute' => 'role',
                'value' => function ($model) {
                    return $model->role == Collaboration::$ROLE_VIEW? 
                        'View' :
                        'Edit';
                },
            ],
//            'role',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
