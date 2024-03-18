<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Reference */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Included References'), 'url' => ['included']];
$this->params['breadcrumbs'][] = $this->title;

$exclude = (isset($_GET['exclude']))? explode(',', $_GET['exclude']) : [];

?>
<div class="reference-view">

    <?php if (Yii::$app->controller->action->id == 'printall') { ?>
    <h3><?= $index +1 ?> - <?= Html::encode($this->title) ?></h3>
    <?php } else { ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success']) ?> | 
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute' => 'id', 'visible' => !in_array('id', $exclude),],
//            [
//                'class' => 'yii\grid\SerialColumn',
//                'visible' => isset($_GET['exclude']) && !in_array('serial', $exclude),
//            ],
            [
                'attribute' => 'active',
                'format' => 'text',
                'value' => function($model) {
                    if ($model->isActive())
                        return Yii::t('app', 'Yes');
                    else 
                        return Yii::t('app', 'No');
                },
                'visible' => !in_array('active', $exclude),
            ],
            ['attribute' => 'category', 'visible' => !in_array('category', $exclude),],
            ['attribute' => 'base', 'visible' => !in_array('base', $exclude),],
            ['attribute' => 'cite', 'format' => 'html', 'visible' => !in_array('cite', $exclude),],
            ['attribute' => 'citation', 'visible' => !in_array('citation', $exclude),], 
            ['attribute' => 'title', 'visible' => !in_array('title', $exclude),],
            ['attribute' => 'author', 'visible' => !in_array('author', $exclude),],
            ['attribute' => 'year', 'visible' => !in_array('year', $exclude),],
            ['attribute' => 'publisher', 'visible' => !in_array('publisher', $exclude),],
            ['attribute' => 'abstract', 'visible' => !in_array('abstract', $exclude),],
            [
                'label' => 'Qualis',
                'format' => 'html',
                'value' => '...', 
                'contentOptions' => ['id' => 'extrato'],
            ],
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
                'visible' => $model->isActive() && !in_array('comment', $exclude),
                'contentOptions' => ['class' => 'truncate'],
            ],
            [
                'attribute' => 'exclusion',
                'format' => 'html',
                'visible' => !$model->isActive() && !in_array('exclusion', $exclude),
                'contentOptions' => ['class' => 'truncate'],
            ],
        ],
    ]) ?>
    
    <?php if (Yii::$app->controller->action->id == 'printall' && isset($_GET['break'])) { ?>
    <p style="page-break-after: always;">&nbsp;</p>
    <?php } else { ?>
    <hr/>
    <?php } ?>

</div>
<script>
window.onload = function(e){ 
    loadDoc(); 
}
function loadDoc() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      myFunction(this);
    }
  };
//  alert('http://slr.tarlis.com.br/index.php?r=qualis/view&titulo=<?= $model->publisher ?>');
  xhttp.open("GET", 'http://slr.tarlis.com.br/index.php?r=qualis/view&titulo=<?= $model->publisher ?>', true);
  xhttp.send();
}
function myFunction(xml) {
  var str;
  if (xml !== null) {
      var resp = JSON.parse(xml.responseText);   
      str = resp.extrato;
      str = str + " (" + resp.titulo + ")";
  } else {
      str = "Não Encontrado";
  }
  document.getElementById("extrato").innerHTML = str;
}
</script>