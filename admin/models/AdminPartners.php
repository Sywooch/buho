<?php
/*
 *  Партнеры
 * */

class admin_partners extends AdminTable
{
	
	public $IMG_SIZE = 175; // макс высота 
	public $IMG_RESIZE_TYPE = 1; //рeсайз по высоте
	public $IMG_BIG_SIZE = 175 ;
	public $IMG_BIG_VSIZE = 100;
	public $IMG_NUM = 1;
	public $ECHO_NAME = 'name';
    public $SORT = 'sort DESC';
   
	public $NAME = "Партнеры";
	public $NAME2 = "партнера";
		
	function __construct()
	{
		
            $this->fld[] = new Field("name","Название)",1);
            $this->fld[] = new Field("site","Сайт",1,array('showInList'=>1));
            $this->fld[] = new Field("about","Текст",2);
            $this->fld[] = new Field("show","Опубликовать",6,array('showInList'=>1, 'editInList'=>1));
            $this->fld[] = new Field("sort","SORT",4);
		
	}
    

}


