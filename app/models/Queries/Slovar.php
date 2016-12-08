<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\Slovar]].
 *
 * @see \app\models\Slovar
 */
class Slovar extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Slovar[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
    public function byAlias($alias)
    {
        return $this->andWhere(['alias' => $alias]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Slovar|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
