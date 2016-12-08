<?php

namespace app\models\Queries;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\CatalogProducts]].
 *
 * @see \app\models\CatalogProducts
 */
class CatalogProducts extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\CatalogProducts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\CatalogProducts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function base()
    {
        return $this->andWhere(['base_id' => 0]);
    }
    public function active()
    {
        return $this->andWhere(['hide' => 0]);
    }
    //recomended products to all carts
    public function recom()
    {
        return $this->andWhere(['recom' => 1]);
    }
    
    public function byCategoryIds($ids)
    {
        if (!is_array($ids) && $ids > 0)
        {
            $ids = [(int)$ids];
        }
        if (is_array($ids) && count($ids))
        {
            return $this->andWhere('catalog_products.id IN (SELECT `product_id` FROM `catalog_products_categories_assoc` WHERE `cat_id` IN('.implode(', ', $ids).'))');
        }
        return $this;
    }
	public function byPrice($price_min, $price_max)
	{
		if ($price_min >= 0 && $price_max > $price_min)
		{
			return $this->andWhere(['between', 'price', $price_min, $price_max]);
		}
		return $this;
	}
    public function byAlias($alias)
    {
        return $this->andWhere(['alias' => $alias]);
    }
    public function byBaseId($id)
    {
        return $this->andWhere(['base_id' => $id]);
    }
    public function byId($id)
    {
        return $this->andWhere(['catalog_products.id' => $id]);
    }
    public function byFilter($selected)
    {
		$params = [];
		if (is_array($selected) && count($selected))
		{
			foreach ($selected as $value)
			{
				$params[$value['param_id']][$value['value_id']] = $value['value_id'];
			}
		}
		ksort($params);

		$regexp = [];
		foreach ($params as $param)
		{
			ksort($param);
			$regexp[] = ':[^:]*-('.implode('|', $param).')-[^:]*:';
		}
		$regexp = implode('.*', $regexp);

		return $this->andWhere(['REGEXP', 'params', $regexp]);
    }
	public function bySearch($st)
	{
		$lang  = \app\models\Lang::getCurrentId();
		return $this->andWhere(['or',['like', 'catalog_products_info.name',$st ],['like', 'catalog_products_info.txt',$st ]]);
	}

    
}
