<?php
/*
 *  Новости
 * */

class admin_news extends AdminTable
{
	
	public $TABLE = 'news';
	public $IMG_SIZE = 210; // макс высота 
	public $IMG_RESIZE_TYPE = 2; //рeсайз по высоте
	public $IMG_BIG_SIZE = 210 ;
	public $IMG_BIG_VSIZE = 190;
	public $IMG_NUM = 1;
	public $ECHO_NAME = 'title';
        public $SORT = 'pub_date DESC, sort';
   
	public $NAME = "Новости";
	public $NAME2 = "новость";
	
	public $MULTI_LANG = 1;
	
	function __construct()
	{
		
            $this->fld[] = new Field("alias","Alias (геренерируеться, если не заполнен)",1);
            $this->fld[] = new Field("pub_date","Дата публикации",13,array('showInList'=>1));

            $this->fld[] = new Field("title","Заголовок",1, array('multiLang'=>1));
            $this->fld[] = new Field("description","Анонс",2, array('multiLang'=>1));
            $this->fld[] = new Field("text","Текст",2, array('multiLang'=>1));
            $this->fld[] = new Field("useful","Полезная информация",6,array('showInList'=>1, 'editInList'=>1));
            $this->fld[] = new Field("status","Опубликовать",6,array('showInList'=>1, 'editInList'=>1));
            $this->fld[] = new Field("sort","SORT",4);
            $this->fld[] = new Field("creation_time","Date of creation",4);
            $this->fld[] = new Field("update_time","Date of update",4);
		
	}
    
    function afterAdd($row) {
            
        if (empty($row['alias'])) {
			$qup = "UPDATE " . $this->TABLE . " SET alias = '" . Translit($row['title_1'])."-". $row['id'] ."' WHERE id = " 
			. $row['id'];
			pdoExec($qup);
		}
    }



}


