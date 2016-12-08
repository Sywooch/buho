<?php
class admin_users extends AdminTable
{
    public $SORT = 'name asc';
    public $NAME = 'Пользователи сайта';
	public $NAME2 = 'пользователя';
    //public $FIELD_UNDER = 'group_id';
   
    function __construct() {
        $this->fld = array(new Field("name","Имя",1),
        new Field("password","Пароль",1),
        new Field("email","E-mail",1, array('showInList'=>1)),
        new Field("phone","Телефон",1, array('showInList'=>1)),
        new Field("address","Адрес",1, array('showInList'=>1)),
		new Field("created", "Создан", 1, array('showInList'=>1))
        );
    }

};
