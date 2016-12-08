<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\News]].
 *
 * @see \app\models\News
 */
class News extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\News[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\News|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function active() {
        return $this->andWhere(['status' => 1]);
    }
    public function byAlias($alias) {
        return $this->andWhere(['alias' => $alias]);
    }
}
