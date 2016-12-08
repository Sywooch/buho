<?php

namespace app\components;

use app\models\Orders;
use app\models\User\User;
use Yii;

use app\models\Pages;
use app\models\Lang;
use app\models\Slovar;
use app\models\TextBlocks;
use app\models\CatalogCategories;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class BaseController extends \yii\web\Controller
{
	public $default_content;

	public function init(){
	    parent::init();
		
		$this->layout = 'main.twig';
		
		Yii::$app->session->open();
		$session = Yii::$app->session;

		//	Информация об авторизованном пользователе
		$this->view->params['user'] = User::getCurrent();

		//	Информация о списке сравнения
		$this->view->params['compare'] = [
			'url' => Url::to('/compare/'),
			'count' => isset($_SESSION['compare']) && is_array($_SESSION['compare']) ? count($_SESSION['compare']) : 0
		];

		//Обработка корзины
		$this->view->params['cart'] = Orders::getCartInfo();

		//	Поиск
		$this->view->params['search'] = Yii::$app->request->get('s');

	    $lang = Lang::getCurrent();
	    $this->view->params['lang'] = $lang;
	    $this->view->params['lang_sh'] = mb_substr(($lang->name),0,3, 'utf-8');
	    $langs = Lang::find()->all();
	    $this->view->params['langs'] = $langs;
	    $current_url=Yii::$app->request->pathinfo;
	    // request url	
   		$this->view->params['current_url']=$current_url;


		$slovar = Slovar::find()
						->leftJoin('slovar_info', '`record_id`=`id`')
						->select(['alias', 'value'])
						->asArray()
						->all();
		$slovar = ArrayHelper::map($slovar, 'alias', 'value');
		$this->view->params = array_merge($this->view->params, $slovar);

        $blocks = TextBlocks::find()->byAlias(['legal_add', 'add'])->asArray()->all();
        if ($blocks)
        {
            foreach ($blocks as $block)
            {
                $this->view->params[$block['alias']] = $block['info']['text'];
            }
        }

		$this->view->params['parent_categories'] = CatalogCategories::find()->first_level()->orderBy("sort DESC")->all();

		if($lang->default){
			$this->view->params['lang_url']='';
			Yii::$app->homeUrl =$this->view->params['home_url']='/';
			$this->view->params['current_url']= $current_url ? "/{$current_url}": '/';
			
		}else{
			$this->view->params['lang_url']="/{$lang->url}";
			Yii::$app->homeUrl = $this->view->params['home_url']="/{$lang->url}/";
			$this->view->params['current_url']="/{$lang->url}/{$current_url}";
	    }
	    
   		$menu_pages = Pages::find()->inMenu()->orderBy('sort')->all();
   		$this->view->params['menu_pages']=$menu_pages;
   		//$current_route = Yii::$app->requestedRoute;						// request route
	    //$alias=$this->getAlias($current_url);
		/*$cur_page=Pages::find()->published()->joinWith('info')->byAlias($alias)->andWhere(['route'=>['',$current_route]])->one();
		if($cur_page){
			$this->cur_page=$cur_page;
		}else{
			$this->cur_page=false;
		}
	    
	    $menu_items=Menus::find()->menuInfo()->all();
	    $this->view->params['menu_items']=$menu_items;
		
		// Seo
		
		$seo=Seo::find()->joinWith('info')->byUrl($current_url)->one();
		$this->view->params['seo']=$seo;
		if($seo){
			Yii::$app->view->title= ($seo->info->title) ? $seo->info->title : '';
			if(!empty($seo->info->description)){
				Yii::$app->view->registerMetaTag([
					'name' => 'description',
					'content' => $seo->info->description
				],'description');
			}
			if(!empty($seo->info->key_words)){
				Yii::$app->view->registerMetaTag([
					'name' => 'keywords',
					'content' => $seo->info->key_words
				],'keywords');
			}
			
		}
		*/
		//$this->view->params['s']='';
		$this->default_content=[
			
		];
		
	}
	

	private function getAlias($url)
	{
		$slash_array=explode('/',$url);
		$extn_array=explode('.html',end($slash_array));
		$alias=reset($extn_array);
		return $alias;
	}
} 

