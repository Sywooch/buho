<?
namespace app\controllers;

use app\models\User\User;
use Yii;
use app\models\Orders;
use app\components\BaseController;
use yii\helpers\Url;

class WishlistController extends BaseController
{
	public function actionRequest()
	{
		$answer = [
			'result' => 'ok',
			'url' => Url::to('/user/wishlist'),
			'man' => ""
		];

		$user = User::getCurrent();

		if ($user->id > 0 && isset($_POST['method']))
		{
			$conn = Yii::$app->getDb();
			switch ($_POST['method'])
			{
				case 'add':
					if (isset($_POST['product_id']) && $_POST['product_id'] > 0)
					{
						$_POST['product_id'] = (int)$_POST['product_id'];
						$q = "INSERT IGNORE INTO `users_wishlist` (`user_id`, `product_id`) "
							."VALUES (".(int)$user->id.", ".$_POST['product_id'].")";
						$conn->createCommand($q)->query();

						$answer['popup'] = '@app/views/wishlist/added.twig';
						$answer['man'] .= "$('.wishlist".$_POST['product_id']."').addClass('active');";
					}
					break;
				case 'remove':
					if (isset($_POST['product_id']) && $_POST['product_id'] > 0)
					{
						$_POST['product_id'] = (int)$_POST['product_id'];
						$q = "DELETE FROM `users_wishlist` "
							."WHERE `user_id`=".(int)$user->id." AND `product_id`=".$_POST['product_id'];
						$conn->createCommand($q)->query();

						$answer['location'] = 'reload';
					}
					break;
				case 'clear':
					$q = "DELETE FROM `users_wishlist` "
						."WHERE `user_id`=".(int)$user->id;
					$conn->createCommand($q)->query();

					$answer['location'] = 'reload';
					break;
			}
		}

		if (isset($answer['popup']))
		{
			$answer['popup'] = $this->renderFile($answer['popup'], [
				'wishlist' => $answer
			]);
		}

		echo json_encode($answer, JSON_NUMERIC_CHECK);
	}
}
