<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
//use frontend\components\cHtml;
use yii\helpers\Url;
/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $text
 * @property integer $parent_id
 * @property integer $sort
 * @property string $creation_time
 * @property string $update_time
 *
 * @property PagesInfo[] $pagesInfos
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'text', 'parent_id', 'sort', 'creation_time', 'update_time'], 'required'],
            [['text'], 'string'],
            [['parent_id', 'sort'], 'integer'],
            [['alias', 'creation_time', 'update_time'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', 'Alias'),
            'name' => Yii::t('app', 'Name'),
            'text' => Yii::t('app', 'Text'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'sort' => Yii::t('app', 'Sort'),
            'creation_time' => Yii::t('app', 'Creation Time'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagesInfos()
    {
        return $this->hasMany(PagesInfo::className(), ['id' => 'id']);
    }
    

    /**
     * @inheritdoc
     * @return \frontend\models\Queries\Pages the active query used by this AR class.
     */
    public static function find()
    {
        $p = new \app\models\Queries\Pages(get_called_class());
        return $p->joinWith('info');
    }
    
    public function getInfo()
    {
        return $this->hasOne(PagesInfo::className(), ['record_id'=>'id'])->where([PagesInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    public function getCertificates()
    {
        return $this->hasMany(Certificates::className(), ['page_id'=>'id']);
    }
    public function getParent(){
		return $this::find()->byId($this->parent_id)->one();
		}
    public function getUrl()
    {
		//return Url::to(['/content/'.$this->alias.'.html']);
		
		if($this->route!=null)	{
			if($this->route == 'content/index'){
				return Lang::getCurrent() == Lang::getDefaultLang() ? '/' : "/". Lang::getCurrent()->url."/";
				}
			return Url::to(['/'.reset(explode('/',$this->route)).'/'.$this->alias.'.html']);
			}
		elseif($this->alias!='')
			return Url::to(['/'.$this->alias]);
		else
			return '#';
	}

     public function hasChildren()
		{
		return $this::find()->select('id')->childPages($this->id)->count();
		}
     public function getChilds()
		{
		return $this->andWhere([]);
		}
     public function getChildrens_columns()
		{
		$column = [];
		$childs = $this::find()->childPages($this->id)->andWhere(['<>', 'nomenu', 1])->orderBy("sort")->all();
		$i = 0;
		foreach($childs as $ch){
			$column[$i][] = $ch;
			$i++;
			//var_dump($ch->id);
			if($i == 4) $i = 0 ; 
			}
		
		return $column;
		}
		 
	public function current($curl , $murl) // current , item
		{
			$a_curl = explode('/', $curl);
			$a_murl = explode('/', $murl);
			//var_dump($a_curl);
			//var_dump($a_murl);
				if(($a_curl[2] == 'product' || $a_curl[1] == 'product') && $a_murl[0] == '#' ){
					return true;
					}
				if((count($a_curl)== 4 || count($a_curl)== 2)&& $a_murl[2] == $a_curl[2] && $a_curl[3] == $a_murl[3] && $murl!='#'){
					return true;
					}
				if(count($a_curl) == 3 && $a_murl[2] == $a_curl[1] && $a_curl[2] == $a_murl[3] && $murl!='#'){
					return true;
					}
				if(($curl == '/ua' || $curl == '/ua/') && ($murl == '/ua' || $murl == '/ua/')){
					return true;
					}
				$product_urls = ['/ru/content/documents.html' ,
				'/ua/content/documents.html' , 
				];
				if( $a_murl[0] == '#' && in_array($curl, $product_urls) ){
					return true;
					}
			return false;
		 }

}
