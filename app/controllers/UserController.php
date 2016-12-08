<?
namespace app\controllers;

use app\models\CatalogProducts;
use app\models\Comments;
use app\models\ExtraParamsCache;
use app\models\NovaPoshta;
use app\models\Orders;
use app\models\User\RegistrationForm;
use app\models\User\RestoreForm;
use app\models\User\UpdateForm;
use app\models\User\User;
use app\models\User\LoginForm;
use Yii;
use app\models\Feedback;
use app\components\BaseController;
use yii\data\Pagination;
use yii\helpers\Url;

class UserController extends BaseController
{

	/*
	 * Кабинет - авторизация
	 */
	public function actionAuth()
	{
		if (User::getCurrent()->getId() > 0)
		{
			$this->redirect('/user/profile');
		}

		return $this->render('index.twig', [
			'template' => 'auth.twig'
		]);
	}

	/*
	 * Кабинет - личные данные
	 */
	public function actionProfile()
	{
		if (!User::getCurrent()->getId())
		{
			$this->redirect('/user/auth');
		}

		return $this->render('index.twig', [
			'template' => 'profile.twig',
			'user' => User::getCurrent()
		]);
	}

	/*
	 * Кабинет - история заказов
	 */
	public function actionOrders()
	{
		if (!User::getCurrent()->getId())
		{
			$this->redirect('/user/auth');
		}

		$data = [
			'template' => 'orders.twig'
		];

		$orders = new \app\models\Queries\Orders('app\models\Orders');
		$orders->andWhere(['user_id' => User::getCurrent()->getId()])->orderBy('`id` DESC');
		$countQuery = clone $orders;

		//	Постраничная навигация
		$data['pages'] = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
		//	Заказы на странице
		$data['orders'] = $orders->offset($data['pages']->offset)->limit($data['pages']->limit)->all();

		return $this->render('index.twig', $data);
	}

	/*
	 * Кабинет - список желаний
	 */
	public function actionWishlist()
	{
		if (!User::getCurrent()->getId())
		{
			$this->redirect('/user/auth');
		}

		$data = [
			'template' => 'wishlist.twig'
		];

		$ids = User::getCurrent()->getWishlist();
		if ($ids)
		{
			$data['products'] = CatalogProducts::find()->byId($ids)->active()->base()->offset($data['pages']->offset)->limit($data['pages']->limit)->all();
			if ($data['products'])
			{
				$data['wishlist_editor'] = 1;
				$data['summary'] = [
					'count' => 0,
					'cost' => 0
				];
				//	Список ID отображаемых товаров
				$ids = [];
				foreach ($data['products'] as $product)
				{
					$ids[] = $product->id;
					$data['summary']['count']++;
					$data['summary']['cost'] += $product->userPrice;
				}
				//	Значения параметров для отображаемых товаров
				$data['products_params'] = ExtraParamsCache::getProductParams($ids);
				//	Сроки доставки для отображаемых товаров
				$data['products_delivery'] = ExtraParamsCache::getProductDelivery($ids);
			}
		}

		return $this->render('index.twig', $data);
	}

	/*
	 * Кабинет - корзина
	 */
	public function actionCart()
	{
		return $this->render('index.twig', [
			'cart' => Orders::getCartInfo(),
			'template' => 'cart.twig',
			'user' => User::getCurrent(),
			'params' => Orders::getOrdersParams(),
			'cities' => NovaPoshta::getCities()
		]);
	}

	/*
	 * Кабинет - заказ оформлен
	 */
	public function actionOrderdone()
	{
		return $this->render('index.twig', [
			'template' => 'orderdone.twig',
		]);
	}

	/*
	 * Кабинет - AJAX запросы
	 */
	public function actionRequest()
	{
		$answer = [
			'result' => 'ok',
		];

		if (isset($_POST['method']))
		{
			switch ($_POST['method'])
			{
				//	Окно обратного звонка
				case 'callback_window':
					$answer['popup'] = '@app/views/user/callbackForm.twig';
					break;
				//	Сохранение запроса обратной связи
				case 'feedback':
				case 'callback':
					$fb = new Feedback();
					$fb->type = $_POST['method'];
					$fb->referer = $_SERVER['HTTP_REFERER'];
					$fb->created = date('Y-m-d H:i:s');
					$fb->setAttributes($_POST);
					if ($fb->save())
					{
						$answer['message'] = Yii::t('app', 'Ваша заявка отправлена');
						$answer['man'] = "$(form).find('input, textarea').val('');setTimeout(function(){ $.magnificPopup.close() }, 2000);";
					}
					else
					{
						$answer['message'] = $fb->getFirstErrors();
						if (is_array($answer['message']))
						{
							$answer['error_field'] = array_keys($answer['message']);
							$answer['message'] = implode('<br>', $answer['message']);
						}
					}
					break;
				//	Окно регистрации
				case 'registration_window':
					$answer['popup'] = '@app/views/user/registrationForm.twig';
					break;
				//	Регистрация покупателя
				case 'registration':
					$r = new RegistrationForm();
					$r->setAttributes($_POST);
					if ($r->validate())
					{
						$u = new User();
						$u->setAttributes($_POST);
						$u->save(false);

                        $answer['message'] = Yii::t('app', 'Вы успешно зарегистрированы');
                        $answer['man'] = "$(form).find('input, textarea').val('');setTimeout(function(){ $.magnificPopup.close() }, 2000);";

                        //  Формирование и отправка письма о регистрации
                        $letter = $this->renderPartial('registrationLetter.twig', [
                            'user' => $u,
                            'root' => Url::home(true)
                        ]);

                        Yii::$app->mailer
                            ->compose()
                            ->setFrom('admin@enerline.ua')
                            ->setTo($u->email)
                            ->setSubject($u->name.', '.Yii::t('app', 'Вы зарегистрировались на').' '.$_SERVER['HTTP_HOST'])
                            ->setHtmlBody($letter)
                            ->send();
					}
					else
					{
						$answer['message'] = $r->getFirstErrors();
						if (is_array($answer['message']))
						{
							$answer['error_field'] = array_keys($answer['message']);
							$answer['message'] = implode('<br>', $answer['message']);
						}
					}
					break;
				//	Окно восстановления пароля
				case 'restore_window':
					$answer['popup'] = '@app/views/user/restoreForm.twig';
					break;
				//	Отправка заявки на восстановление пароля
				case 'restore':
                    $r = new RestoreForm();
                    $r->setAttributes($_POST);
                    if ($r->validate())
                    {
                        $u = User::findOne(['email' => $r->email]);
                        if ($u->id > 0)
                        {
                            $newPassword = User::getRandomPassword();
                            $u->setAttribute('password_restore', sha1($newPassword));
                            $u->save(false);

                            $answer['message'] = Yii::t('app', 'На ваш E-mail отправлено письмо с новым паролем');
                            $answer['man'] = "$(form).find('input').val('');setTimeout(function(){ $.magnificPopup.close() }, 2000);";

                            //  Формирование и отправка письма о восстановлении пароля
                            $letter = $this->renderPartial('restoreLetter.twig', [
                                'user' => $u,
                                'root' => Url::home(true),
                                'newPassword' => $newPassword
                            ]);

                            Yii::$app->mailer
                                ->compose()
                                ->setFrom('admin@enerline.ua')
                                ->setTo($u->email)
                                ->setSubject($u->name.', '.Yii::t('app', 'Ваш новый пароль на').' '.$_SERVER['HTTP_HOST'])
                                ->setHtmlBody($letter)
                                ->send();
                        }
                        else
                        {
                            $answer['message'] = Yii::t('app', 'Пользователя с таким E-mail нет в базе');
                        }
                    }
                    else
                    {
                        $answer['message'] = $r->getFirstErrors();
                        if (is_array($answer['message']))
                        {
                            $answer['error_field'] = array_keys($answer['message']);
                            $answer['message'] = implode('<br>', $answer['message']);
                        }
                    }
					break;
				//	Авторизация
				case 'login':
					if (Yii::$app->user->isGuest)
					{
						$l = new LoginForm();
						$l->setAttributes($_POST);
						if ($l->validate())
						{
							$u = User::findByEmail($l->email);
							if ($u && ($u->password == sha1($l->password) || $u->password_restore == sha1($l->password)))
							{
                                if (Yii::$app->user->login($u))
                                {
                                    $answer['message'] = Yii::t('app', 'Авторизация выполнена');
                                    $answer['location'] = 'reload';
                                }
                                else
                                {
                                    $answer['message'] = Yii::t('app', 'Не удалось выполнить авторизацию');
                                }
							}
							else
							{
								$answer['message'] = Yii::t('app', 'Не верный E-mail или пароль');
							}
						}
						else
						{
							$answer['message'] = $l->getFirstErrors();
							if (is_array($answer['message']))
							{
								$answer['error_field'] = array_keys($answer['message']);
								$answer['message'] = implode('<br>', $answer['message']);
							}
						}
					}
					else
					{
						$answer['message'] = Yii::t('app', 'Вы уже авторизованы. Обновите страницу');
					}
					break;
				//	Выход
				case 'logout':
					if (!Yii::$app->user->isGuest)
					{
						Yii::$app->user->logout(FALSE);
					}
					$answer['location'] = 'reload';
					break;
				//	Обновление персональных данных
				case 'update':
					$user = User::getCurrent();
					if ($user->id > 0)
					{
						$r = new UpdateForm();
						$r->setAttributes($_POST);
						if ($r->validate())
						{
							$user->setAttributes($_POST);
							$user->save(false);

							$answer['message'] = Yii::t('app', 'Данные обновлены');
						}
						else
						{
							$answer['message'] = $r->getFirstErrors();
							if (is_array($answer['message']))
							{
								$answer['error_field'] = array_keys($answer['message']);
								$answer['message'] = implode('<br>', $answer['message']);
							}
						}
					}
					break;
                case 'comment_window':
                    if (isset($_POST['product_id']) && $_POST['product_id'] > 0)
                    {
                        $answer['popup'] = '@app/views/user/commentForm.twig';
                        $answer['product'] = $_POST['product_id'];
                    }
                    break;
                case 'comment':
                    $com = new Comments();
                    $com->setAttributes($_POST);
                    if ($com->save())
                    {
                        $answer['message'] = Yii::t('app', 'Ваш отзыв сохранён');
                        $answer['man'] = "$(form).find('input, textarea').val('');setTimeout(function(){ $.magnificPopup.close() }, 2000);";
                    }
                    else
                    {
                        $answer['message'] = $com->getFirstErrors();
                        if (is_array($answer['message']))
                        {
                            $answer['error_field'] = array_keys($answer['message']);
                            $answer['message'] = implode('<br>', $answer['message']);
                        }
                    }
                    break;
			}
		}

		//$answer = array_merge($answer, User::getCurrent());

		//	Рендеринг HTML кода всплывающих окон
		if (isset($answer['popup']))
		{
			$answer['popup'] = $this->renderFile($answer['popup'], [
				'user' => $answer
			]);
		}

		echo json_encode($answer, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
	}
}
