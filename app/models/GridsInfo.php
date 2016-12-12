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
            [['record_id', 'lang', 'title_top', 'title_mid', 'title_bot', 'href_url'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['title_top', 'title_mid', 'title_bot', 'href_url'], 'string', 'max' => 250],
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
            'lang' => Yii::t('app', 'Lang'),
            'title_top' => Yii::t('app', 'Заголовок (верx)'),
            'title_mid' => Yii::t('app', 'Заголовок (середина)'),
            'title_bot' => Yii::t('app', 'Заголовок (низ)'),
            'href_url' => Yii::t('app', 'Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(Banners::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\BannersInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\BannersInfo(get_called_class());
    }
}
