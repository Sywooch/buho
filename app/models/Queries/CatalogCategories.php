<?php

namespace app\models\Queries;

/**
 * This is the ActiveQuery class for [[\app\models\CatalogCategories]].
 *
 * @see \app\models\CatalogCategories
 */
class CatalogCategories extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\models\CatalogCategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
	
    /**
     * @inheritdoc
     * @return \app\models\CatalogCategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function active() {
        return $this->andWhere(['hide' => 0]);
    }
    public function first_level()
    {
        return $this->active()->andWhere(['parent_id' => '-1']);
    }
    public function childs($id)
    {
        return $this->active()->andWhere(['parent_id' => $id]);
    }
    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }
    public function byBaseId($id)
    {
        return $this->andWhere(['base_id' => $id]);
    }
    public function byAlias($alias)
    {
        $this->andWhere("[[name_alt]]='$alias'");
        return $this;
    }
}
