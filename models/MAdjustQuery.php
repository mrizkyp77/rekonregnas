<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MAdjustment]].
 *
 * @see MAdjustment
 */
class MAdjustQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MAdjustment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MAdjustment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
