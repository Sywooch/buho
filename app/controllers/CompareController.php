<?
namespace app\controllers;

use app\models\CatalogProducts;
use Yii;
use app\components\BaseController;
use yii\helpers\Url;

class CompareController extends BaseController
{
	public function actionIndex()
	{
		$data = [];
		if (isset($_SESSION['compare']) && is_array($_SESSION['compare']) && count($_SESSION['compare']))
		{
			$_SESSION['compare'] = array_map('intval', $_SESSION['compare']);
			$data['products'] = CatalogProducts::find()->byId($_SESSION['compare'])->all();
			$params = CatalogProducts::findParams($data['products']);
			if ($params['params'])
			{
				//	Формирую список из всех параметров, которые есть в выбранных товарах
				$all_params = [];
				foreach ($params['params'] as $product_id => $product_params)
				{
					foreach ($product_params as $param)
					{
						//	Название параметра
						$all_params[$param['param_id']][0] = $param['param_name'];
						//	Значение параметра для конкретного товара
						$all_params[$param['param_id']][$product_id] = $param['value_name'];
					}
				}

				//	Формирую построчный список параметров
				foreach ($all_params as $param_id => $values)
				{
					//	Название параметра
					$data['params'][$param_id]['name'] = $values[0];
					//	Класс параметра. same - значит все значения одинаковы у всех товаров
					$data['params'][$param_id]['class'] = 'same';
					$prev_value = FALSE;
					foreach ($data['products'] as $product)
					{
						//	Если у товара нет значения такого параметра, добавляю прочерк
						if (!isset($values[$product->id]))
						{
							$values[$product->id] = '-';
						}
						//	Добавляю значение в строку
						$data['params'][$param_id]['values'][] = $values[$product->id];
						//	Проверяю значения на идентичность. Если хотя бы одно значение отличается, убираю класс same
						if (!$prev_value)
						{
							$prev_value = $values[$product->id];
						}
						elseif ($prev_value != $values[$product->id])
						{
							$data['params'][$param_id]['class'] = '';
						}
					}
				}
			}
		}

		return $this->render('index.twig', $data);
	}

	public function actionRequest()
	{
		$answer = [
			'result' => 'ok',
			'count' => 0,
			'url' => Url::to('/compare/'),
			'man' => "$('.compare-box .compare-cont').html(answer.count);"
					."if(answer.count>0)$('.header .compare-box').removeClass('empty');else $('.header .compare-box').addClass('empty');"
		];

		if (!isset($_SESSION['compare']) || !is_array($_SESSION['compare']))
		{
			$_SESSION['compare'] = [];
		}

		if (isset($_POST['method']))
		{
			switch ($_POST['method'])
			{
				case 'add':
					if (isset($_POST['product_id']) && $_POST['product_id'] > 0)
					{
						$_POST['product_id'] = (int)$_POST['product_id'];
						$_SESSION['compare'][$_POST['product_id']] = $_POST['product_id'];
						$answer['popup'] = '@app/views/compare/added.twig';
						$answer['man'] .= "$('.compare".$_POST['product_id']."').addClass('active');";
					}
					break;
				case 'remove':
					if (isset($_POST['product_id']) && $_POST['product_id'] > 0 && isset($_SESSION['compare'][$_POST['product_id']]))
					{
						unset($_SESSION['compare'][$_POST['product_id']]);
						$answer['location'] = 'reload';
					}
					break;
				case 'clear':
					$_SESSION['compare'] = [];
					$answer['location'] = 'reload';
					break;
			}
		}

		$answer['count'] = count($_SESSION['compare']);

		if (isset($answer['popup']))
		{
			$answer['popup'] = $this->renderFile($answer['popup'], [
				'compare' => $answer
			]);
		}

		echo json_encode($answer, JSON_NUMERIC_CHECK);
	}
}
