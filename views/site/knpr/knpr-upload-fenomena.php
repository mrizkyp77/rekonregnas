<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use kartik\file\FileInput;
$this->title = 'Upload Data PDRB Provinsi';

?>
<div class="site-about">

    <p>
        Aturan upload data: <br>
        1. Data fenomena yang diupload sesuai dengan wewenang pengupload (untuk KNPR ('0000' atau '0100')) <br>
        2. Data fenomena yang diupload sesuai dengan form yang disediakan <br>
        3. Data fenomena masih belum pernah terupload di database <br>
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