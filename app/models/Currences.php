<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currences".
 *
 * @property integer $id
 * @property string $name
 * @property integer $base
 * @property double $coef
 */
class Currences extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currences';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'base', 'coef'], 'required'],
            [['base'], 'integer'],
            [['coef'], 'number'],
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
            'base' => Yii::t('app', 'Базовая'),
            'coef' => Yii::t('app', 'Курс относительно базовой'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\Currences the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\Currences(get_called_class());
    }
    public static function current()
    {
        return self::find()->andWhere(['base'=> 1 ])->one();
    }
}
