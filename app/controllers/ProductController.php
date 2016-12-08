<?php

namespace app\controllers;

use app\models\TextBlocks;
use Imagine\Image\Box;
use Yii;
use yii\base\Exception;
use yii\imagine\Image;
use yii\web\HttpException;
use app\models\CatalogCategories;
use app\models\CatalogProducts;
use app\components\BaseController;
use app\models\ExtraParamsCache;

class ProductController extends BaseController {

    public function actionIndex($alias)
	{
		$data = [];

        $data['product'] = CatalogProducts::find()->active()->base()->byAlias($alias)->one();
        if ($data['product'])
		{
            $data['product']->searchImages();
			$data['breadcrumbs'] = CatalogCategories::find()->byId($data['product']->cat_id)->one()->breadcrumbsForProduct;

            $data['seo_text'] = TextBlocks::find()->andWhere(['alias' => 'product-seo'])->one()->info->text;
            $data['seo_text'] = str_replace('&nbsp;', ' ', $data['seo_text']);
            $data['seo_text'] = preg_replace('#\{\{\s*product\.name\s*\}\}#', $data['product']->info->name, $data['seo_text']);
            $bk = array_slice($data['breadcrumbs'], -1, 1);
            $data['seo_text'] = preg_replace('#\{\{\s*category\.name\s*\}\}#', $bk[0]['label'], $data['seo_text']);

            $data['breadcrumbs'][] = $data['product']->info->name;

			//	Значения параметров товара
			$data['product_params'] = ExtraParamsCache::getProductParams($data['product']->id);
			//	Срок доставки для товара
			$data['product_delivery'] = ExtraParamsCache::getProductDelivery($data['product']->id);

			//	Модификации
            /*
			$data['modid'] = Yii::$app->request->get('mod');
			$data['mods'] = $data['product']->mods;
			if($data['modid'])
			{
				$data['active_mod'] = CatalogProducts::find()->byId($data['modid'])->one();
			}
			else
			{
				$data['active_mod'] = $data['mods'][0];
			}
            */

			//	Подарок
			if (strlen($data['product']->gift_articul))
			{
				$data['gift'] = CatalogProducts::find()->andWhere(['articul' => $data['product']->gift_articul])->one();
			}

			//	Рекомендуемые товары
			$data['recom'] = CatalogProducts::find()->recom()->active()->byCategoryIds($data['product']->cat_id)->orderBy('RAND()')->limit(10)->all();

			//	Похожие товары
            $data['similar'] = CatalogProducts::find()->active()->byCategoryIds($data['product']->cat_id)
                ->andWhere('price BETWEEN '.($data['product']->fullPrice * 0.8).' AND '.($data['product']->fullPrice * 1.2))->orderBy('RAND()')->limit(10)->all();

            //  С этим товаром также покупают
            //$data['also'] = $data['product']->similar;

            return $this->render('index.twig', $data);
        }
        throw new HttpException(404, Yii::t('app', 'Страница не найдена'));
    }

    //  Отображение фотографии товара с водяным знаком
    public function actionImage($alias, $number = 0)
    {
        $key = $alias.'-'.$number;
        $image = Yii::$app->cache->get($key);
        if (!$image)
        {
            $images = CatalogProducts::find()->active()->base()->byAlias($alias)->one()->searchImages();
            if (isset($images['b']) && is_array($images['b']) && isset($images['b'][(int)$number]))
            {
                $img = realpath(__DIR__.'/../..').$images['b'][(int)$number];
                $wtm = realpath(__DIR__.'/..').'/web/images/watermark.png';
                if (file_exists($img) && file_exists($wtm))
                {
                    $img = Image::getImagine()->open($img);
                    $wtm = Image::getImagine()->open($wtm);

                    if ($img->getSize()->getWidth() < $wtm->getSize()->getWidth())
                    {
                        $wtm->resize(new Box($img->getSize()->getWidth(), $wtm->getSize()->getHeight()));
                    }
                    if ($img->getSize()->getHeight() < $wtm->getSize()->getHeight())
                    {
                        $wtm->resize(new Box($wtm->getSize()->getWidth(), $img->getSize()->getHeight()));
                    }

                    $image = Image::watermark($img, $wtm)->get('jpg', ['quality' => 75]);

                    Yii::$app->cache->set($key, $image, 3600);
                }
            }
        }

        if (!$image)
        {
            throw new HttpException(404, Yii::t('app', 'Страница не найдена'));
        }
        else
        {
            header('Content-Type: image/jpeg');
            echo $image;
        }
    }

    public function actionModPrice(){
        $mod = Yii::$app->request->get('mod');
        $active_mod = CatalogProducts::find()->byId($mod)->one();
        $this->layout = false;
        return $this->render('price.twig',[
           'active_mod' => $active_mod, 
        ]);
    }

    public function actionAddProduct(){
        $id = Yii::$app->request->get('id');
        $count = Yii::$app->request->get('count');
        
        Yii::$app->session->open();
        $session = Yii::$app->session; 
        $cart = $session['cart'];
        if(isset($cart[$id])){
            $cart[$id] += $count;
        }
        else{
            $cart[$id] = $count;
        }
        $session['cart'] = $cart;
        
        $cart_count = array_sum($cart);
        $this->layout = false;
        return $cart_count;
    }

}
