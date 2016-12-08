<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $status
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property BannersInfo[] $bannersInfos
 */
class Feedbacks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedbacks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'company'], 'string', 'max' => 250],
			[['text'], 'string', 'min' => 10],
			[['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Ваше имя'),
            'email' => Yii::t('app', 'Ваш email'),
            'company' => Yii::t('app', 'Ваша компания'),
            'text' => Yii::t('app', 'Комментарий'),
        ];
    }
}
