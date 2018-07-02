<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PdrbProvTahun]].
 *
 * @see PdrbProvTahun
 */
class PdrbProvTahunQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PdrbProvTahun[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PdrbProvTahun|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
