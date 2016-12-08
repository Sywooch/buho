<?php

namespace app\models\User;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
	private static $current = NULL;
	private static $ip_city = NULL;
	private $wishlist = FALSE;
	private $discount = FALSE;

	public function init()
	{
		parent::init();
		$this->on(self::EVENT_BEFORE_INSERT, function(){
			$this->password = sha1($this->password);
			return TRUE;
		});
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'users';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$rules = [
			[['email', 'name', 'phone', 'created'], 'required'],
			[['city_id', 'filial_id'], 'integer'],
			[['name'], 'string', 'min' => 2, 'max' => 100],
			[['password'], 'string', 'min' => 6, 'max' => 20],
			[['address'], 'string', 'min' => 10],
			[['email'], 'email'],
			[['email'], 'unique'],
		];

		if($this->isNewRecord)
		{
			$rules[] = [['password'], 'required'];
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
			'name' => Yii::t('app', 'Ваше Имя'),
			'email' => 'E-mail',
			'phone' => 'Телефон',
			'password' => 'Пароль',
			'address' => Yii::t('app', 'Адрес доставки'),
			'created' => Yii::t('app', 'Дата регистрации'),
		];
	}

	public static function getCurrent()
	{
		if (self::$current === NULL)
		{
			self::$current = FALSE;
			if (Yii::$app->user->id > 0)
			{
				self::$current = self::findIdentity(Yii::$app->user->id);
			}
			else
			{
				self::$current = new self();
			}
		}

		return self::$current;
	}

	public static function findByEmail($email)
	{
		return static::findOne(['email' => $email]);
	}

	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	public static function findIdentityByAccessToken($token, $type = NULL)
	{
		return static::findOne(['access_token' => $token]);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getAuthKey()
	{
		return $this->authKey;
	}

	public function validateAuthKey($authKey)
	{
		return $this->authKey === $authKey;
	}

	//	Метод возвращает список пунктов меню личного кабинета
	public function getMenu()
	{
		$menu = [
			[
				'name' => Yii::t('app', 'Персональные данные'),
				'url' => Url::toRoute(['/user/profile'])
			],
			[
				'name' => Yii::t('app', 'История заказов'),
				'url' => Url::toRoute(['/user/orders'])
			],
			[
				'name' => Yii::t('app', 'Список желаний'),
				'url' => Url::toRoute(['/user/wishlist'])
			],
			[
				'name' => Yii::t('app', 'Корзина'),
				'url' => Url::toRoute(['/user/cart'])
			],
		];

		if (!$this->id)
		{
			array_unshift($menu, [
				'name' => Yii::t('app', 'Авторизация'),
				'url' => Url::toRoute(['/user/auth'])
			]);
		}

		return $menu;
	}

	//	Метод возвращает название города по ID города у текущего пользователя
	public function getCityName()
	{
		if ($this->city_id > 0)
		{
			$conn = Yii::$app->getDb();
			$q = "SELECT `name_ru` AS `name` "
				."FROM `np_cities` "
				."WHERE `id`=".(int)$this->city_id;
			$res = $conn->createCommand($q)->queryOne();
			if (isset($res['name']))
			{
				return $res['name'];
			}
		}

		return '';
	}

	//	Метод возвращает массив ID товаров в списке желаний текущего пользователя
	public function getWishlist()
	{
		if ($this->id > 0)
		{
			if ($this->wishlist === FALSE)
			{
				$this->wishlist = [];
				$conn = Yii::$app->getDb();
				$q = "SELECT `product_id` "
					."FROM `users_wishlist` "
					."WHERE `user_id`=".(int)$this->id." "
					."ORDER BY `added` DESC";
				$res = $conn->createCommand($q)->queryAll();
				if ($res)
				{
					foreach ($res as $row)
					{
						$this->wishlist[] = $row['product_id'];
					}
				}
			}
		}

		return $this->wishlist;
	}

	public function getOrdersSumm()
	{
		$summ = 0;

		if ($this->id > 0)
		{
			$conn = Yii::$app->getDb();
			$q = "SELECT SUM(o.`total`) AS `total` "
				."FROM `orders` o LEFT JOIN `orders_params` op ON op.`id`=o.`status_id` "
				."WHERE o.`user_id`=".(int)$this->id." AND op.`system_key`='success'";
			$res = $conn->createCommand($q)->queryAll();
			if ($res)
			{
				$summ = $res[0]['total'];
			}
		}

		return $summ;
	}

	public function getDiscount()
	{
		if ($this->id > 0 && !$this->discount)
		{
			$summ = $this->getOrdersSumm();
			if ($summ > 0)
			{
				$this->discount = sqrt($summ) / 100;
				if ($this->discount > 1)
				{
					$this->discount = 1;
				}
			}
		}

		return $this->discount;
	}

	public static function getIPCity()
	{
		if (self::$ip_city === NULL)
		{
			$ip = ip2long($_SERVER['REMOTE_ADDR']);
			$conn = Yii::$app->getDb();
			$q = "SELECT `city` "
				."FROM `geo_ip` "
				."WHERE ".(int)$ip." BETWEEN `ip_from` AND `ip_to`";
			$res = $conn->createCommand($q)->queryAll();
			if (isset($res[0]['city']))
			{
				self::$ip_city = $res[0]['city'];
			}
            else
            {
                self::$ip_city = FALSE;
            }
		}

		return self::$ip_city;
	}

    /*
     *	Генератор случайного пароля
     */
    public static function getRandomPassword($length = 8)
    {
        //	Формирую массив допустимых для пароля символов
        $chars = array();
        for ($i = 0; $i <= 9; $i++)
        {
            $chars[] = $i;
        }
        for ($i = 65; $i <= 90; $i++)
        {
            $chars[] = chr($i);
        }
        for ($i = 0; $i <= 9; $i++)
        {
            $chars[] = $i;
        }
        for ($i = 97; $i <= 122; $i++)
        {
            $chars[] = chr($i);
        }
        for ($i = 0; $i <= 9; $i++)
        {
            $chars[] = $i;
        }

        //	Формирую пароль
        $pass = '';
        $char_count = count($chars);
        for ($i = 0; $i < $length; $i++)
        {
            $pass .= $chars[rand(0, $char_count - 1)];
        }
        return $pass;
    }
}