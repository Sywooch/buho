<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\TextBlocks]].
 *
 * @see \app\models\TextBlocks
 */
class TextBlocks extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\TextBlocks[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\TextBlocks|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    //поиск по алиасу
    public function byAlias($alias)
    {
        return $this->andWhere(['alias' =>$alias]);
    }
}
