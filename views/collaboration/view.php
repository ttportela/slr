<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Collaboration */

$this->title = 'Collaboration with '.$model->user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collaborations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collaboration-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'project_id',
                'value' => function ($model) {
                    return $model->project->name;
                },
            ],
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
                    return $model->role == 0? 
                        'View' :
                        'Edit';
                },
            ],
        ],
    ]) ?>

</div>
