<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value'=>""]) ?>

    <?= $form->field($model, 'akses')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_daerah')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'kode_pdrb')->textInput() ?>

    <?= $form->field($model, 'authKey')->textInput() ?>
    
    <?= $form->field($model, 'kode_val')->textInput() ?>
    
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
