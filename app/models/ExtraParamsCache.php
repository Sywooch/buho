<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "extra_params_cache".
 *
 * @property integer $product_id
 * @property integer $param_id
 * @property integer $value_id
 * @property integer $range_id
 * @property integer $cat_id
 * @property string $param_name_1
 * @property string $param_name_2
 * @property integer $in_filter
 * @property integer $is_float
 * @property string $value_name_1
 * @property double $value_float
 * @property string $range_name_1
 * @property integer $param_sort
 * @property integer $value_sort
 * @property integer $range_sort
 */
class ExtraParamsCache extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extra_params_cache';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'param_id', 'value_id'], 'required'],
            [['product_id', 'param_id', 'value_id', 'range_id', 'cat_id', 'in_filter', 'is_float', 'param_sort', 'value_sort', 'range_sort'], 'integer'],
            [['value_float'], 'number'],
            [['param_name_1', 'param_name_2'], 'string', 'max' => 127],
            [['value_name_1'], 'string', 'max' => 255],
            [['range_name_1'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'param_id' => Yii::t('app', 'Param ID'),
            'value_id' => Yii::t('app', 'Value ID'),
            'range_id' => Yii::t('app', 'Range ID'),
            'cat_id' => Yii::t('app', 'Находится в разделе'),
            'param_name_1' => Yii::t('app', 'Param Name 1'),
            'param_name_2' => Yii::t('app', 'Param Name 2'),
            'in_filter' => Yii::t('app', 'Использовать в фильтре'),
            'is_float' => Yii::t('app', 'Is Float'),
            'value_name_1' => Yii::t('app', 'Value Name 1'),
            'value_float' => Yii::t('app', 'Value Float'),
            'range_name_1' => Yii::t('app', 'Range Name 1'),
            'param_sort' => Yii::t('app', 'Param Sort'),
            'value_sort' => Yii::t('app', 'Value Sort'),
            'range_sort' => Yii::t('app', 'Range Sort'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\Queries\ExtraParamsCache the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Queries\ExtraParamsCache(get_called_class());
    }

	//	Список параметров и значений для фильтра в категории
	public static function getCategoryFilter($ids, $selected)
	{
		//$lang  = (int)\app\models\Lang::getCurrentId();
		$conn = Yii::$app->getDb();

		$result = [];
		$ids = (array)$ids;
		if (!is_array($selected))
		{
			$selected = explode('-', (string)$selected);
			$selected = array_map('intval', $selected);
		}

        $lang = (int)\app\models\Lang::getCurrentId();
		//	Получаю список всех параметров и их значений для товаров из указанных категорий
		$q = "SELECT "
            ."v.`product_id`, "
            ."v.`param_id`, "
            ."v.`value_id`, "
            ."IF(v.`param_name_".$lang."`!='', v.`param_name_".$lang."`, v.`param_name_1`) AS `param_name`, "
            ."`param_name_alt`, "
            ."IF(v.`value_name_".$lang."`!='', v.`value_name_".$lang."`, v.`value_name_1`) AS `value_name`, "
            ."COUNT(*) AS `count` "
			."FROM `extra_params_cache` v "
			."WHERE v.`in_filter`=1 AND v.`cat_id` IN(".implode(', ', $ids).") "
			."GROUP BY v.`value_id` "
			."ORDER BY `param_sort` ASC, `param_name_1` ASC, `value_sort` ASC, `value_name_1` ASC";
		$res = $conn->createCommand($q)->queryAll();
		if (is_array($res) && count($res))
		{
			//	Получаю текущие параметры страницы
			$url_params = Yii::$app->getRequest()->getQueryParams();
			//	Выбор каждого параметра сбрасывает пейджинг
			$url_params['page'] = 1;
			//	Устанавливаю параметр фильтрации
			if (!isset($url_params['filter']))
			{
				$url_params['filter'] = [];
			}
			else
			{
				$url_params['filter'] = explode('-', $url_params['filter']);
				$url_params['filter'] = array_combine($url_params['filter'], $url_params['filter']);
			}
			//	Указание для генератора ссылок - использовать текущий роут
			$url_params[0] = '';

			foreach ($res as $row)
			{
				//	Определяю выбран ли пункт
				$row['selected'] = in_array($row['value_id'], $selected) ? true : false;
				//	Формирую ссылку для значения
				$url = $url_params;
				//	Добавляю или удаляю id значения из параметра filter
				if (isset($url['filter'][$row['value_id']]))
				{
					unset($url['filter'][$row['value_id']]);
				}
				else
				{
					$url['filter'][$row['value_id']] = $row['value_id'];
				}
				//	Собираю массив id значений в строку или удаляю параметр вообще, если в нём нет ни одного id
				if (count($url['filter']))
				{
					sort($url['filter']);
					$url['filter'] = implode('-', $url['filter']);
				}
				else
				{
					unset($url['filter']);
				}
				//	Генерирую URL
				$row['url'] = Url::toRoute($url);

				//	Добавляю параметр
				if (!isset($result[$row['param_id']]))
				{
					$result[$row['param_id']] = [
						'id' => $row['param_id'],
						'name' => $row['param_name']
					];
				}
				//	Добавляю значение в параметр
				$result[$row['param_id']]['values'][] = $row;
			}
		}

		return $result;
	}

	public static function getSelected($filter)
	{
		$result = [];

		if (is_array($filter) && count($filter))
		{
			foreach ($filter as $param)
			{
				if (isset($param['values']) && is_array($param['values']) && count($param['values']))
				{
					foreach ($param['values'] as $value)
					{
						if (isset($value['selected']) && $value['selected'])
						{
							$result[$value['value_id']] = $value;
						}
					}
				}
			}
		}

		return $result;
	}

	//	Значения фильтра по цене в указанной категории
	public static function getCategoryPrices($ids, $selected)
	{
		$conn = Yii::$app->getDb();

		$result = [
			'min_limit' => 0,
			'max_limit' => 1,
			'min' => 0,
			'max' => 1,
			'url' => Url::current(['price' => 'min-max'])
		];
		$ids = (array)$ids;
		if (!is_array($selected))
		{
			$selected = explode('-', (string)$selected);
			$selected = array_map('intval', $selected);
		}

		//	Получаю минимальную и максимальную цены товаров в указанных категориях
		$q = "SELECT MIN(`price`) AS `min_limit`, MAX(`price`) AS `max_limit` "
			."FROM `catalog_products` "
			."WHERE `cat_id` IN(".implode(', ', $ids).")";
		$res = $conn->createCommand($q)->queryAll();
		if (is_array($res) && count($res))
		{
			$result['min_limit'] = (int)$res[0]['min_limit'];
			$result['max_limit'] = (int)$res[0]['max_limit'];
			$result['min'] = $result['min_limit'];
			$result['max'] = $result['max_limit'];
			if (is_array($selected) && count($selected) == 2 && $selected[0] < $selected[1])
			{
				if ($selected[0] >= $result['min'] && $selected[0] < $result['max'])
				{
					$result['min'] = $selected[0];
				}
				if ($selected[1] > $result['min'] && $selected[1] <= $result['max'])
				{
					$result['max'] = $selected[1];
				}
			}
		}

		return $result;
	}

	//	Параметры товаров по ID
	public static function getProductParams($ids)
	{
		//$lang  = (int)\app\models\Lang::getCurrentId();
		$conn = Yii::$app->getDb();

		$result = [];
		$ids = (array)$ids;
		$q = "SELECT v.`product_id`, v.`param_id`, v.`value_id`, v.`param_name_1` AS `param_name`, `param_name_alt`, v.`value_name_1` AS `value_name` "
			."FROM `extra_params_cache` v "
			."WHERE v.`product_id` IN(".implode(', ', $ids).") "
			."ORDER BY `param_sort` ASC, `value_sort` ASC";
		$res = $conn->createCommand($q)->queryAll();
		if (is_array($res) && count($res))
		{
			foreach ($res as $row)
			{
				$result[$row['product_id']][] = $row;
			}
		}

		return $result;
	}

	//	Параметр товаров "Срок доставки" по ID
	public static function getProductDelivery($ids)
	{
		//$lang  = (int)\app\models\Lang::getCurrentId();
		$conn = Yii::$app->getDb();

		$result = [];
		$ids = (array)$ids;
		$q = "SELECT v.`product_id`, v.`value_id`, v.`value_name_1` AS `value_name` "
			."FROM `extra_params_cache` v "
			."WHERE v.`product_id` IN(".implode(', ', $ids).") AND v.`param_name_1` LIKE 'срок доставки' "
			."ORDER BY `param_sort` ASC, `value_sort` ASC";
		$res = $conn->createCommand($q)->queryAll();
		if (is_array($res) && count($res))
		{
			foreach ($res as $row)
			{
				$result[$row['product_id']] = $row['value_name'];
			}
		}

		return $result;
	}
}
