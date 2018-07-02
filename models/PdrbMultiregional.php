<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pdrb_multiregional".
 *
 * @property string $id_prov
 * @property string $id_pdrb
 * @property int $tahun_dasar
 * @property int $tahun
 * @property int $triwulan
 * @property int $putaran
 * @property int $revisi
 * @property double $pdrb_b
 * @property double $pdrb_k
 * @property string $timestamp
 */
class PdrbMultiregional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pdrb_multiregional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_prov', 'id_pdrb', 'tahun_dasar', 'tahun', 'triwulan', 'putaran', 'revisi', 'timestamp'], 'required'],
            [['tahun_dasar', 'tahun', 'triwulan', 'putaran', 'revisi'], 'default', 'value' => null],
            [['tahun_dasar', 'tahun', 'triwulan', 'putaran', 'revisi'], 'integer'],
            [['pdrb_b', 'pdrb_k'], 'number'],
            [['timestamp'], 'safe'],
            [['id_prov'], 'string', 'max' => 4],
            [['id_pdrb'], 'string', 'max' => 7],
            [['id_prov', 'id_pdrb', 'tahun_dasar', 'tahun', 'triwulan', 'putaran', 'revisi'], 'unique', 'targetAttribute' => ['id_prov', 'id_pdrb', 'tahun_dasar', 'tahun', 'triwulan', 'putaran', 'revisi']],
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
            'tahun_dasar' => Yii::t('app', 'Tahun Dasar'),
            'tahun' => Yii::t('app', 'Tahun'),
            'triwulan' => Yii::t('app', 'Triwulan'),
            'putaran' => Yii::t('app', 'Putaran'),
            'revisi' => Yii::t('app', 'Revisi'),
            'pdrb_b' => Yii::t('app', 'Pdrb B'),
            'pdrb_k' => Yii::t('app', 'Pdrb K'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PdrbMultiregionalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PdrbMultiregionalQuery(get_called_class());
    }
}
