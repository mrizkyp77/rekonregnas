<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_waktu".
 *
 * @property integer $id_waktu
 * @property integer $tahun
 * @property integer $triwulan
 * @property integer $putaran
 * @property string $status
 */
class Waktu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_waktu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun', 'triwulan', 'putaran'], 'required'],
            [['tahun', 'triwulan', 'putaran'], 'integer'],
            [['id_waktu', 'status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_waktu' => Yii::t('app', 'Id Waktu'),
            'tahun' => Yii::t('app', 'Tahun'),
            'triwulan' => Yii::t('app', 'Triwulan'),
            'putaran' => Yii::t('app', 'Putaran'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return MWaktuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WaktuQuery(get_called_class());
    }
    
    public function getWaktuBerjalan(){
        $berjalan = Waktu::find()->select('id_waktu')->where(['status' => 'aktif'])->one();
        return $berjalan->id_waktu ;
    }
    
    public function getPutaranMaks($tahun, $triwulan){
        $putaran = Waktu::find()->select('putaran')->where(['tahun'=>$tahun, 'triwulan'=>$triwulan])->max('putaran') ;
        return $putaran;
    }
    
    public function cekStatusWaktu($model){
        return (Waktu::find()->where(['tahun' => $model->tahun, 'triwulan' => $model->triwulan, 'putaran' => $model->putaran, 'status' => 'aktif'])->one());
    }
    
    public function gantiStatus(){
        Yii::$app->db->createCommand()
             ->update('m_waktu', ['status' => 'tidak aktif'], ['status' => 'aktif'])
             ->execute();
    }
    
    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if (!Waktu::find()->where(['id_waktu' => (string)$this->tahun."/".(string)$this->triwulan."/".(string)$this->putaran, 'status' => 'aktif'])->one()){
                $this->gantiStatus();
                $this->status = 'aktif';
                $this->id_waktu = (string)$this->tahun."/".(string)$this->triwulan."/".(string)$this->putaran ;
                return true;
            } else {
                return false;
            }
        }
    }
}
