<?php

namespace app\models;

use app\models\User\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class Orders extends ActiveRecord
{
	private static $params = NULL;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'orders';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$rules = [
			[['name', 'email', 'phone', 'delivery_id', 'pay_id'], 'required'],
			[['name'], 'string', 'min' => 2, 'max' => 100],
			[['comment'], 'string', 'min' => 0, 'max' => 1000],
			[['email'], 'email'],
			[['phone'], 'match', 'pattern' => '#^\+38 \(0\d\d\) \d\d\d-\d\d-\d\d$#', 'message' => Yii::t('app', 'Введите номер телефона в формате <nobr>+38 (0xx) xxx-xx-xx</nobr>')],
			//[['delivery_id', 'city_id', 'filial_id', 'pay_id', 'status_id'], 'number'],
			[['total'], 'double'],
		];

		$params = static::getOrdersParams();
		if (isset($_POST['delivery_id']) && isset($params['delivery'][$_POST['delivery_id']]))
		{
			switch ($params['delivery'][$_POST['delivery_id']]['system_key'])
			{
				case 'courier':
					$rules[] = [['address'], 'required'];
					break;
				case 'novaposhta':
					$rules[] = [['city_id'], 'required', 'message' => Yii::t('app', 'Введите название города для доставки')];
					$rules[] = [['filial_id'], 'required', 'message' => Yii::t('app', 'Выберите отделение Новой Почты из списка')];
					break;
			}
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('app', 'Имя'),
			'email' => 'E-mail',
			'phone' => 'Телефон',
			'comment' => Yii::t('app', 'Комментарий'),
			'address' => Yii::t('app', 'Адрес доставки'),
			'delivery_id' => Yii::t('app', 'Способ доставки'),
			'pay_id' => Yii::t('app', 'Способ оплаты'),
			'city_id' => Yii::t('app', 'Город доставки'),
			'filial_id' => Yii::t('app', 'Отделение Новой Почты'),
		];
	}

	public static function getCartInfo()
	{
		$cart = [
			'products' => [],
			'count' => 0,
			'cost' => 0,
			'url' => Url::toRoute('/user/cart')
		];
		if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']))
		{
			$cart['products'] = $_SESSION['cart'];
		}

		if (count($cart['products']))
		{
			$products = CatalogProducts::find()->byId(array_keys($cart['products']))->active()->all();
			foreach ($products as $product)
			{
				$cart['count'] += $cart['products'][$product->id];
				$cart['cost'] += $cart['products'][$product->id] * $product->userPrice;
				$cart['products'][$product->id] = [
					'id' => $product->id,
					'articul' => $product->articul,
					'name' => $product->info->name,
					'url' => $product->url,
					'price' => $product->userPrice,
                    'price_full' => $product->fullPrice,
					'count' => $cart['products'][$product->id],
                    'available' => $product->available,
					'cost' => $cart['products'][$product->id] * $product->userPrice,
					'installation' => $product->installation,
					'image' => $product->imgs[0]
				];
			}
		}

		return $cart;
	}

	public static function getOrdersParams()
	{
		if (self::$params === NULL)
		{
			self::$params = [];

			$conn = Yii::$app->getDb();
			$q = "SELECT `id`, `name`, `type`, `add_cost`, `system_key` "
				."FROM `orders_params` "
				."ORDER BY `sort` ASC";
			$res = $conn->createCommand($q)->queryAll();
			if ($res)
			{
				foreach ($res as $row)
				{
					self::$params[$row['type']][$row['id']] = $row;
				}
			}
		}

		return self::$params;
	}

	public function getCreated()
	{
		return date('H:i d.m.Y', strtotime($this->creation_time));
	}

	public function getUpdated()
	{
		return date('H:i d.m.Y', strtotime($this->update_time));
	}

	public function getStatus()
	{
		$params = self::getOrdersParams();
		if (isset($params['status'][$this->status_id]))
		{
			return $params['status'][$this->status_id];
		}
		return '';
	}

	public function getPayment()
	{
		$params = self::getOrdersParams();
		if (isset($params['payment'][$this->pay_id]))
		{
			return $params['payment'][$this->pay_id];
		}
		return '';
	}

	public function getDelivery()
	{
		$params = self::getOrdersParams();
		if (isset($params['delivery'][$this->delivery_id]))
		{
			return $params['delivery'][$this->delivery_id];
		}
		return '';
	}

	public function getProducts()
	{
		$products = [];
		$conn = Yii::$app->getDb();
		$q = "SELECT `product_id`, `count`, `price`, `installation`, `subtotal` "
			."FROM `orders_items` "
			."WHERE `order_id`=".(int)$this->id;
		$res = $conn->createCommand($q)->queryAll();
		if (is_array($res) && count($res))
		{
			foreach ($res as $row)
			{
				$products[$row['product_id']] = $row;
			}
		}

		$info = CatalogProducts::find()->base()->byId(array_keys($products))->all();
		if ($info)
		{
			foreach ($info as $one)
			{
				$products[$one->id]['name'] = $one->info->name;
				$products[$one->id]['image'] = $one->imgs[0];
				$products[$one->id]['articul'] = $one->articul;
				$products[$one->id]['url'] = $one->url;
			}
		}

		return $products;
	}
}
