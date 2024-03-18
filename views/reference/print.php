<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ProjectQuery;

AppAsset::register($this);

$title = Yii::t('app', 'References');

if ($project = ProjectQuery::findActualProject()) {
    $project = $project->name;
    $this->title = $title .": ". $project;
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
<style type="text/css">
.truncate {
   max-width: 350px !important;
   min-width: 350px !important;
   overflow: visible;
   white-space: normal;
   width: auto;
}
</style>
</head>
<body>
<div class="reference-index">

    <h1><?= $title .": <span class='small'>". $project ."</span>" ?></h1>

    <?php 
        $exclude = (isset($_GET['exclude']))? explode(',', $_GET['exclude']) : [];
        $dataProvider->sort = false;
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $model,
        'columns' => [
            ['attribute' => 'id', 'visible' => !in_array('id', $exclude),],
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => isset($_GET['exclude']) && !in_array('serial', $exclude),
            ],
            [
                'attribute' => 'active',
                'format' => 'text',
                'value' => function($model) {
                    if ($model->isActive())
                        return Yii::t('app', 'Yes');
                    else 
                        return Yii::t('app', 'No');
                },
                'visible' => Yii::$app->controller->action->id == 'index' && !in_array('active', $exclude),
            ],
            ['attribute' => 'category', 'visible' => !in_array('category', $exclude),],
            ['attribute' => 'base', 'visible' => !in_array('base', $exclude),],
            ['attribute' => 'title', 'visible' => !in_array('title', $exclude),],
            ['attribute' => 'author', 'visible' => !in_array('author', $exclude),],
            ['attribute' => 'cite', 'format' => 'html', 'visible' => !in_array('cite', $exclude),],
            ['attribute' => 'citation', 'visible' => !in_array('citation', $exclude),], 
            ['attribute' => 'year', 'visible' => !in_array('year', $exclude),],
            ['attribute' => 'publisher', 'visible' => !in_array('publisher', $exclude),],
            [
                'attribute' => 'classification',
                'format' => 'html',
//                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('classification', $exclude),
            ],
            [
                'attribute' => 'validation',
                'format' => 'html',
//                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('validation', $exclude),
            ],
            [
                'attribute' => 'concept',
                'format' => 'html',
//                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('concept', $exclude),
            ],
            [
                'attribute' => 'strategy',
                'format' => 'html',
//                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('strategy', $exclude),
            ],
            [
                'attribute' => 'datasource',
                'format' => 'html',
//                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('datasource', $exclude),
            ],
            [
                'attribute' => 'objective',
                'format' => 'html',
//                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('objective', $exclude),
            ],
            [
                'attribute' => 'description',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('description', $exclude),
            ],
            [
                'attribute' => 'threat',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('threat', $exclude),
            ],
            [
                'attribute' => 'conclusion',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('conclusion', $exclude),
            ],
            [
                'attribute' => 'data',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('data', $exclude),
            ],
            [
                'attribute' => 'comment',
                'format' => 'html',
                'visible' => Yii::$app->controller->action->id != 'excluded' && !in_array('comment', $exclude),
                'contentOptions' => ['class' => 'truncate'],
            ],
            [
                'attribute' => 'exclusion',
                'format' => 'html',
                'visible' => Yii::$app->controller->action->id == 'excluded' && !in_array('exclusion', $exclude),
                'contentOptions' => ['class' => 'truncate'],
            ],
        ],
    ]); ?>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>