<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_kbli".
 *
 * @property string $kode_pdrb
 * @property string $kode_fenom
 * @property string $ket_pdrb
 * @property string $leng_pdrb
 * @property string $no_pdrb
 */
class Kbli extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_kbli';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_pdrb'], 'required'],
            [['kode_pdrb'], 'string', 'max' => 7],
            [['kode_fenom'], 'string', 'max' => 4],
            [['ket_pdrb'], 'string', 'max' => 60],
            [['leng_pdrb'], 'string', 'max' => 150],
            [['no_pdrb'], 'string', 'max' => 2],
            [['kode_pdrb'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode_pdrb' => Yii::t('app', 'Kode Pdrb'),
            'kode_fenom' => Yii::t('app', 'Kode Fenom'),
            'ket_pdrb' => Yii::t('app', 'Ket Pdrb'),
            'leng_pdrb' => Yii::t('app', 'Leng Pdrb'),
            'no_pdrb' => Yii::t('app', 'No Pdrb'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return KbliQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KbliQuery(get_called_class());
    }
}
