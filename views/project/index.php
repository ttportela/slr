<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\ProjectQuery;
use app\models\Collaboration;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Project'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
//            'user_id',
            [
               'attribute' => 'id',
               'label' => 'Bibtex',
               'format'=>'raw',
               'value' => function ($model) {
                    return Html::a('<i class="glyphicon glyphicon-download-alt
" />', Url::toRoute(['/project/bib', 'id' => $model->id, 'down' => 1]),['target'=>'_blank', 'data-pjax'=>"0"] );
               }
            ],

            ['class' => 'yii\grid\ActionColumn',
             'template' => "{select} {view} {update} {delete}",
             'buttons' => [
                'select' => function($url, $model, $key) {
                    if (ProjectQuery::actualProject() == $model->id)
                        return '<span class="glyphicon glyphicon-ok" aria-label="Selected" />';
                    return Html::a('<span class="glyphicon glyphicon-pushpin" aria-label="Select" />' , ['/project/select', 'id'=>$model->id]);
                }
             ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<br/>

<div class="project-index">

    <h2>Collaborations</h2>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProviderCollab,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

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
            /*'id',
            'name',
            [
                'attribute' => 'user',
                'value' => function ($model) {
                    return $model->user->profile->name? 
                        $model->user->profile->name :
                        $model->user->username;
                },
            ],
            [
                'label' => 'Access',
                'value' => function ($model) {
                    return $model->collaboration->role == Collaboration::$ROLE_VIEW? 
                        'View' :
                        'Edit';
                },
            ],*/

            ['class' => 'yii\grid\ActionColumn',
             'template' => "{select}",
             'buttons' => [
                'select' => function($url, $model, $key) {
                    if (ProjectQuery::actualProject() == $model->project_id)
                        return '<span class="glyphicon glyphicon-ok" aria-label="Selected" />';
                    return Html::a('<span class="glyphicon glyphicon-pushpin" aria-label="Select" />' , ['/project/select', 'id'=>$model->project_id]);
                }
             ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
