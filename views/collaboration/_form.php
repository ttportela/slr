<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dektrium\user\models\User;
use app\models\Collaboration;
use app\models\Project;

/* @var $this yii\web\View */
/* @var $model app\models\Collaboration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collaboration-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'project_id')->dropDownList(
        ArrayHelper::map(Project::find()->my()->asArray()->all(), 'id', 'name')
    ) ?>

    <!--?= $form->field($model, 'user_id')->dropDownList(
        ArrayHelper::map(User::find()->andWhere(['<>','id',Yii::$app->user->identity->id])->asArray()->all(), 'id', 'username')
    ) ?-->
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->radioList([Collaboration::$ROLE_VIEW=>'View ', Collaboration::$ROLE_EDIT=>'Edit ']); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
