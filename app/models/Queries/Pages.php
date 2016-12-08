<?php

namespace app\models\Queries;


/**
 * This is the ActiveQuery class for [[\frontend\models\Pages]].
 *
 * @see \frontend\models\Pages
 */
class Pages extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/
    
	public function firstLevel()
    {
        $this->andWhere('[[parent_id]]=-1');
        return $this;
    }
	
	public function parentLevel($parent_id = -1)
    {
        $this->andWhere("[[parent_id]]=$parent_id");
        return $this;
    }
	
	public function childPages($parent_id)
    {
        $this->andWhere("[[parent_id]]=$parent_id");
        return $this;
    }
    
    public function orWhereId($id)
    {
        $this->orWhere("[[id]]=$id");
        return $this;
    }
    
    public function byAlias($alias)
    {
        $this->andWhere("[[alias]]='$alias'");
        return $this;
    }
    
    public function byId($id)
    {
        $this->andWhere(["id" => $id]);
        return $this;
    }
    public function byParentId($id)
    {
        $this->andWhere(["parent_id" => $id]);
        return $this;
    }
    
    public function byRoute($route)
    {
        $this->andWhere("[[route]]='$route'");
        return $this;
    }    
    public function inMenu()
    {
        $this->andWhere("[[nomenu]]='0'");
        return $this;
    }    
    
    /**
     * @inheritdoc
     * @return \frontend\models\Pages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \frontend\models\Pages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
}
