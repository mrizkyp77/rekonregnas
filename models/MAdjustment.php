<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_adjust".
 *
 * @property string $id_adjust
 */
class MAdjustment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_adjust';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_adjust'], 'required'],
            [['id_adjust'], 'string', 'max' => 16],
            [['id_adjust'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_adjust' => Yii::t('app', 'Id Adjust'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return MAdjustQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MAdjustQuery(get_called_class());
    }
}
