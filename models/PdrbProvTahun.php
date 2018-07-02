<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pdrb_prov_tahun".
 *
 * @property string $id_prov
 * @property string $id_pdrb
 * @property int $tahun
 * @property double $pdrb_b
 * @property double $pdrb_k
 * @property double $share_b
 * @property double $share_k
 * @property double $laju_p
 * @property double $implisit
 * @property double $laju_imp
 * @property double $diskrepansi_b
 * @property double $diskrepansi_k
 * @property int $flag
 * @property string $status
 * @property string $timestamp
 */
class PdrbProvTahun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pdrb_prov_tahun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_prov', 'id_pdrb', 'tahun', 'timestamp'], 'required'],
            [[ 'flag'], 'default', 'value' => null],
            [[ 'tahun', 'flag'], 'integer'],
            [['pdrb_b', 'pdrb_k', 'share_b', 'share_k', 'laju_p', 'implisit', 'laju_imp', 'diskrepansi_b', 'diskrepansi_k'], 'number'],
            [['timestamp'], 'safe'],
            [['id_prov'], 'string', 'max' => 4],
            [['id_pdrb'], 'string', 'max' => 7],
            [['status'], 'string', 'max' => 10],
            [['id_prov', 'id_pdrb', 'tahun'], 'unique', 'targetAttribute' => ['id_prov', 'id_pdrb', 'tahun']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prov' => Yii::t('app', 'Id Prov'),
            'id_pdrb' => Yii::t('app', 'Id Pdrb'),
            'tahun' => Yii::t('app', 'Tahun'),
            'pdrb_b' => Yii::t('app', 'Pdrb B'),
            'pdrb_k' => Yii::t('app', 'Pdrb K'),
            'share_b' => Yii::t('app', 'Share B'),
            'share_k' => Yii::t('app', 'Share K'),
            'laju_p' => Yii::t('app', 'Laju P'),
            'implisit' => Yii::t('app', 'Implisit'),
            'laju_imp' => Yii::t('app', 'Laju Imp'),
            'diskrepansi_b' => Yii::t('app', 'Diskrepansi B'),
            'diskrepansi_k' => Yii::t('app', 'Diskrepansi K'),
            'flag' => Yii::t('app', 'Flag'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PdrbProvTahunQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PdrbProvTahunQuery(get_called_class());
    }
    
    public function getAdhbByTime($prov, $pdrb, $waktu){
        $adhb = PdrbProvTahun::find()->select('pdrb_b')->where(['id_prov' => $prov, 'id_pdrb' => $pdrb, 'timestamp' => $waktu])->one();
        return $adhb;
    }
    
    public function getAdhkByTime($prov, $pdrb, $waktu){
        $adhk = PdrbProvTahun::find()->select('pdrb_k')->where(['id_prov' => $prov, 'id_pdrb' => $pdrb, 'timestamp' => $waktu])->one();
        return $adhk;
    }
    
    public function getIndImpByTime($kabkot, $pdrb, $waktu){
        $indImp = PdrbProvTahun::find()->select('implisit')->where(['id_prov' => $prov, 'id_pdrb' => $pdrb, 'timestamp' => $waktu])->one();
        return  $indImp;     
    }
    
    public static function getReferensiDataTerbaru($id_prov, $tahun){
        if (PdrbProvTahun::find()->where(['id_prov' => $id_prov, 'tahun'=> $tahun])->exists()){
            $ref = PdrbProvTahun::find()->select('timestamp')->where(['id_prov' => $id_prov, 'tahun' => $tahun])->max('timestamp');
            return $ref;
        } else {
            return null;
        }    
    }
    
    public static function getReferensiTahunSebelum($id_prov, $tahun){
        $ref = PdrbProvTahun::getReferensiDataTerbaru($id_prov, $tahun-1);
        return $ref;
    }
    
    public static function getDistPerB($PDRB_b, $adhb){
        if($PDRB_b != 0){
            (float)$distPerB = (float)$adhb/(float)$PDRB_b *100;
            return $distPerB;
       }
        else {
           return null;
       }
    }
    
    public static function getDistPerK($PDRB_k, $adhk){
        if($PDRB_k != 0){
            (float)$distPerK = (float)$adhk/(float)$PDRB_k *100;
            return $distPerK;
        }
        else {
            return null;
        }
    }
    
    public static function getLajuP($id_prov, $id_pdrb, $ref_waktu, $pdrb_k){
        $pdrb_k_0 = PdrbProvTahun::getAdhkByTime($id_prov, $id_pdrb, $ref_waktu);
        if($pdrb_k_0->pdrb_k != 0){
            $lajuP = ($pdrb_k / $pdrb_k_0->pdrb_k *100) - 100;
            return $lajuP;
        }
        else {
            return null;
        }
    }
    
    //Untuk mendapat indeks implisit
    public static function getIndImp($adhb, $adhk){
        if($adhk != 0){
            (float)$indImp = (float)$adhb/(float)$adhk *100;
            return $indImp;
        } 
        else {
            return null;    
        }
    }
    
    public function getLajuImp($id_prov, $id_pdrb, $ref_waktu, $implisit){
        $implisit_0 = PdrbProvTahun::getIndImpByTime($id_prov, $id_pdrb, $ref_waktu);
        if ($implisit_0 == null){
            $implisit_0 = PdrbProvTahun::getIndImp(PdrbProvTahun::getAdhbByTime($id_prov, $id_pdrb, $ref_waktu), PdrbProvTahun::getAdhkByTime($kabkot, $id_pdrb, $ref_waktu));
        }
        if($implisit_0->implisit != 0){
            $lajuImp = ($implisit/$implisit_0->implisit *100) -100;
            return $lajuImp;
        }
        else {
            return null;
        }
    }
}
