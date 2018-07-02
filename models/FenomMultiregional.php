<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fenom_multiregional".
 *
 * @property string $id_fenom
 * @property string $id_prov
 * @property string $id_pdrb
 * @property int $tahun
 * @property int $triwulan
 * @property int $putaran
 * @property int $revisi
 * @property string $isi_fenom
 * @property string $isi_tipe
 * @property string $isi_sumber
 * @property string $isi_indikasi
 * @property string $timestamp
 */
class FenomMultiregional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fenom_multiregional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_fenom', 'id_prov', 'id_pdrb', 'tahun', 'triwulan', 'putaran', 'revisi', 'isi_fenom', 'timestamp'], 'required'],
            [['id_fenom'], 'number'],
            [['id_prov', 'id_pdrb'], 'string'],
            [['tahun', 'triwulan', 'putaran', 'revisi'], 'default', 'value' => null],
            [['tahun', 'triwulan', 'putaran', 'revisi'], 'integer'],
            [['timestamp'], 'safe'],
            [['isi_fenom', 'isi_sumber'], 'string', 'max' => 300],
            [['isi_tipe', 'isi_indikasi'], 'string', 'max' => 10],
            [['id_fenom'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_fenom' => Yii::t('app', 'Id Fenom'),
            'id_prov' => Yii::t('app', 'Id Prov'),
            'id_pdrb' => Yii::t('app', 'Id Pdrb'),
            'tahun' => Yii::t('app', 'Tahun'),
            'triwulan' => Yii::t('app', 'Triwulan'),
            'putaran' => Yii::t('app', 'Putaran'),
            'revisi' => Yii::t('app', 'Revisi'),
            'isi_fenom' => Yii::t('app', 'Isi Fenom'),
            'isi_tipe' => Yii::t('app', 'Isi Tipe'),
            'isi_sumber' => Yii::t('app', 'Isi Sumber'),
            'isi_indikasi' => Yii::t('app', 'Isi Indikasi'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FenomKabkotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FenomMultiregionalQuery(get_called_class());
    }
    
    public function beforeSave($insert)
    {
        if($this->find()->exists()){
            $this->id_fenom = $this->find()->max('id_fenom') + 1;
            return true;
        } 
        else {
            $this->id_fenom = 0;
            return true;
        }
    }
}
