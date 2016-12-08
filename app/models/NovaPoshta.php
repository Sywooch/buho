<?php

namespace app\models;

use Yii;
use yii\base\Model;

class NovaPoshta extends Model
{
	public static function getCities($city_name = NULL)
	{
		$cities = [];

		$conn = Yii::$app->getDb();
		$q = "SELECT `id`, `name_ru` "
			."FROM `np_cities` ";
		if (strlen($city_name))
		{
			$q .= "WHERE `name_ru` LIKE '".$conn->quoteSql($city_name)."' ";
		}
		$q .= "ORDER BY `name_ru` ASC";
		$res = $conn->createCommand($q)->queryAll();
		if (is_array($res) && count($res))
		{
			foreach ($res as $row)
			{
				$cities[$row['id']] = $row['name_ru'];
			}
		}

		return $cities;
	}

	public static function getFilials($city_id)
	{
		$filials = [];

		if ($city_id > 0)
		{
			$conn = Yii::$app->getDb();
			$q = "SELECT f.`id`, f.`address`, f.`phone`, f.`worktime`, f.`city_id` "
				."FROM `np_filials` f "
				."WHERE f.`city_id`=".(int)$city_id." "
				."ORDER BY f.`number` ASC";
			$res = $conn->createCommand($q)->queryAll();
			if (is_array($res) && count($res))
			{
				foreach ($res as $row)
				{
					$filials[$row['id']] = $row;
				}
			}
		}

		return $filials;
	}
}
