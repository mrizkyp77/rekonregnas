<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Profil Pengguna';
?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['prov-update-user', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email',
            'telepon'
        ],
    ]) ?>

</div>
