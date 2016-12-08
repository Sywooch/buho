<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $title
 * @property string $description
 * @property string $text
 *
 * @property News $record
 */
class NewsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'title', 'description', 'text'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['description', 'text'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'title' => Yii::t('app', 'Заголовок'),
            'description' => Yii::t('app', 'Анонс'),
            'text' => Yii::t('app', 'Текст'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(News::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\NewsInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\NewsInfo(get_called_class());
    }
}
