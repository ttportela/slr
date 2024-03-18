<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ListView;
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
//        $exclude = (isset($_GET['exclude']))? explode(',', $_GET['exclude']) : [];
        $dataProvider->sort = false;
//        $ct = 0;
    ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
//        'options' => [
//            'tag' => 'div',
//            'class' => 'list-wrapper',
//            'id' => 'list-wrapper',
//        ],
//        'layout' => "{pager}\n{items}\n{summary}",
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('view',['model' => $model,'key' => $key,'index' => $index]);
         }
    ]);
    ?>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>