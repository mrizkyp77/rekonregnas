<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Fenomena Multiregional';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Ini halaman fenomena PDRB Multiregional
    </p>
</div>
<?php if (Yii::$app->user->identity->authKey == 1){
    
$form = ActiveForm::begin(); ?>
<div class='container-fluid'>
    <div class='col-lg-4'>
      <?= Html::dropDownList('opsi_pdrb', 'id_pdrb', $items_0) ?> 
    </div>
    <div class='col-lg-1'>
      <?= Html::dropDownList('opsi_tahun', 'tahun',$items_1) ?>
    </div>
    <div class='col-lg-1'>
      <?= Html::dropDownList('opsi_triwulan', 'triwulan',$items_2) ?>
    </div>
    <div class='col-lg-1'>
        <?=Html::submitButton('Show',['name'=>'Button', 'value'=>'show' ,'class'=>'btn btn-primary'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="container-fluid">
<?php if($button == 'show'){ ?>   
    <?= GridView::widget([
        'responsive' => true,
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true
            ],
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_prov',
                'label' => 'Id',
                'header' => '<center> Id </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'nama_provinsi',
                'label' => 'Provinsi',
                'header' => '<center> Provinsi </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_pdrb',
                'label' => 'PDRB',
                'header' => '<center> Id PDRB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'tahun',
                'label' => 'Tahun',
                'header' => '<center> Tahun </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'triwulan',
                'label' => 'Triwulan',
                'header' => '<center> Triwulan </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'isi_fenom',
                'label' => 'Isi Fenom',
                'header' => '<center> Fenom </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'isi_tipe',
                'label' => 'Isi Tipe',
                'header' => '<center> Tipe </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'isi_sumber',
                'label' => 'Isi Sumber',
                'header' => '<center> Sumber </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'isi_indikasi',
                'label' => 'Indikasi',
                'header' => '<center> Indikasi </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'timestamp',
                'label' => 'Timestamp',
                'header' => '<center> Waktu Upload </center>'
            ],
        ],
        'floatHeader'=>true,
        'floatOverflowContainer'=>true,
        'containerOptions' => ['style' => 'width: 1200px; height:600px'],
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
<?php } 

} else { ?>
    <div style="background-color: red; width: 500px; height: 50px; align-self: auto; display: flex;
    align-items: center; justify-content: center; position:relative; padding: 20px; border: 10px solid graytext; ">
        Maaf, Anda tidak berhak untuk melihat data. Silahkan hubungi admin.
    </div>
<?php }
?>

</div>

