<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_daerah".
 *
 * @property string $kode_daerah
 * @property string $kode_provinsi
 * @property string $kode_kab
 * @property string $nama_provinsi
 * @property string $nama_kab
 * @property string $nama_provinsi_satu
 * @property string $pulau
 *
 * @property MAdmin[] $mAdmins
 * @property TEvaluasi[] $tEvaluasis
 * @property MReferensi[] $kodeReferensis
 * @property TFenomena[] $tFenomenas
 * @property TLampiran[] $tLampirans
 * @property TPdrb[] $tPdrbs
 * @property TPdrbX[] $tPdrbXes
 */
class Daerah extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_daerah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_daerah'], 'required'],
            [['kode_daerah'], 'string', 'max' => 4],
            [['kode_provinsi', 'kode_kab'], 'string', 'max' => 2],
            [['nama_provinsi', 'nama_kab'], 'string', 'max' => 255],
            [['nama_provinsi_satu'], 'string', 'max' => 30],
            [['pulau'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode_daerah' => Yii::t('app', 'Kode Daerah'),
            'kode_provinsi' => Yii::t('app', 'Kode Provinsi'),
            'kode_kab' => Yii::t('app', 'Kode Kab'),
            'nama_provinsi' => Yii::t('app', 'Nama Provinsi'),
            'nama_kab' => Yii::t('app', 'Nama Kab'),
            'nama_provinsi_satu' => Yii::t('app', 'Nama Provinsi Satu'),
            'pulau' => Yii::t('app', 'Pulau'),
        ];
    }

    /**
     * @inheritdoc
     * @return DaerahQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DaerahQuery(get_called_class());
    }
    
    public function getKodeProv($kode_daerah){
        $kode_prov = Daerah::find()->select('kode_provinsi')->where(['kode_daerah'=>$kode_daerah])->one();
        return $kode_prov->kode_provinsi;
    }
    
    public function getIdProvByKodeProv($kode_prov){
        $id_prov = Daerah::find()->select('kode_daerah')->where(['kode_provinsi'=>$kode_prov, 'kode_kab'=>'00'])->one();
        return $id_prov;
    }
}
