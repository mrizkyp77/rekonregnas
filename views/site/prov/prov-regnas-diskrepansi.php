<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\BootstrapAsset;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Regnas Diskrepansi Prov';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">
    <p>
        Ini halaman regnas diskrepansi Prov
    </p>
</div>
<?php $form = ActiveForm::begin(); ?>
<div class='container-fluid'>
    <div class='col-lg-1'>
      <?= Html::dropDownList('opsi_tahun', 'tahun',$items_1) ?>
    </div>
    <div class='col-lg-1'>
      <?= Html::dropDownList('opsi_triwulan', 'triwulan',$items_2) ?>
    </div>
    <div class='col-lg-1'>
        <?=Html::submitButton('Show',['name'=>'Button', 'value'=>'show' ,'class'=>'btn btn-primary'])?>
    </div>
    <div class='col-lg-1'>
        <?=Html::submitButton('Update',['name'=>'Button', 'value'=>'update' ,'class'=>'btn btn-primary'])?>
    </div>
    <div class='col-lg-1'>
        <?=Html::submitButton('Finalisasi',['name'=>'Button', 'value'=>'finalisasi' ,'class'=>'btn btn-primary'])?>
    </div>
    <div class='col-lg-1'>
        <?=Html::submitButton('Definalisasi',['name'=>'Button', 'value'=>'definalisasi' ,'class'=>'btn btn-primary'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div style="padding: 30px">
<?php if($button == 'show'){ ?>   
    <?= GridView::widget([
        'responsive' => true,
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true
            ],
        'columns' => $kolom,
        'floatHeader'=>true,
        'floatOverflowContainer'=>true,
        'containerOptions' => ['style' => 'height:600px'],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
        ],
        'toolbar' =>  [
            '{export}'
            ],
        'exportConfig' => [
            GridView::EXCEL=>[
            'filename' => $nama,
            'showPageSummary' => true,
                ]
            ],
        ]);  ?>
<?php } ?>

</div>

