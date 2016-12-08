<?
namespace app\controllers;

use app\models\CatalogProducts;
use Yii;
use app\models\Orders;
use app\components\BaseController;

class CartController extends BaseController
{
	public function actionRequest()
	{
		$answer = [
			'result' => 'ok',
			'count' => 0,
			'cost' => 0,
			'products' => [],
			'man' => "$('.cart-count').html(answer.count);"
					."$('.cart-cost').html(answer.cost);"
					."if(answer.count>0)$('.cart-box').removeClass('empty');else $('.cart-box').addClass('empty');"
					."for(var p in answer.products){ $('.cart-cost-'+p).html(answer.products[p].cost); $('.cart-count-'+p).val(answer.products[p].count); }"
		];

		if (isset($_POST['method']))
		{
			switch ($_POST['method'])
			{
				case 'add':
					if (isset($_POST['product_id']))
					{
						if (!is_array($_POST['product_id']))
						{
							$_POST['product_id'] = [$_POST['product_id']];
						}
						$_POST['product_id'] = array_map('intval', $_POST['product_id']);

						if (!isset($_POST['count']) || $_POST['count'] < 1)
						{
							$_POST['count'] = 1;
						}

						foreach ($_POST['product_id'] as $id)
						{
                            //  Ищу товар по ID
                            $product = CatalogProducts::find()->byId($id)->one();
                            //  Если товар есть в наличии, выполняю добавление в корзину
                            if ($product->id == $id && $product->available > 0)
                            {
                                if ($id > 0 && (!isset($_POST['product_check']) || isset($_POST['product_check'][$id])))
                                {
                                    if (!isset($_SESSION['cart'][$id]))
                                    {
                                        $_SESSION['cart'][$id] = 0;
                                    }
                                    $_SESSION['cart'][$id] += (int)$_POST['count'];
                                    //  Если товара добавлено больше, чем есть в наличии, уменьшаю количество товара в корзине
                                    if ($_SESSION['cart'][$id] > $product->available)
                                    {
                                        $_SESSION['cart'][$id] = $product->available;
                                    }
                                }
                            }
						}

						$answer['popup'] = '@app/views/cart/added.twig';
					}
					break;
				case 'change':
					if (isset($_POST['product_id']) && $_POST['product_id'] > 0 && isset($_SESSION['cart'][$_POST['product_id']]) && isset($_POST['count']) && $_POST['count'] > 0)
					{
                        //  Ищу товар по ID
                        $product = CatalogProducts::find()->byId($_POST['product_id'])->one();
						if ($product->id > 0 && $product->available > 0)
                        {
                            $_SESSION['cart'][$product->id] = (int)$_POST['count'];
                            if ($_SESSION['cart'][$product->id] > $product->available)
                            {
                                $_SESSION['cart'][$product->id] = $product->available;
                            }
                        }
					}
					break;
				case 'remove':
					if (isset($_POST['product_id']) && $_POST['product_id'] > 0 && isset($_SESSION['cart'][$_POST['product_id']]))
					{
						unset($_SESSION['cart'][$_POST['product_id']]);
						$answer['location'] = 'reload';
					}
					break;
				case 'clear':
					$_SESSION['cart'] = [];
					$answer['location'] = 'reload';
					break;
			}
		}

		$answer = array_merge($answer, Orders::getCartInfo());

		if (isset($answer['popup']))
		{
			$answer['popup'] = $this->renderFile($answer['popup'], [
				'cart' => $answer
			]);
		}

		echo json_encode($answer, JSON_NUMERIC_CHECK);
	}
}
