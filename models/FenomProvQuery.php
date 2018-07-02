<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[FenomProv]].
 *
 * @see FenomProv
 */
class FenomProvQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return FenomProv[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return FenomProv|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
