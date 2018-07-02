<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Manajemen Waktu KNPR';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($model_0){
    $tahun = $model_0->tahun;
    $triwulan = $model_0->triwulan;
    $putaran = $model_0->putaran;
    } 
    else {
        $tahun = null;
        $triwulan = null;
        $putaran = null;    
    }
        ?>
<div class="waktu-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true, 'value' => $tahun]) ?>

    <?= $form->field($model, 'triwulan')->textInput(['maxlength' => true, 'value' => $triwulan]) ?>

    <?= $form->field($model, 'putaran')->textInput(['maxlength' => true, 'value' => $putaran]) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'value'=>'Submit', 'name'=>'submit']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
