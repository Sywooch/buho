<?
namespace app\controllers;

use app\models\NovaPoshta;
use app\models\OrdersItems;
use app\models\User\User;
use Yii;
use app\models\Orders;
use app\components\BaseController;
use yii\helpers\Url;

class OrderController extends BaseController
{
	public function actionRequest()
	{
		$answer = [
			'result' => 'ok',
		];

		if (isset($_POST['method']))
		{
			switch ($_POST['method'])
			{
				case 'get_filials':
					if (isset($_POST['city_name']) && strlen($_POST['city_name']) > 0)
					{
						$city = NovaPoshta::getCities($_POST['city_name']);
						if (is_array($city) && count($city) == 1)
						{
							$city_id = array_keys($city);
							$answer['city_id'] = $city_id[0];
							$answer['filials'] = NovaPoshta::getFilials($answer['city_id']);
						}
					}
					break;
				//	Сохранение заказа
				case 'save_order':
					$backet = Orders::getCartInfo();
					$params = Orders::getOrdersParams();
					if ($backet['count'] > 0)
					{
						$o = new Orders();
						$o->setAttributes($_POST);
						if ($o->validate())
						{
							$user = User::getCurrent();
							if ($user->id > 0)
							{
								$o->setAttribute('user_id', $user->id);
								//	Обновляю пустые поля пользователя
								$fields = ['city_id', 'filial_id', 'address'];
								foreach ($fields as $field)
								{
									if (isset($_POST[$field]) && $_POST[$field] && !$user->getAttribute($field))
									{
										$user->setAttribute($field, $_POST[$field]);
									}
								}
								$user->update(true, $fields);
							}
							$o->setAttribute('total', $backet['cost']);
							if (isset($params['status']))
							{
								foreach ($params['status'] as $param)
								{
									if ($param['system_key'] == 'new')
									{
										$o->setAttribute('status_id', $param['id']);
										break;
									}
								}
							}
							$o->setAttribute('update_time', date('Y-m-d H:i:s'));
							$o->setAttribute('creation_time', date('Y-m-d H:i:s'));

							if ($o->save())
							{
								$order_id = $o->getAttribute('id');
								foreach ($backet['products'] as $product)
								{
									$p = new OrdersItems();
									$p->setAttribute('order_id', $order_id);
									$p->setAttribute('product_id', $product['id']);
									$p->setAttribute('name', $product['name']);
									$p->setAttribute('count', $product['count']);
									$p->setAttribute('url', $product['url']);
									$p->setAttribute('price', $product['price']);
                                    $p->setAttribute('price_full', $product['price_full']);
									if (isset($_POST['installation'][$product['id']]) && $_POST['installation'][$product['id']])
									{
										$p->setAttribute('installation', $product['installation']);
									}
									$p->setAttribute('subtotal', $product['cost']);
									$p->save();

                                    //  Уменьшаю количество этого товара на складе
                                    $q = "UPDATE `catalog_products` SET "
                                        ."`avail_text`=IF(`available`<=".(int)$product['count'].", 'Нет в наличии', `avail_text`), "
                                        ."`available`=`available`-".(int)$product['count']." "
                                        ."WHERE `id`=".(int)$product['id'];
                                    Yii::$app->db->createCommand($q)->execute();
								}
								$answer['message'] = Yii::t('app', 'Ваш заказ успешно сохранён');
								$answer['location'] = Url::toRoute('/user/orderdone');
								$_SESSION['order_id'] = $order_id;
								$_SESSION['cart'] = [];

								$this->sendOrderEmail($o, $backet['products']);
							}
						}
						else
						{
							$answer['message'] = $o->getFirstErrors();
							if (is_array($answer['message']))
							{
								$answer['error_field'] = array_keys($answer['message']);
								$answer['message'] = implode('<br>', $answer['message']);
							}
						}
					}
					else
					{
						$answer['message'] = Yii::t('app', 'Ваша корзина пуста');
					}
					break;
			}
		}

		echo json_encode($answer, JSON_NUMERIC_CHECK);
	}

	private function sendOrderEmail($order, $products)
	{
		$letter = $this->renderPartial('orderLetter.twig', [
			'order' => $order,
			'products' => $products,
			'root' => Url::home(true)
		]);

		if (strlen($order->email))
		{
			Yii::$app->mailer
				->compose()
				->setFrom('admin@enerline.ua')
				->setTo($order->email)
				->setSubject(Yii::t('app', 'Ваш заказ оформлен'))
				->setHtmlBody($letter)
				->send();
		}
	}
}
