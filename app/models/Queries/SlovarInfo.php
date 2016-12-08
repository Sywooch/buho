<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\SlovarInfo]].
 *
 * @see \app\models\SlovarInfo
 */
class SlovarInfo extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SlovarInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SlovarInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
