<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\CatalogCategoriesInfo]].
 *
 * @see \app\models\CatalogCategoriesInfo
 */
class CatalogCategoriesInfo extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\CatalogCategoriesInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\CatalogCategoriesInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
