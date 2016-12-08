<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "catalog_categories".
 *
 * @property integer $id
 * @property integer $hide
 * @property string $name_alt
 * @property integer $sort
 * @property integer $no_del
 * @property integer $parent_id
 * @property integer $creation_time
 * @property integer $update_time
 * @property string $also_ids
 *
 * @property CatalogCategoriesInfo[] $catalogCategoriesInfos
 */
class CatalogCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hide', 'name_alt', 'sort', 'no_del', 'parent_id', 'creation_time', 'update_time', 'also_ids'], 'required'],
            [['hide', 'sort', 'no_del', 'parent_id', 'creation_time', 'update_time'], 'integer'],
            [['name_alt', 'also_ids'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hide' => Yii::t('app', 'Скрыть'),
            'name_alt' => Yii::t('app', 'Название для УРЛ латиницей (сгенерируется автоматом)'),
            'sort' => Yii::t('app', 'Сортировка'),
            'no_del' => Yii::t('app', 'Не удалять'),
            'parent_id' => Yii::t('app', 'Находится в разделе'),
            'creation_time' => Yii::t('app', 'Date of creation'),
            'update_time' => Yii::t('app', 'Date of update'),
            'also_ids' => Yii::t('app', 'Товары, которые рекомендуются покупать вместе (ID товаров через запятую, пример: 545,567)'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogCategoriesInfos()
    {
        return $this->hasMany(CatalogCategoriesInfo::className(), ['record_id' => 'id']);
    }
    public function getSelfAndChildsIds()
    {
        $res = [];
        $res[] = $this->id;
        $childs = $this->childs;
        foreach ($childs as $ch) {
            $res[] = $ch->id;            
        }
        return $res;
    }
    public function getSelfChildsGrandchildsIds()
    {
        $res = [];
        $res[] = $this->id;
        $childs = $this->childs;
        foreach ($childs as $ch) {
            $res[] = $ch->id;
            $gchilds = $ch->childs;
            foreach ($gchilds as $gch) {
                $res[] = $gch->id;
            }
        }
        return $res;
    }
    public function getProductsCount() {
        return CatalogProducts::find()->select('id')->active()->base()->byCategoryIds($this->selfAndChildsIds)->count();
    }
    public function getIdsForCatalog() {
        if($this->parent_id == -1){
            $ids = $this->selfChildsGrandchildsIds;
        }
        else{
            $ids = $this->selfAndChildsIds;
        }
        
        return $ids;
    }
	public function getInfo()
    {
        return $this->hasOne(CatalogCategoriesInfo::className(), ['record_id'=>'id'])->onCondition([CatalogCategoriesInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    /*
	public function getChilds()
    {
        return $this::find()->childs($this->id)->orderBy("sort DESC")->all();
    }
    */
    public function getChilds()
    {
        return $this->hasMany($this::className(), ['parent_id' => 'id'])->orderBy("sort DESC");
    }
    //files
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['record_id' => 'id'])->where([Files::tableName() . '.table_name' => $this::tableName()]);
    }
	public function getUrl()
    {
		return Url::toRoute(['catalog/index', 'alias' => $this->name_alt, 'page' => 1]);
        //return Lang::getCurrentUrl()."catalog/". $this->name_alt . ".html";
    }
    /*
    public function getParent_item()
    {
        return $this::find()->byId($this->parent_id)->one();
    }
    */

    public function getParent()
    {
        return $this::find()->byId($this->parent_id)->one();
        //return $this->hasOne($this::className(), ['id' => 'parent_id'])->andWhere('id>0');
    }

    public function getBreadcrumbsItems($last_has_url = false)
    {
		$result = [];
		$result[] = ['label' => '<span itemprop="title">'. $this->info->name . '</span>' ,
					'url' => $last_has_url ?  $this->url : NULL ];
		$parent = $this->parent;
		while(!empty($parent))
        {
			array_unshift($result, ['label' => '<span itemprop="title">'. $parent->info->name . '</span>', 'url' => $parent->url]);
			$parent = $parent->parent;
        }
			
		return $result;
    }
    
    public function getBreadcrumbs()
    {
		
        return $this->breadcrumbsItems;
    }
    public function getBreadcrumbsForProduct()
    {
		
        return $this->getBreadcrumbsItems(true);
    }
    /**
     * @inheritdoc
     * @return \app\models\Queries\CatalogCategories the active query used by this AR class.
     */
    public static function find()
    {
        $c =  new \app\models\Queries\CatalogCategories(get_called_class());
        return $c->joinWith('info')->with(['childs', 'childs.info']);
    }
}
