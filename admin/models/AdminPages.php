<?
/*
 *  Текстовые страницы
 * */

class admin_pages extends AdminTable
{
	public $TABLE = 'pages';
    public $SORT = 'sort ASC';
	public $IMG_SIZE = 100;
	public $IMG_RESIZE_TYPE = 1;
	public $IMG_BIG_SIZE = 250;
	public $IMG_BIG_VSIZE = 250;
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name';
    
    public $FIELD_UNDER = 'parent_id';
	public $NAME="Текстовые страницы";
	public $NAME2="текстовую страницу";
	
	public $MULTI_LANG = 1;
	public $USE_TAGS = 0;
	
	function __construct()
	{
		$this->fld=array(
			new Field("alias","Алиас",1),
			//new Field("route","route",1),
			new Field("nomenu", "Не показывать в меню", 6,  array('showInList'=>1)),
			new Field("name","Заголовок",1, array('multiLang'=>1)),
			new Field("mname","Заголовок в меню",1, array('multiLang'=>1)),
			new Field("title","title",1, array('multiLang'=>1)),
			new Field("description","description",1, array('multiLang'=>1)),
			new Field("h1","h1",1, array('multiLang'=>1)),
            new Field("text","Текст",2, array('multiLang'=>1)),
			new Field("parent_id","В разделе", 9, array(
				'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'pages', 'valsFromCategory'=>-1, 
				'valsEchoField'=>'name')),
			
			new Field("sort","SORT",4),
			new Field("creation_time","Date of creation",4),
			new Field("update_time","Date of update",4),
			
		);
        

	}
    
    function show_files($row) {
        if (!empty($row['id']))
            echo '<iframe width="800" height="500" style="border:1px solid #CCC" frameborder="0" src="sub_items.php?tabler=pages&amp;tablei=files&amp;id=0&amp;under='.$row['id'].'"></iframe>';
        else
            echo 'Файли можна додавати тільки у створені сторінки, додайте сторінку та відкрийте її для редагування<br/><br/>';
    }
    
    function afterAdd($row) {
        
        //saveAllCatRelations();
        
        if (empty($row['alias'])) {
			if (!empty($row['parent_id'])) {
				$rowu = FetchID('pages', $row['parent_id']);
				$parentAlias = $rowu['alias'] . '-';
			} else {
				$parentAlias = '';
			}
			
			
			$qup = "UPDATE pages SET alias = '" . Translit($parentAlias . $row['name_1'])."' WHERE id = " . $row['id'];
			pdoExec($qup);
		}
    }
    
    function afterEdit($row) {
        
        //saveAllCatRelations();
    }

	

};

?>
