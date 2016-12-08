<?php
/*
 *  Фото в галерее
 * */

class admin_fotos extends AdminTable
{
	public $SORT = 'sort';
	public $TABLE = 'fotos';
	public $IMG_BIG_SIZE = 964;
	public $IMG_BIG_VSIZE = 723;
	public $IMG_NUM = 1;
	public $ECHO_NAME = 'name';
        public $IMG_SIZE = 320; //- размер маленькой картинки
	public $IMG_RESIZE_TYPE = 1; // - тип ресайза (1 - ширина, 2 - высота, 3 - вписать в квадрат)
	public $NAME = "Фото в галерее";
	public $NAME2 = "фото";
	
	public $MULTI_LANG = 1;
	public $USE_TAGS = 0;
	
	function __construct()
	{
			$this->fld=array(
			new Field("name","Название",1,array('multiLang'=>1)),
			new Field("sort","SORT",4),
			new Field("status","Отображать",6, array('showInList'=>1, 'editInList'=>1)),
			new Field("creation_time","Date of creation",4),
			new Field("update_time","Date of update",4),
		);       
	}

	

}
/*
 *  Видео в галерее
 * */

class admin_videos extends AdminTable
{
	public $SORT = 'sort';
	public $TABLE = 'videos';
	public $IMG_BIG_SIZE = 320;
	public $IMG_BIG_VSIZE = 180;
	public $IMG_NUM = 1;
	public $ECHO_NAME = 'name';
        public $IMG_SIZE = 320; //- размер маленькой картинки
	public $IMG_RESIZE_TYPE = 1; // - тип ресайза (1 - ширина, 2 - высота, 3 - вписать в квадрат)
	public $NAME = "Видео в галерее";
	public $NAME2 = "видео";
	
	public $MULTI_LANG = 1;
	public $USE_TAGS = 0;
	
	function __construct()
	{
			$this->fld=array(
			new Field("name","Название",1,array('multiLang'=>1)),
                        new Field("url","Ссылка на видео (Пример: https://youtu.be/mawTCv4BvGY )",1),    
			new Field("sort","SORT",4),
			new Field("status","Отображать",6, array('showInList'=>1, 'editInList'=>1)),
			new Field("creation_time","Date of creation",4),
			new Field("update_time","Date of update",4),
		);       
	}

	

}