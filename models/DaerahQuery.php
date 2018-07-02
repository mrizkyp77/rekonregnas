<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Daerah]].
 *
 * @see Daerah
 */
class DaerahQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Daerah[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Daerah|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
