<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catalog_categories_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $name
 * @property string $name_one
 * @property string $txt
 *
 * @property CatalogCategories $record
 */
class CatalogCategoriesInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_categories_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'name', 'name_one', 'txt'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['txt'], 'string'],
            [['name', 'name_one'], 'string', 'max' => 250],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategories::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'name' => Yii::t('app', 'Название'),
            'name_one' => Yii::t('app', 'Название ед.ч.'),
            'txt' => Yii::t('app', 'Текст описания'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(CatalogCategories::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\CatalogCategoriesInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\CatalogCategoriesInfo(get_called_class());
    }
}
