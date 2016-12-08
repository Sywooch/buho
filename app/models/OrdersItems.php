<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders_items".
 */
class OrdersItems extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'name', 'count', 'price', 'price_full', 'subtotal', 'url'], 'required'],
            [['order_id', 'product_id'], 'integer'],
            [['name'], 'string'],
            [['count', 'price', 'price_full', 'subtotal'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getO()
    {
        return $this->hasOne(Orders::className(), ['id' => 'o_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\OrdersItems the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\OrdersItems(get_called_class());
    }
}
