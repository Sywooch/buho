<?php

namespace app\controllers;

use app\models\ExtraParamsCache;
use app\models\User\User;
use yii\web\HttpException;
use app\models\TextBlocks;
use app\models\CatalogCategories;
use app\models\CatalogProducts;
use app\components\BaseController;
use yii\data\Pagination;
use yii\helpers\Url;
use Yii;

class CatalogController extends BaseController
{
	public function getSort()
	{
		//	Получаю текущие параметры страницы
		$url_params = Yii::$app->getRequest()->getQueryParams();
		//	Указание для генератора ссылок - использовать текущий роут
		$url_params[0] = '';
		if (!isset($url_params['order']))
		{
			$url_params['order'] = 'default';
		}

		//	Виды сортировок
		$sort = [
			'all' => [
				[
					'key' => 'default',
					'name' => Yii::t('app', 'рекомендуемые'),
					'sql' => '`avail_text` ASC, `recom` DESC, `ordered` DESC',
					'selected' => '',
					'url' => '',
				],
				[
					'key' => 'cheap',
					'name' => Yii::t('app', 'от дешевых к дорогим'),
					'sql' => '`price` ASC',
					'selected' => '',
					'url' => '',
				],
				[
					'key' => 'expensive',
					'name' => Yii::t('app', 'от дорогих к дешёвым'),
					'sql' => '`price` DESC',
					'selected' => '',
					'url' => '',
				],
			],
			'selected' => []
		];
		//	Сортировка по-умолчанию
		$sort['selected'] = $sort['all'][0];

		foreach ($sort['all'] as $pos => $data)
		{
			//	Формирую ссылку сортировки
			$url = $url_params;
			if ($data['key'] != 'default')
			{
				$url['order'] = $data['key'];
			}
			else
			{
				unset($url['order']);
			}
			$sort['all'][$pos]['url'] = Url::toRoute($url);

			//	Отмечаю выбранный тип сортировки и добавляю его в секцию selected
			if ($url_params['order'] == $data['key'])
			{
				$sort['all'][$pos]['selected'] = 'selected';
				$sort['selected'] = $sort['all'][$pos];
			}
		}

		return $sort;
	}

    public function actionIndex($alias, $filter = '', $price = '')
	{
		$data = [];

        $data['category'] = CatalogCategories::find()->byAlias($alias)->one();
        if ($data['category'])
		{
			//	Все значения фильтра в текущей категории
			$data['filter'] = ExtraParamsCache::getCategoryFilter($data['category']->idsForCatalog, $filter);
			//	Выбранные в фильтре значения
			$data['selected'] = ExtraParamsCache::getSelected($data['filter']);
			//	Значения фильтра по цене в текущей категории
			$data['price'] = ExtraParamsCache::getCategoryPrices($data['category']->idsForCatalog, $price);
			//	Настройка выборки товаров
			$query = CatalogProducts::find()->byCategoryIds($data['category']->idsForCatalog)->active()->base();
			//	Количество всех товаров в категории
			$data['count_total'] = $query->count();
			//	Количество выбранных товаров
			$data['count_current'] = $data['count_total'];
			$query = $query->byPrice($data['price']['min'], $data['price']['max']);
			if ($data['selected'])
			{
				//	Фильтрация товаров
				$query = $query->byFilter($data['selected']);
			}
			$data['count_current'] = $query->count();

			if($filter || $price)
			{
				$data['reset_filter'] = $data['category']->getUrl();
			}

			//	Постраничная навигация
			$data['pages'] = new Pagination(['totalCount' => $data['count_current']]);
			//	Сортировка
			$sort = $this->getSort();
			$query = $query->orderBy($sort['selected']['sql']);
			$data['sort'] = $sort['all'];
			//	Товары на странице
			$data['products'] = $query->offset($data['pages']->offset)->limit($data['pages']->limit)->all();

            return $this->render('index.twig', $data);
        }
        throw new HttpException(404, 'No such page!');
    }

    public function actionSearch()
	{
        $search = Yii::$app->request->get('s');
        $query = CatalogProducts::find()->bySearch($search)->active()->base();
        $countQuery = clone $query;
        $count = $countQuery->count();
        $pages = new Pagination(['totalCount' => $count]);

		//	Сортировка
		$sort = $this->getSort();
		$query = $query->orderBy($sort['selected']['sql']);

        $products = $query->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('search.twig', [
			'products' => $products,
			'count_products' => $count,
			'sort' => $sort['all'],
			'pages' => $pages,
			'search' => $search,
		]);
    }

	public function actionActions()
	{
		$query = CatalogProducts::find()->active()->base()->andWhere('LENGTH(`gift_articul`)>0 AND `gift_end_date`>=NOW()')->andWhere('available>0');
		$countQuery = clone $query;
		$count = $countQuery->count();
		$pages = new Pagination(['totalCount' => $count]);

		//	Сортировка
		$sort = $this->getSort();
		$query = $query->orderBy($sort['selected']['sql']);

		$products = $query->offset($pages->offset)->limit($pages->limit)->all();

		return $this->render('actions.twig', [
			'products' => $products,
			'count_products' => $count,
			'type' => 'actions',
			'pages' => $pages,
		]);
	}

	public function actionDiscounts()
	{
		$ip = '(ip)?';
		$N = 'N?';
		$W = 'W?';

		//	Скидка по IP (действует для всех, кроме посетителей из Киева)
		if (mb_strtolower(User::getIPCity(), 'UTF-8') == 'киев')
		{
			$ip = '';
		}
		//	Ночная скидка (с 19:30 до 07:30)
		if (date('H:i') > '07:30' && date('H:i') <= '19:30')
		{
			$N = '';
		}
		//	Скидки в выходные
		if (!in_array(date('w'), [0, 6]))
		{
			$W = '';
		}

		$query = CatalogProducts::find()->active()->base()->andWhere('`discount_formula`!=0')->andWhere('`discount_formula` REGEXP "^[0-9]+%?'.$ip.$N.$W.'$"')->andWhere('available>0');
		$countQuery = clone $query;
		$count = $countQuery->count();
		$pages = new Pagination(['totalCount' => $count]);

		//	Сортировка
		$sort = $this->getSort();
		$query = $query->orderBy($sort['selected']['sql']);

		$products = $query->offset($pages->offset)->limit($pages->limit)->all();

		return $this->render('actions.twig', [
			'products' => $products,
			'count_products' => $count,
			'type' => 'discounts',
			'pages' => $pages,
		]);
	}
}
