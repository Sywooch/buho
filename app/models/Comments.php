<?php

namespace app\models;

use Yii;

class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'name', 'email', 'text'], 'required'],
            [['email'], 'email'],
            [['product_id'], 'integer'],
            [['name'], 'string', 'max' => 120],
            [['text'], 'string', 'min' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'ID товара'),
            'name' => Yii::t('app', 'Ваше имя'),
            'email' => Yii::t('app', 'Ваш email'),
            'text' => Yii::t('app', 'Текст отзыва'),
        ];
    }
}
