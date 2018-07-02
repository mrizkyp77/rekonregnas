<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PdrbMultiregional]].
 *
 * @see PdrbMultiregional
 */
class PdrbMultiregionalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PdrbMultiregional[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PdrbMultiregional|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
