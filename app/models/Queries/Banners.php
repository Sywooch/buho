<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\Banners]].
 *
 * @see \app\models\Banners
 */
class Banners extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Banners[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Banners|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    // с галочкой показать
    public function active(){
		return $this->andWhere(['status' => 1]);
		}
}
