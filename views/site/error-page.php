<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'Halaman Error';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php 
        foreach ($error as $id_error){ 
            ?> <center>
                    <div style="background-color: red; width: 500px; height: 100px; align-self: auto; display: flex;
    align-items: center; justify-content: center; position:relative; padding: 20px; border: 10px solid graytext; ">
                        <?php echo $id_error; ?> 
                    </div>
            </center>
                <?php 
            }
?>