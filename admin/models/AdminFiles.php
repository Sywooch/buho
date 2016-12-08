<?

class admin_files extends AdminTable
{
	public $SORT = 'files.id DESC';
	public $TABLE = 'files';
	public $ECHO_NAME = 'title_1';
    
   // public $FIELD_UNDER = 'parent_id';
	public $NAME = "Файли";
	public $NAME2 = "файл";
	public $FIELD_UNDER = 'record_id';
	//public $PSEUDO_MULTI_LANG = 1;
	//public $USE_TAGS = 1;
	
	function __construct()
	{
		$this->fld=array(
                        new Field("title_1","Підпис рус",1),
                        new Field("title_2","Підпис укр",1),
			new Field("filename","Назва файлу",4, array('showInList'=>1)),
			new Field("format","Файл для завантаження",4),
			new Field("table_name","Таблиця",3),
			new Field("record_id","ID from table",3),
			new Field("creation_time","Date of creation",4),
			//new Field("update_time","Date of update",4),
		);
        
        $this->SP_WHERE_AND = "AND table_name = '" . $_REQUEST['tabler']."'";

	}
    
    function showit_filename($row) {
        return '<a href="/userfiles/'.$row['format'].'/'.$row['filename'].'" target="_blank">' . $row['filename'] . '</a>';
    }
    
   function afterAdd($row) {
            
        if (empty($row['table_name'])) {
			$qup = "UPDATE " . $this->TABLE . " SET `table_name` = '" . $_POST['tabler'] ."' WHERE id = " . $row['id'];
			pdoExec($qup);
		}
    }

	

};

?>
