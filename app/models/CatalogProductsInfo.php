<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catalog_products_info".
 *
 * @property integer $record_id
 * @property integer $lang
 * @property string $name
 * @property string $txt
 *
 * @property CatalogProducts $record
 */
class CatalogProductsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_products_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_id', 'lang', 'name', 'txt'], 'required'],
            [['record_id', 'lang'], 'integer'],
            [['txt'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProducts::className(), 'targetAttribute' => ['record_id' => 'id']],
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
            'name' => Yii::t('app', 'Наименование'),
            'txt' => Yii::t('app', 'Описание'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(CatalogProducts::className(), ['id' => 'record_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\CatalogProductsInfo the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\CatalogProductsInfo(get_called_class());
    }
}
