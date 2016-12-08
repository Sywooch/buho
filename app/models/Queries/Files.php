<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\Files]].
 *
 * @see \app\models\Files
 */
class Files extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Files[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Files|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
