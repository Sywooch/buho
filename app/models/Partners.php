<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property integer $status
 * @property integer $creation_time
 * @property integer $update_time
 *
 * @property BannersInfo[] $bannersInfos
 */
class Partners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

	public function getLogo()
	{
		$fname = '/images/partners/'.$this->id.'.1.b.jpg';
		if (file_exists(__DIR__.'/../..'.$fname))
		{
			return $fname;
		}

		return '/images/no-image.png';
	}
}
