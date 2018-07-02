<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\file\FileInput;
$this->title = 'Upload Fenomena Multiregional';

?>
<div class="site-about">

    <p>
        Aturan upload fenomena: <br>
        1. Data yang diupload sesuai dengan wewenang pengupload (sesuai dengan wilayah kerja) <br>
        2. Data yang diupload sesuai dengan form yang disediakan <br>
    </p>
</div>



<!--<div>
        <input type="submit" class="btn btn-info" value="Submit">
</div>-->

<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    <?= $form->field($model,'file')->fileInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Save',['class'=>'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>