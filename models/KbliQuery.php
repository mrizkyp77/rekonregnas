<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Kbli]].
 *
 * @see Kbli
 */
class KbliQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Kbli[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Kbli|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
