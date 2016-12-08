<?php

namespace app\controllers;

use app\models\Feedbacks;
use app\models\Partners;
use app\models\User\User;
use Yii;
use yii\web\HttpException;
use app\models\Pages;
use app\models\Banners;
use app\models\News;
use app\components\BaseController;
use yii\data\Pagination;
use app\models\CatalogProducts;
use app\models\ExtraParamsCache;

class ContentController extends BaseController
{
    public function actionIndex()
    {
		$data = [];
        $data['banners'] = Banners::find()->orderBy('sort')->active()->all();
		$data['products'] = CatalogProducts::find()->active()->base()->recom()->andWhere('available>0')->orderBy('RAND()')->limit(9)->all();
		if ($data['products'])
		{
			//	Список ID отображаемых товаров
			$ids = [];
			foreach ($data['products'] as $product)
			{
				$ids[] = $product->id;
			}
			//	Значения параметров для отображаемых товаров
			$data['products_params'] = ExtraParamsCache::getProductParams($ids);
			//	Сроки доставки для отображаемых товаров
			$data['products_delivery'] = ExtraParamsCache::getProductDelivery($ids);
		}
		$data['news'] = News::find()->active()->orderBy('sort DESC')->limit(10)->all();

		return $this->render('index.twig', $data);
    }

    public function actionView($alias)
    {
		$page = Pages::find()->byAlias($alias)->one();
		if($page)
		{
			return $this->render('view.twig',[
				'page' => $page,
			]);
		}

		throw new HttpException(404, Yii::t('app', 'Страница не найдена'));
    }

    public function actionContacts()
	{
		$page = Pages::find()->byAlias('contacts.html')->one();
        if($page)
		{
			return $this->render('contacts.twig',[
				'page' => $page,
			]);
		}
		
		throw new HttpException(404, Yii::t('app', 'Страница не найдена'));
    }

    public function actionNews($type = 'news')
	{
        $page = Pages::find()->byAlias('news')->one();
        $query = News::find()->active()->andWhere(['is_article' => (int)($type == 'article')])->orderBy('pub_date DESC, sort');
        $countQuery = clone $query;
        $count = $countQuery->count();
        $pages = new Pagination(['totalCount' => $count]);
        $news = $query->orderBy('sort DESC')->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('news.twig',[
			'page' => $page,
			'pages' => $pages,
			'news' => $news,
            'type' => $type
		]);
    }
    
    public function actionNewsView($alias)
	{
        $one_new = News::find()->active()->byAlias($alias)->one() ;
        if($one_new)
		{
			return $this->render('news_view.twig',[
				'one_new' => $one_new,
			]);
		}
        throw new HttpException(404, Yii::t('app', 'Новость не найдена'));
    }

	public function actionPartners()
	{
		return $this->render('partners.twig',[
			'partners' => Partners::find()->andWhere(['show' => 1])->orderBy('sort DESC')->all(),
		]);
	}

	public function actionFeedbacks()
	{
		$feedbacks = Feedbacks::find()->andWhere(['show' => 1])->orderBy('added DESC')->asArray()->all();

		return $this->render('feedbacks.twig',[
			'feedbacks' => $feedbacks,
			'user' => User::getCurrent()
		]);
	}

	public function actionRequest()
	{
		$result = [];

		if (isset($_POST['method']))
		{
			switch($_POST['method'])
			{
				case 'save_feedback':
					$fb = new Feedbacks();
					$fb->setAttributes($_POST);
					if ($fb->validate())
					{
						$fb->save();
						$result['message'] = Yii::t('app', 'Спасибо. Ваш отзыв сохранён');
						$result['man'] = "container.find('textarea').val('');";
					}
					else
					{
						$result['message'] = $fb->getFirstErrors();
						if (is_array($result['message']))
						{
							$result['error_field'] = array_keys($result['message']);
							$result['message'] = array_unique($result['message']);
							$result['message'] = implode('<br>', $result['message']);
						}
					}

					break;
			}
		}

		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}
}