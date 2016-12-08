<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_feedback".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $referer
 * @property string $message
 * @property integer $done
 * @property string $created
 * @property string $updated
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$rules = [
			[['type', 'name', 'created'], 'required'],
			[['done'], 'integer'],
			[['name'], 'string', 'min' => 2, 'max' => 100],
			[['message'], 'string', 'min' => 10],
			[['phone'], 'validatePhone'],
			[['email'], 'email'],
			[['referer'], 'string', 'max' => 256],
		];

		if($this->type == 'callback')
		{
			$rules[] = ['phone', 'required'];
		}
		else
		{
			$rules[] = [['email', 'message'], 'required'];
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'type' => Yii::t('app', 'Тип запроса'),
			'name' => Yii::t('app', 'Имя'),
			'email' => 'E-mail',
            'phone' => 'Телефон',
			'referer' => Yii::t('app', 'Страница, с которой пришёл запрос'),
			'message' => Yii::t('app', 'Сообщение'),
			'done' => Yii::t('app', 'Заявка обработана'),
			'created' => 'Дата создания',
			'updated' => 'Дата изменения',
        ];
    }

	public function validatePhone()
	{
		if (strlen($this->phone))
		{
			$this->phone = preg_replace('#\D+#', '', $this->phone);
			if (strlen($this->phone) == 12)
			{
				$this->phone = preg_replace('#^(\d{2})(\d{3})(\d{3})(\d{2})(\d{2})$#', '+$1 ($2) $3-$4-$5', $this->phone);
			}
			else
			{
				$this->addError('phone', Yii::t('app', 'Некорректный номер телефона'));
			}
		}
	}
}
