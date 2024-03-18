<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ProjectQuery;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = Yii::t('app', 'References');

if (Yii::$app->controller->action->id == 'included') {
    $title = Yii::t('app', 'Included ') . $title;
} else if (Yii::$app->controller->action->id == 'excluded') {
    $title = Yii::t('app', 'Excluded ') . $title;
}

if ($project = ProjectQuery::findActualProject()) {
    $project_id = $project->id;
    $project = $project->name;
    $this->title = $title .": ". $project;
}

$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
.truncate {
   max-width: 350px !important;
   min-width: 350px !important;
   overflow: hidden;
   white-space: nowrap;
   text-overflow: ellipsis;
}

.truncate:hover{
   max-width: 350px !important;
   min-width: 350px !important;
   overflow: visible;
   white-space: normal;
   width: auto;
}
</style>
<div class="reference-index">

    <h1><?= $title .": <span class='small'>". $project ."</span>" ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Reference'), ['create'], ['class' => 'btn btn-success']) ?>
         |
        <?= Html::a(Yii::t('app', 'All'), ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Included'), ['included'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Excluded'), ['excluded'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
    $exclude = ['serial']; //(isset($_GET['exclude']))? explode(',', $_GET['exclude']) : [];
    if (!isset($_GET['exclude'])) {
        $cookies = Yii::$app->request->cookies;

        if ($cookies->has('exclude_cols_ref')) {
            $exclude = explode(',', $cookies->get('exclude_cols_ref'));
        }
    } else {
        $exclude = explode(',', $_GET['exclude']);
        $cookies = Yii::$app->response->cookies;

        // add a new cookie to the response to be sent
        $cookies->add(new \yii\web\Cookie([
            'name' => 'exclude_cols_ref',
            'value' => $_GET['exclude'],
        ]));
    }
    //        Yii::$app->helper->trace($exclude);
    ?>
    <p>
        <?= Html::a('', ['print', 'exclude'=>implode(",", $exclude)], ['title' => 'Print Ref. Table', 'class' => 'btn btn-info glyphicon glyphicon-print', 'id' => 'btnprint', 'target' => '_blank']) ?>
        <?= Html::a('*', ['printall', 'exclude'=>implode(",", $exclude)], ['title' => 'Print Ref. List', 'class' => 'btn btn-info glyphicon glyphicon-print', 'id' => 'btnprintall', 'target' => '_blank']) ?>
        |
        <?= Html::a('', Url::toRoute(['/project/bib', 'id' => $project_id, 'down' => 0]),['title' => 'Download BIB File', 'class' => 'btn btn-info glyphicon glyphicon-download-alt', 'id' => 'downbib', 'target'=>'_blank', 'data-pjax'=>"0"] ); ?>
    </p>
    
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['attribute' => 'id', 'visible' => !in_array('id', $exclude),],
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => !in_array('serial', $exclude),
            ],
            ['class' => 'yii\grid\ActionColumn'],
//            'user_id',
//            'active',
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
//            'bib:ntext',
//            'base',
//            'title',
//            'author',
//            'citation:ntext',
//            'year',
//            'publisher',
            ['attribute' => 'category', 'visible' => !in_array('category', $exclude),],
            ['attribute' => 'base', 'visible' => !in_array('base', $exclude),],
            ['attribute' => 'title', 'visible' => !in_array('title', $exclude),],
            ['attribute' => 'author', 'visible' => !in_array('author', $exclude),],
            ['attribute' => 'cite', 'format' => 'html', 'visible' => !in_array('cite', $exclude),],
            ['attribute' => 'citation', 'format' => 'ntext', 'visible' => !in_array('citation', $exclude),],
            ['attribute' => 'year', 'visible' => !in_array('year', $exclude),],
            ['attribute' => 'publisher', 'visible' => !in_array('publisher', $exclude),],
//            [
//                'label' => 'Qualis',
//                'format' => 'raw',
//                'value' => function($model) {
//                    return '<div id="extrato-'.$model->id.'" onload="loadDoc(\''.$model->publisher.'\','.$model->id.');">...</div>';
//                },
////                'visible' => !in_array('qualis', $exclude),
//            ],
//            'abstract:ntext',
            [
                'attribute' => 'classification',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('classification', $exclude),
            ],
            [
                'attribute' => 'validation',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('validation', $exclude),
            ],
            [
                'attribute' => 'concept',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('concept', $exclude),
            ],
            [
                'attribute' => 'strategy',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('strategy', $exclude),
            ],
            [
                'attribute' => 'datasource',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
                'visible' => !in_array('datasource', $exclude),
            ],
            [
                'attribute' => 'objective',
                'format' => 'html',
                'contentOptions' => ['class' => 'truncate'],
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
//            'description:html',
//            'threat:html',
//            'conclusion:html',
//            'data:html',
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
//            'comment:ntext',
//            'exclusion:ntext',
        ],
    ]); ?>
    
    Show Columns:
    <?php 
        echo '<label class="checkbox-inline">';
        echo Html::checkbox('exclude', !in_array('serial', $exclude), ['value' => 'serial','label' => '#', 'id' => 'exclude', 'onclick' => 'updateColumns(this);']);
        echo '</label>';
        foreach ($model->attributeLabels() as $key => $val) {
            echo '<label class="checkbox-inline">';
            echo Html::checkbox('exclude', !in_array($key, $exclude), ['value' => $key,'label' => $val, 'id' => 'exclude', 'onclick' => 'updateColumns(this);']);
            echo '</label>';
        }
    ?>
    <br/>
    <?= Html::a('', ['index', 'exclude'=>implode(",", $exclude)], ['class' => 'btn btn-info glyphicon glyphicon-refresh', 'id' => 'btnexclude']) ?>
    <?php Pjax::end(); ?>
</div>
<script type="text/javascript">
    function updateColumns(e) {
            var checks = [];
            var checkboxes = document.querySelectorAll("input[name='exclude']");
            for (var i = 0; i < checkboxes.length; i++) {
                if (!checkboxes[i].checked)
                    checks.push(checkboxes[i].value);
            }
            document.getElementById("btnexclude").href = '<?=Url::toRoute(['index', 'id' => $model->id])?>&exclude='+checks.join(",");
            document.getElementById("btnprint").href = '<?=Url::toRoute(['print', 'id' => $model->id])?>&exclude='+checks.join(",");
            document.getElementById("btnprintall").href = '<?=Url::toRoute(['printall', 'id' => $model->id])?>&exclude='+checks.join(",");
    };
</script>