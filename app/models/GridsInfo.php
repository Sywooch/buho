<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $title_top
 * @property string $title_mid
 * @property string $title_bot
 * @property string $href_url
 *
 * @property Banners $record
 */
class GridsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grids_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'href', 'title', 'photo', 'lang'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['href', 'title', 'photo'], 'string', 'max' => 250],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grids::className(), 'targetAttribute' => ['record_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'record_id' => Yii::t('app', 'Record ID'),    
            'href_url' => Yii::t('app', 'Url'),
            'title' => Yii::t('app', 'Заголовок'),
            'photo' => Yii::t('app', 'photo'),
            'lang' => Yii::t('app', 'Lang'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(Grids::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\BannersInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\GridsInfo(get_called_class());
    }
}
