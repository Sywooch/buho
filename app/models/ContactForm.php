<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_form".
 *
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property string $massage
 * @property integer $status
 * @property integer $creation_time
 * @property integer $update_time
 */
class ContactForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tel', 'email', 'massage', 'creation_time' ], 'required'],
            [['massage'], 'string'],
            [['status', 'creation_time', 'update_time'], 'integer'],
            [['name', 'tel', 'email'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Имя'),
            'tel' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'Email'),
            'massage' => Yii::t('app', 'Сообщение'),
            'status' => Yii::t('app', 'Прозвонен'),
            'creation_time' => Yii::t('app', 'Дата создания'),
            'update_time' => Yii::t('app', 'Дата обновления'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\ContactForm the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\ContactForm(get_called_class());
    }
}
