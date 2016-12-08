<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "text_blocks_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $title
 * @property string $text
 *
 * @property TextBlocks $record
 */
class TextBlocksInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_blocks_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'title', 'text'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['title', 'text'], 'string'],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => TextBlocks::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'text' => Yii::t('app', 'Текст'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(TextBlocks::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\TextBlocksInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\TextBlocksInfo(get_called_class());
    }
}
