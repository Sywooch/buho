<?php
use Yii;
use app\models\Suits;
use app\models\Sliders;
use app\models\SuitsDescription;
use app\components\BaseController;

class SuitsController extends BaseController {
   
public function actionView() {
    $id =  Yii::$app->request->get('id');
    $lang = Yii::$app->request->get('lang');
    $suit_alias = Suits::findOne($id);
    $data['description'] = SuitsDescription::findOne($id, $lang);
    $data['slider'] = Sliders::find()->where(['page_id'=>'id'])->asArray()->all();
}
    
    
    
}
