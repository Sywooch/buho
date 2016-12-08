<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\ExtraParamsCache]].
 *
 * @see \app\models\ExtraParamsCache
 */
class ExtraParamsCache extends \yii\db\ActiveQuery
{
	/**
	 * @return $this
	 */
    public function inFilter()
    {
        return $this->andWhere(['in_filter' => 1]);
    }

	public function byCategoryId($id)
	{
		if (is_array($id) && count($id))
		{
			return $this->leftJoin('catalog_products p', "p.id=product_id")->andWhere(['p.cat_id' => $id])->groupBy('value_id');
		}

		return $this;
	}

    /**
     * @inheritdoc
     * @return \app\models\ExtraParamsCache[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ExtraParamsCache|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
