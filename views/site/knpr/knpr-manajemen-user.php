<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manajemen User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>


    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'responsive' => true,
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            'username',
            'akses',
            'kode_daerah',
            'kode_pdrb',
            'email',
            'telepon',
            ['class'=> 'yii\grid\ActionColumn']
        ],
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true
            ],
        'resizableColumns'=>true,
        'floatHeader'=>true,
        'floatOverflowContainer'=>true,
        'containerOptions' => ['style' => 'height:600px'],
        ]);  ?>

</div>
