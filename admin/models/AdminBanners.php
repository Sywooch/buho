<?
/*
 *  Баннеры
 * */

class admin_banners extends AdminTable
{
	public $SORT = 'sort';
	public $TABLE = 'banners';
	public $IMG_BIG_SIZE = 1500;
	public $IMG_BIG_VSIZE = 500;
	public $IMG_NUM = 1;
	public $ECHO_NAME = 'name';
	public $IMG_SIZE = 960; //- размер маленькой картинки
	public $IMG_RESIZE_TYPE = 1; // - тип ресайза (1 - ширина, 2 - высота, 3 - вписать в квадрат)
   // public $FIELD_UNDER = 'parent_id';
	public $NAME = "Баннеры";
	public $NAME2 = "баннер";
	
	public $MULTI_LANG = 1;
	public $USE_TAGS = 0;
	
	function __construct()
	{
			$this->fld=array(
			new Field("name","Название",1),
			new Field("title_top","Заголовок",1, array('multiLang'=>1)),
			//new Field("title_mid","Заголовок (середина)",1, array('multiLang'=>1)),
			//new Field("title_bot","Заголовок (низ)",1, array('multiLang'=>1)),
			new Field("href_url","Url",1, array('multiLang'=>1)),
			//new Field("button_color","Цвет кнопки (Формат: #ХХХХХХ)",1),
			new Field("sort","SORT",4),
			new Field("status","Отображать",6, array('showInList'=>1, 'editInList'=>1)),
			new Field("creation_time","Date of creation",4),
			new Field("update_time","Date of update",4),
		);       
	}
};