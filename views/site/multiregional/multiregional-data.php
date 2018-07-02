<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Data Multiregional';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Ini halaman data PDRB Multiregional
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
                'attribute' => 'pdrb_b',
                'label' => 'ADHB',
                'header' => '<center> ADHB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'pdrb_k',
                'label' => 'ADHK',
                'header' => '<center> ADHK </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'diskrepansi_b',
                'label' => 'Diskrepansi B',
                'header' => '<center> Diskrepansi B <br> PDB-PDRB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'diskrepansi_k',
                'label' => 'Diskrepansi K',
                'header' => '<center> Diskrepansi K <br> PDB-PDRB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_b',
                'label' => 'StrukturEkoK Sekarang',
                'header' => '<center> Struktur Ekonomi <br> ADHB <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_b_0',
                'label' => 'StrukEkoB Sebelum',
                'header' => '<center> Struktur Ekonomi <br> ADHB <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_k',
                'label' => 'StrukturEkoK Sekarang',
                'header' => '<center> Struktur Ekonomi <br> ADHK <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_k_0',
                'label' => 'StrukEkoK Sebelum',
                'header' => '<center> Struktur Ekonomi <br> ADHK <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_q',
                'label' => 'Laju Pertumbuhan (q-to-q) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> (q-to-q) <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_q_0',
                'label' => 'Laju Pertumbuhan (q-to-q) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> (q-to-q) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_y',
                'label' => 'Laju Pertumbuhan (y-to-y) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> (y-to-y) <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_y_0',
                'label' => 'Laju Pertumbuhan (q-to-q) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> (y-to-y) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_c',
                'label' => 'Laju Pertumbuhan (c-to-c) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> (c-to-c) <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_c_0',
                'label' => 'Laju Pertumbuhan (c-to-c) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> (c-to-c) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'implisit',
                'label' => 'Indeks Implisit Sekarang',
                'header' => '<center> Indeks Implisit <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'implisit_0',
                'label' => 'Indeks Implisit Sebelum',
                'header' => '<center> Indeks Implisit <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_q',
                'label' => 'Laju Pertumbuhan Implisit (q-to-q) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (q-to-q) <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_q_0',
                'label' => 'Laju Pertumbuhan Implisit (q-to-q) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (q-to-q) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_y',
                'label' => 'Laju Pertumbuhan Implisit (y-to-y) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (y-to-y) <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_y_0',
                'label' => 'Laju Pertumbuhan Implisit (y-to-y) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (y-to-y) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_c',
                'label' => 'Laju Pertumbuhan Implisit (c-to-c) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (c-to-c) <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_c_0',
                'label' => 'Laju Pertumbuhan Implisit (c-to-c) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (c-to-c) <br> Sebelum </center>'
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'flag',
                'trueLabel' => 1, 
                'falseLabel' => 0,
                'trueIcon' =>  '<span class="glyphicon glyphicon-warning-sign"></span>',
                'falseIcon' => '<span class="glyphicon glyphicon-ok"></span>'   
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'label' => 'Status',
                'header' => '<center> Status </center>'
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

