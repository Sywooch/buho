<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "text_categories".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $creation_time
 * @property integer $update_time
 * @property integer $sort
 */
class TextCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'creation_time', 'update_time'], 'required'],
            [['parent_id', 'creation_time', 'update_time', 'sort'], 'integer'],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'creation_time' => Yii::t('app', 'Date of creation'),
            'update_time' => Yii::t('app', 'Date of update'),
            'sort' => Yii::t('app', 'Sort'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\TextCategories the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\TextCategories(get_called_class());
    }
}
