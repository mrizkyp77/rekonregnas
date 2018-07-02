<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PdrbProv]].
 *
 * @see PdrbProv
 */
class PdrbProvQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PdrbProv[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PdrbProv|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
