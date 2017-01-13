<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $status
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property BannersInfo[] $bannersInfos
 */
class Grids extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grids';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort', 'status', 'creation_time', 'update_time'], 'required'],
            [['sort', 'status', 'creation_time', 'update_time'], 'integer'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название'),
            'sort' => Yii::t('app', 'SORT'),
            'status' => Yii::t('app', 'Отображать'),
            'creation_time' => Yii::t('app', 'Date of creation'),
            'update_time' => Yii::t('app', 'Date of update'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGridsInfos()
    {
        return $this->hasMany(GridsInfo::className(), ['record_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\Banners the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\Grids(get_called_class());
    }
    //связь с многоязычной таблицей
    public function getInfo()
    {
        return $this->hasOne(GridsInfo::className(), ['record_id'=>'id'])->where([GridsInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    //большая картинка
    public function getBImg(){
		return 'http://'.$_SERVER['SERVER_NAME'] . '/images/'.$this->tableName().'/'.$this->id.".1.b.jpg";
		}
	//маленькая картинка
    public function getSImg(){
		return 'http://'.$_SERVER['SERVER_NAME'] . '/images/'.$this->tableName().'/'.$this->id.".1.s.jpg";
		}
}
