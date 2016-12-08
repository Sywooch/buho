<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $alias
 * @property string $pub_date
 * @property integer $status
 * @property integer $sort
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property NewsInfo[] $newsInfos
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'pub_date', 'status', 'sort', 'creation_time', 'update_time'], 'required'],
            [['pub_date'], 'safe'],
            [['status', 'sort', 'creation_time', 'update_time'], 'integer'],
            [['alias'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', 'Alias (геренерируеться, если не заполнен)'),
            'pub_date' => Yii::t('app', 'Дата публикации'),
            'status' => Yii::t('app', 'Опубликовать'),
            'sort' => Yii::t('app', 'SORT'),
            'creation_time' => Yii::t('app', 'Date of creation'),
            'update_time' => Yii::t('app', 'Date of update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsInfos()
    {
        return $this->hasMany(NewsInfo::className(), ['record_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\News the active query used by this AR class.
     */
    public static function find()
    {
        $n = new \app\models\Queries\News(get_called_class());
        return $n->joinWith('info');
    }
    
    public function getInfo()
    {
        return $this->hasOne(NewsInfo::className(), ['record_id'=>'id'])->where([NewsInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }

    public function getUrl()
    {
        return Lang::getCurrentUrl().($this->is_article ? 'article' : 'news')."/". $this->alias . ".html";
    }

    public function getImg()
    {
        if(is_file(__DIR__ . '/../../images/'.$this->tableName().'/'.$this->id.".1.b.jpg"))
        {
            $res = '/images/'.$this->tableName().'/'.$this->id.".1.b.jpg" ;
        }
        else
        {
            $res = '/img/no-img.png';
        }
       			
        return $res;
    }
}
