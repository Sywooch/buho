<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $name
 * @property string $mname
 * @property string $title
 * @property string $description
 * @property string $h1
 * @property string $text
 *
 * @property Pages $record
 */
class PagesInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'name', 'mname', 'title', 'description', 'h1', 'text'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['text'], 'string'],
            [['name', 'mname', 'title', 'description', 'h1'], 'string', 'max' => 250],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pages::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'name' => Yii::t('app', 'Заголовок'),
            'mname' => Yii::t('app', 'Заголовок в меню'),
            'title' => Yii::t('app', 'title'),
            'description' => Yii::t('app', 'description'),
            'h1' => Yii::t('app', 'h1'),
            'text' => Yii::t('app', 'Текст'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(Pages::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\PagesInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\PagesInfo(get_called_class());
    }
}
