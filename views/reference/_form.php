<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Reference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reference-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <ul class="nav nav-tabs">
      <li <?= ($model->isNewRecord)? 'class="active"':''?>><a data-toggle="tab" href="#basic">Reference</a></li>
      <li <?= ($model->isNewRecord)? '':'class="active"'?>><a data-toggle="tab" href="#desc">Classification</a></li>
      <li><a data-toggle="tab" href="#eval">Evaluation</a></li>
    </ul>

    <div class="tab-content">
      <div id="basic" class="tab-pane fade in <?= ($model->isNewRecord)? 'active':''?>">
        <h3>Reference Data</h3>
          
    <?= $form->field($model, 'bib')->textarea(['rows' => 6, 'onchange' => 'parse(this.value)']) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'base')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'citation')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'abstract')->textarea(['rows' => 6]) ?>
          
      </div>
      <div id="desc" class="tab-pane fade in <?= ($model->isNewRecord)? '':'active'?>">
        <h3>Classification</h3>
          
    <?= $form->field($model, 'active')->radioList(['1'=>'Yes ',0=>'No '], ['onclick' => 'toogleIn()']); ?>
          
    <?= $form->field($model, 'classification')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'validation')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'concept')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'strategy')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'datasource')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'objective')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
          
      </div><div id="eval" class="tab-pane fade in">
        <h3>Evaluation</h3>
          
    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'threat')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'conclusion')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'data')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'comment')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'exclusion')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>
          
      </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
function parse(value) {
    var entry = bibtexParse.toJSON(value);
    console.log(entry);
    
//    document.getElementById("reference-citation").innerHTML =  
//        (entry[0].entryTags.author + " ("+entry[0].entryTags.year+"). " + entry[0].entryTags.title + ". " + 
//        entry[0].entryTags.journal + ", ("+entry[0].entryTags.volume+")"+entry[0].entryTags.number+", " + 
//        entry[0].entryTags.pages + ". https://doi.org/" + entry[0].entryTags.doi).replace(/[\{\}\\]/g, '');
    
    document.getElementById("reference-citation").innerHTML = '{' + entry[0].citationKey + '}';
    
    document.getElementById("reference-title").setAttribute("value", entry[0].entryTags.title.replace(/[\{\}\\]/g, ''));
    document.getElementById("reference-author").setAttribute("value", entry[0].entryTags.author.replace(/[\{\}\\]/g, ''));
    document.getElementById("reference-year").setAttribute("value", entry[0].entryTags.year.replace(/[\{\}\\]/g, ''));
    document.getElementById("reference-publisher").setAttribute("value", entry[0].entryTags.journal.replace(/[\{\}\\]/g, ''));
    document.getElementById("reference-abstract").innerHTML = (entry[0].entryTags.abstract.replace(/[\{\}\\]/g, ''));
}
    
function toogleIn() {
//    alert(document.getElementsByName("Reference[active]")[1].checked);
    var value = document.getElementsByName("Reference[active]")[1].checked == true;
    document.getElementsByClassName("field-reference-comment")[0].style.display = (value)? 'inherit' : 'none';
    document.getElementsByClassName("field-reference-exclusion")[0].style.display = (!value)? 'inherit' : 'none';
}

toogleIn();
</script>
