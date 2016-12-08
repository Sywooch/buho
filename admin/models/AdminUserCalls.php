<?php
/*
 *  Звонки
 * */

class admin_users_feedback extends AdminTable
{
	
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'phone';
    
   
	public $NAME = "Запросы на обратные звонки";
	public $NAME2 = "запрос";
	public $SORT = 'done, id DESC';
	public $MULTI_LANG = 0;
	
	function __construct()
	{
		
            $this->fld[] = new Field("phone","Телефон",1);
            $this->fld[] = new Field("name","Имя",1,['showInList'=>1]);
            $this->fld[] = new Field("referer","Страница сайта",1,['showInList'=>1]);
            $this->fld[] = new Field("created","Дата и время",13, ['showInList'=>1]);
            $this->fld[] = new Field("done","Обработан",6, ['showInList'=>1, 'editInList'=>1]);
		
	}
    
}
/*
 *  Запросы с формы обратной связи на странице контакты
 * */

class admin_contact_form extends AdminTable
{
	
	public $TABLE = 'contact_form';
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name';
    
   
	public $NAME = "Запросы с формы обратной связи на странице контакты";
	public $NAME2 = "запрос";
	public $SORT = 'status, creation_time DESC';
	public $MULTI_LANG = 0;
	
	function __construct()
	{
		
            $this->fld[] = new Field("name","Имя",1);
            $this->fld[] = new Field("tel","Телефон",1, array('showInList'=>1));
            $this->fld[] = new Field("email","Email",1, array('showInList'=>1));
            $this->fld[] = new Field("massage","Сообщение",2);
            $this->fld[] = new Field("date","Дата и время",5, array('showInList'=>1));
            $this->fld[] = new Field("status","Прозвонен",6,array('showInList'=>1));
            $this->fld[] = new Field("creation_time","Date of creation",4);
            $this->fld[] = new Field("update_time","Date of update",4);
		
	}
    
    function show_date($row) {
       
            return date("d/m/y h:i" , $row['creation_time']);
       
    }
}

