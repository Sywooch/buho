<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "text_blocks".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $parent_id
 * @property integer $creation_time
 * @property integer $update_time
 * @property integer $sort
 *
 * @property TextBlocksInfo[] $textBlocksInfos
 */
class TextBlocks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_blocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'parent_id', 'creation_time', 'update_time', 'sort'], 'required'],
            [['parent_id', 'creation_time', 'update_time', 'sort'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 250],
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
            'alias' => Yii::t('app', 'Алиас'),
            'parent_id' => Yii::t('app', 'В разделе'),
            'creation_time' => Yii::t('app', 'Date of creation'),
            'update_time' => Yii::t('app', 'Date of update'),
            'sort' => Yii::t('app', 'SORT'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTextBlocksInfos()
    {
        return $this->hasMany(TextBlocksInfo::className(), ['record_id' => 'id']);
    }
//связь с многоязычной таблицей
    public function getInfo()
    {
        return $this->hasOne(TextBlocksInfo::className(), ['record_id'=>'id'])->where([TextBlocksInfo::tableName().'.lang' => Lang::getCurrentId()]);
    }
    /**
     * @inheritdoc
     * @return \app\models\Queries\TextBlocks the active query used by this AR class.
     */
    public static function find()
    {
        $t = new \app\models\Queries\TextBlocks(get_called_class());
        return $t->joinWith('info');
    }
}
