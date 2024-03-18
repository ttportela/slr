<?php

/* @var $this yii\web\View */
use app\models\Project;
use app\models\ProjectQuery;
use yii\helpers\Url;

$this->title = 'SLR';

if (!Yii::$app->user->isGuest) {
    $items = Project::find()->my()
          ->orderBy('id DESC')
          ->all();
    $items = array_merge($items, Project::find()->collaborations()
          ->orderBy('id DESC')
          ->all());
}

?>
<style type="text/css">
.bs-glyphicons {
    margin: 0 -10px 20px;
    overflow: hidden;
} 
.bs-glyphicons-list {
    padding-left: 0;
    list-style: none;
}
.bs-glyphicons li {
    float: left;
    width: 25%;
    height: 115px;
    padding: 10px;
    font-size: 10px;
    line-height: 1.4;
    text-align: center;
    background-color: #f9f9f9;
    border: 1px solid #fff;
}
.bs-glyphicons li {
    width: 12.5%;
    font-size: 12px;
}
.bs-glyphicons .glyphicon {
    margin-top: 5px;
    margin-bottom: 10px;
    font-size: 24px;
}

.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: 'Glyphicons Halflings';
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.bs-glyphicons .glyphicon-class {
    display: block;
    text-align: center;
    word-wrap: break-word;
}
</style>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hello!</h1>

        <p class="lead">Welcome to de Systematic Literature Review Helper.</p>
        
        <div class="row">
        <?php if (!Yii::$app->user->isGuest && !empty($items)) { ?>
            <h3>Select a project:</h3>
            <div class="bs-glyphicons"> 
                <ul class="bs-glyphicons-list"> 
                    <?php foreach ($items as $value) { ?>
                    <li><a href="<?= Url::toRoute(['/project/select', 'id' => $value->id]) ?>"><span class="glyphicon glyphicon-<?= (ProjectQuery::actualProject() == $value->id)? 'ok' : 'pushpin' ?>" aria-hidden="true"></span><span class="glyphicon-class"><?= $value->name ?></span></a></li>  
                    <?php } ?>
                </ul> 
            </div>
        <?php } ?>
        </div>
    </div>
    
</div>
