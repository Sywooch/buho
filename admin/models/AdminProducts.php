<?php
/*
 *  Товары
 * */
 
class admin_catalog_products extends AdminTable
{

	public $SORT='hide ASC, avail_text ASC';
	public $ECHO_ID='id';
	public $name="Каталог товаров";
	public $NAME2 = "товар";
    
	
	public $IMG_SIZE = 201;
	public $IMG_RESIZE_TYPE = 3;
	public $IMG_BIG_SIZE = 1025;
	public $IMG_BIG_VSIZE = 725;
	public $IMG_NUM = 4;
	public $IMG_FIELD='id';
	public $IMG_LIST_WIDTH = 100;
	public $FOLDER_IMAGES = 1;

    public $MULTI_LANG = 1;
        
    public $SHOW_NUM = 500;
    
    public $FIELD_UNDER="cat_id";
    
    public $TABLE_EXTRA_PARAMS = 'extra_params';
    public $TABLE_EXTRA_PARAMS_GROUPS = 'extra_params_groups';
    public $TABLE_EXTRA_PARAMS_ASSOC = 'extra_params_cat_assoc';
    public $TABLE_EXTRA_PARAMS_VALUES = 'extra_params_values';
    public $TABLE_EXTRA_PARAMS_RANGES = 'extra_params_ranges';
    public $USE_EXTRA_PARAMS_RANGES = 0;
    public $TABLE_EXTRA_PARAMS_PRODUCTS_ASSOC = 'extra_params_products_assoc';
    
    public $ECHO_NAME="name";
    
    public $TYPE_PARAM = "rubs_list_type";
    
    public $SP_WHERE_AND = "AND `base_id`='0'";
    
	function __construct()
	{

				$this->fld[] = new Field("name","Наименование",1, array('multiLang'=>1));
				$this->fld[] = new Field("articul","Артикул",1, array('multiLang'=>0, 'showInList'=>1));
				$this->fld[] = new Field("cat_id","Находится в разделе",9, array(
						'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'catalog_categories', 'valsFromCategory'=>-1,
						'valsEchoField'=>'name'));
				$this->fld[] = new Field("assoc","Assoc",4);
				
				$this->fld[] = new Field("alias","Наименование для УРЛ латиницей (сгенерируется автоматом)",1);		

				$this->fld[] = new Field("price","Стоимость розничная",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("rate","Курс",0);
				$this->fld[] = new Field("discount_formula","Формула скидки",1,array('showInList'=>1));
				$this->fld[] = new Field("discount_end_date","Дата окончания скидки",13);
                $this->fld[] = new Field("price_old","Стоимость старая",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("price_max","Стоимость максимальная",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("price_min","Стоимость минимальная",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("price_price","Стоимость прайсвая",0,array('showInList'=>0, 'editInList'=>0));
				$this->fld[] = new Field("price_input","Стоимость закупки",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("price_whole","Стоимость оптовая",0,array('showInList'=>0, 'editInList'=>0));
				$this->fld[] = new Field("amount_whole","Количество для опта",0,array('showInList'=>0, 'editInList'=>0));
				$this->fld[] = new Field("amount_pack","Количество в упаковке",0,array('showInList'=>0, 'editInList'=>0));
				$this->fld[] = new Field("k1","К1",0);
				$this->fld[] = new Field("k2","К2",0);
				$this->fld[] = new Field("installation","Монтаж",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("avail_text","Текст наличия (сортировка)",1);
                                $this->fld[] = new Field("available","Остатки, шт",0,array('showInList'=>1, 'editInList'=>0));
				$this->fld[] = new Field("ordered","Продано, шт",0);
				$this->fld[] = new Field("context","Контекст",6);
				$this->fld[] = new Field("context_brand","Контекст с брендом",6);
                                
                                $this->fld[] = new Field("hide","Скрыть",6, array('showInList'=>1, 'editInList'=>1));
				
				$this->fld[] = new Field("txt","Описание",2,array('multiLang'=>1));
				//$this->fld[] = new Field("files","Файлы",5);
				//$this->fld[] = new Field("mods","Модифікації",5);
				$this->fld[] = new Field("recom","Популярный (слайдер)",6,array('showInList'=>1, 'editInList'=>1));
				$this->fld[] = new Field("to_xml","Выгружать в XML",6,array('showInList'=>1, 'editInList'=>1));
				
				$this->fld[] = new Field("gift_articul","Подарок (артикул)",1);
				$this->fld[] = new Field("gift_end_date","Дата окончания подарка",13);
				//$this->fld[] = new Field("also_ids","Товары, которые рекомендуются покупать вместе с данным (ID товаров через запятую, пример: 545,567)",1);
				
				$this->fld[] = new Field("admin","Ответственный контенщик",1,array('showInList'=>0));
				$this->fld[] = new Field("creation_time","Date of creation",4);
				$this->fld[] = new Field("update_time","Date of update",4);
				
				$this->fld[] = new Field("flag","Флаг для экспорта",6, array('showInList'=>1, 'editInList'=>1));
				
				
				
				
				if ($_SESSION['admin']['group_id'] == 3) {
					 $this->TABLE_EXTRA_PARAMS = '';
					 $this->TABLE_EXTRA_PARAMS_GROUPS = '';
					 $this->TABLE_EXTRA_PARAMS_ASSOC = '';
					 $this->TABLE_EXTRA_PARAMS_VALUES = '';
					 $this->TABLE_EXTRA_PARAMS_RANGES = '';
				}
				if ($_SESSION['admin']['group_id'] == 4) {
					 $this->SP_WHERE_AND = " AND admin = '". $_SESSION['admin']['name'] . "'";
				}

		        if (isset($_GET['filterBrand']) && $_GET['filterBrand'] > 0) 
					$whBrand=' AND supplier_id='.$_GET['filterBrand'];
				else 
					$whBrand='';
					
				if (isset($_GET['extraParamFilter']) && is_array($_GET['extraParamFilter']) 
						&& implode(',', $_GET['extraParamFilter'])!='') {
					
					$whExtraParams = '';
					
					foreach ($_GET['extraParamFilter'] as $extraValue) {
						if (!empty($extraValue)) {
							$whExtraParams .= ' AND id IN (SELECT product_id FROM extra_params_cache 
					WHERE value_id = ' . $extraValue . ')';
						}

					}
					
					
				}
				else 
					$whExtraParams = '';
				
				$this->SP_WHERE_AND .= $whBrand.$whExtraParams;
        
	}
        function preTable() {
        global $tabler,$tablei,$under,$RUBS_LIST_TYPE;
        
        //Extra params Filter         
        $q = "SELECT * FROM extra_params_cat_assoc WHERE admin_filter = 1 GROUP by param_id";
        $params = pdoFetchAll($q);
            
        $filterParams = '';
        
        foreach ($params as $assocRow) {
            
            $param = $assocRow['param_id'];
            
            $q = "SELECT * FROM extra_params WHERE id = $param";
            $rowP = pdoFetchAll($q);
            
            $q = "SELECT * FROM extra_params_values WHERE  param_id = $param ORDER by sort ASC";
            $extraValues = pdoFetchAll($q);
        
            if (count($extraValues) > 0) {
                array_unshift($extraValues,array('id'=>'', 'name_1'=>'Все'));
                
                $filterParams .= ' ' . $rowP[0]['name_1'] . ' <select name="extraParamFilter['.$param.']">';
                
                foreach ($extraValues as $extraValue) {
                    $filterParams .= '<option value="'.$extraValue['id'].'" '.((isset($_GET['extraParamFilter'][$param]) && $_GET['extraParamFilter'][$param] == $extraValue['id'])?'selected':'').'>'.$extraValue['name_1'].'</option>';
                }
                $filterParams .= '</select>';
            }
        }

        
        
        // Brands list
        
        $q = "SELECT * FROM suppliers ORDER by name ASC";
        $brands = pdoFetchAll($q);
        
        if (count($brands) > 0) {
			
			array_unshift($brands,array('id'=>0, 'name'=>'Все'));
			
			$filterBrand = '<select name="filterBrand">';
			
			foreach ($brands as $brand) {
				$filterBrand .= '<option value="'.$brand['id'].'" '.(((isset($_GET['brand_id_corr']) && $_GET['brand_id_corr'] == $brand['id']) || (isset($_GET['filterBrand']) && $_GET['filterBrand'] == $brand['id']))?'selected':'').'>'.$brand['name'].'</option>';
			}
			$filterBrand .= '</select>';
			
			$filter = '
			<form method="get" name="filterForm" style="padding-top:5px;padding-bottom:1px;">
			<strong>Фильтр по группе брендов</strong> '.$filterBrand.' '.$filterParams.'

		<input name="tabler" type="hidden" id="tabler" value="'.$tabler.'">
	<input name="tablei" type="hidden" id="tablei" value="'.$tablei.'">
	<input name="under" type="hidden" id="under" value="'.$under.'"> 
	<input name="rubs_list_type" type="hidden" id="under" value="select"> 
	<input name="subfilter" type="submit" value="отфильтровать" /></form>';
			
			//var_dump($brands);
		}
		else 
			$filter = '';
        
      
        if ($RUBS_LIST_TYPE == 'tree' && $_GET['under'] ==- 1) {
            return '';
        } else {
            return $filter;
        }
    }
    
    
    function getProductOrderCount($productId) {
        $q = "SELECT SUM(`count`) as num FROM orders_items WHERE product_id = " . $productId;
        $res = pdoFetchAll($q);
        return $res[0]['num'];
    }
    
    function show_assoc($row){
        if (!empty($row['id'])) {
                return genAssocBlock(['name' => 'Дополнительные категории', 'id' => $row['id'], 'tableRubsAssoc' => 'catalog_products_categories_assoc'])
        .'<br/><br/>';
        } else {
               return; //'Сначала создайте товар';
        }
    }
        
    function getParamValueId($name, $paramId) {
        
        $q = "SELECT * FROM extra_params_values
        WHERE name_1 LIKE '" . $name . "' AND param_id = $paramId";
        $res =  mysql_query($q);
        $num = mysql_num_rows($res);
        
        if ($num > 0) {
            $row = mysql_fetch_assoc($res);
            return $row['id'];
        }
        else {
            $q = "INSERT INTO extra_params_values
            SET name_1 = '" . $name . "',
            value_float = '" . floatval($name) . "',
            param_id = '$paramId',
            range_id = 0,
            sort = 0";
            $res = mysql_query($q);
            if ($res == 1) {
                $id = mysql_insert_id();
                mysql_query("UPDATE extra_params_values SET sort = $id WHERE id = $id");
                return $id;
            }
            else {
                echo mysql_error();
                return 0;
            }
        }
    }
    
    // Cache
    static function cacheExtraParams($productId = 0) {
        
        if ($productId > 0) {
            $wh = "WHERE product_id = " . $productId;
            $q = "DELETE FROM `extra_params_cache` " . $wh;
        } else {
            $wh = "";
            $q = "TRUNCATE TABLE `extra_params_cache`";
        }
        pdoExec($q);
        
        $q = "INSERT IGNORE INTO `extra_params_cache` SELECT * FROM `extra_params_cache_view` " . $wh;
        pdoExec($q);
        
        $q = "INSERT IGNORE INTO `catalog_products` (`id`, `params`) "
			."SELECT `product_id`, GROUP_CONCAT(`params` ORDER BY `param_id` SEPARATOR '') FROM ( "
			."SELECT `product_id`, `param_id`, CONCAT(':', GROUP_CONCAT(CONCAT('-', `value_id`, '-') ORDER BY `value_id` ASC SEPARATOR ''), ':') AS `params` "
			."FROM `extra_params_cache` " . $wh . " "
			."GROUP BY `product_id`, `param_id` "
			."ORDER BY `param_id` ASC "
			.") t "
			."GROUP BY `product_id` "
			."ON DUPLICATE KEY UPDATE `params`=VALUES(`params`)";
        pdoExec($q);
    }
    
    function afterAdd($row) {
        
        if (empty($row['alias'])) {
			
			$qup = "UPDATE catalog_products SET alias = '" . Translit($row['name_1']) ."_". $row['id']."' WHERE id = " . $row['id'];
			pdoExec($qup);
		}
		
		$q = "INSERT INTO catalog_products_categories_assoc SET cat_id = " . $row['cat_id'] . ", product_id  = " . $row['id'];
		pdoExec($q);
                
        self::cacheExtraParams($row['id']);
		
    }
    
    function afterEdit($row) {
        
        if (empty($row['alias'])) {
			
			$qup = "UPDATE catalog_products SET alias = '" . Translit($row['name_1']) ."_". $row['id']."' WHERE id = " . $row['id'];
			pdoExec($qup);
        }
        
        $q = "INSERT IGNORE INTO catalog_products_categories_assoc SET cat_id = " . $row['cat_id'] . ", product_id  = " . $row['id'];
		pdoExec($q);
        
        self::cacheExtraParams($row['id']);
		
    }
    
    function printFiles($row) {
        if (!empty($row['id']))
            echo '<iframe width="800" height="500" style="border:1px solid #CCC" frameborder="0" src="sub_items.php?tabler=catalog_products&amp;tablei=files&amp;id=0&amp;under='.$row['id'].'"></iframe>';
        else
            echo 'Файли можна додавати тільки у створені сторінки, додайте сторінку та відкрийте її для редагування<br/><br/>';
    }
    function show_mods($row) {
        if (!empty($row['id']))
            echo '<iframe width="800" height="500" style="border:1px solid #CCC" frameborder="0" src="sub_items.php?tabler=catalog_products&amp;tablei=catalog_products&amp;id=0&amp;under='.$row['id'].'"></iframe>';
        else
            echo 'Файли можна додавати тільки у створені сторінки, додайте сторінку та відкрийте її для редагування<br/><br/>';
    }
    
    
    public function disableGen($fld, $row, $i){
        
        if (in_array($_SESSION['admin']['group_id'], array(1))) {
            return false;
        }
        
        if ($_SESSION['admin']['group_id'] == 3) {
     
            return true;
        }
        
        return false;
    }
    
}

// Связки
class admin_catalog_products_categories_assoc
{
    public $tableRubs = 'catalog_categories';
    public $tableItems = 'catalog_products';
    public $colUnder = 'cat_id';
    public $colRecord = 'product_id';
    
    
};

/*
 *  Категории
 * 
 * */
 
class admin_catalog_categories extends AdminTable
{
    public $IMG_NUM = 0;
    public $FIELD_UNDER = "parent_id";
    public $ECHO_NAME = 'name';
    public $ECHO_ID = 'id';
    public $EXTRA_PARAMS=1;
    public $TABLE_EXTRA_PARAMS = 'extra_params';
    public $TABLE_EXTRA_PARAMS_GROUPS = 'extra_params_groups';
    public $TABLE_EXTRA_PARAMS_ASSOC = 'extra_params_cat_assoc';
    public $MULTI_LANG = 1;
    public $SP_WHERE_AND = '';
	
    function __construct()
    {
        
        
        
        $this->fld[] = new Field("name","Название",1,array('multiLang'=>1));
        $this->fld[] = new Field("hide","Скрыть",6,['showInList'=>1]);
        $this->fld[] = new Field("name_one","Название ед.ч.", 1, array('multiLang'=>1));
        $this->fld[] = new Field("name_alt","Название для УРЛ латиницей (сгенерируется автоматом)",1,array('showInList'=>1));
        $this->fld[] = new Field("sort","Сортировка",4);
        $this->fld[] = new Field("no_del","Не удалять",6);
        $this->fld[] = new Field("parent_id","Находится в разделе",9,array(
            'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'catalog_categories', 'valsFromCategory'=>-1, 
            'valsEchoField'=>'name'));
        $this->fld[] = new Field("creation_time","Date of creation",4);
        $this->fld[] = new Field("update_time","Date of update",4);
        $this->fld[] = new Field("txt","Текст описания",2,array('multiLang'=>1));
        $this->fld[] = new Field("base_id","ИД базового товара, для подкатегорий модификаций товаров",0);
        $this->fld[] = new Field("also_ids","Товары, которые рекомендуются покупать вместе (ID товаров через запятую, пример: 545,567)",1);
    }
    function afterEdit($row) {
		if (empty($row['name_alt'])) {
			if (!empty($row['parent_id'])) {
				$rowu = FetchID('catalog_categories', $row['parent_id']);
				$parentAlias = $rowu['name_alt'] . '-';
			} else {
				$parentAlias = '';
			}
			
			
			$qup = "UPDATE catalog_categories SET name_alt = '" . Translit($parentAlias . $row['name_1'])."' WHERE id = " . $row['id'];
			pdoExec($qup);
		}
	}
	function afterAdd($row) {
        
        //saveAllCatRelations();
        
        if (empty($row['name_alt'])) {
			if (!empty($row['parent_id'])) {
				$rowu = FetchID('catalog_categories', $row['parent_id']);
				$parentAlias = $rowu['name_alt'] . '-';
			} else {
				$parentAlias = '';
			}
			
			
			$qup = "UPDATE catalog_categories SET name_alt = '" . Translit($parentAlias . $row['name_1'])."' WHERE id = " . $row['id'];
			pdoExec($qup);
		}
	}
	
	function printFiles($row) {
        if (!empty($row['id']))
            echo '<iframe width="800" height="500" style="border:1px solid #CCC" frameborder="0" src="sub_items.php?tabler=catalog_categories&amp;tablei=files&amp;id=0&amp;under='.$row['id'].'"></iframe>';
        else
            echo 'Файли можна додавати тільки у створені сторінки, додайте сторінку та відкрийте її для редагування<br/><br/>';
    }
    
    public function disableGen($fld, $row, $i){
        
        if (in_array($_SESSION['admin']['group_id'], array(1))) {
            return false;
        }
        
        return true;
    }
}

/*
 * Дополнитлеьные параметры материалов
 * */
 
class admin_extra_params_groups extends AdminTable
{
	var $fld;
	var $SORT='sort asc';
	var $SHOW_NUM=100;
	var $FIELD_UNDER="parent_id";
    var $ECHO_NAME = 'name_1';
    var $NAME = "Характеристики товаров";
    var $NAME2 = "группу характеристик";
    
	function __construct()
	{

			$this->fld[] = new Field("name_1","Название характеристики",1);
            $this->fld[] = new Field("parent_id","Находится в группе",9,array(
				'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'extra_params_groups', 'valsFromCategory'=>-1, 
				'valsEchoField'=>'name_1'));
			$this->fld[] = new Field("sort","Порядковый номер при cортировке",4);
	}
}

class admin_extra_params extends AdminTable
{
	var $fld;
	var $SORT='sort asc';
	var $SHOW_NUM = 100;
	var $FIELD_UNDER="group_id";
	var $ECHO_NAME = 'name_1';
	var $NAME = "Характеристики";
	var $NAME2 = "характеристику";
	var $PSEUDO_MULTI_LANG = 1;
    
	function __construct()
	{
		$this->fld[] = new Field("name_1","Название характеристики рус",1);
		$this->fld[] = new Field("name_2","Название характеристики укр",1,array('showInList'=>1));
		$this->fld[] = new Field("name_alt","Системное название (для импорта)",1,array('showInList'=>1, 'editInList'=>1));
		$this->fld[] = new Field("in_filter","Использовать в фильтре",6,array('showInList'=>1, 'editInList'=>1));
		$this->fld[] = new Field("is_float","Значения = цифры",6);
		$this->fld[] = new Field("group_id","Находится в группе",9,array(
			'showInList'=>0, 'editInList'=>0, 'valsFromTable'=>'extra_params_groups', 'valsFromCategory'=>-1,
			'valsEchoField'=>'name_1'));
		$this->fld[] = new Field("ranges","Диапазоны для группировки",5);
		$this->fld[] = new Field("sort","Порядковый номер при cортировке",4);
	}
    
    function showed_ranges($rowi)
    {	
		if (!empty($rowi['id'])) {
			return '<p><strong><a href="sub_items.php?tabler=extra_params&tablei=extra_params_values&id=0&under='.$rowi['id'].'" class="iframeLink" target="pvals"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Варианты значений</a></strong></p>
        <br/><br/><iframe src="sub_items.php?tabler=extra_params&tablei=extra_params_values&id=0&under='.$rowi['id'].'" width="800" height="650" frameborder="0" name="pvals"></iframe>';
		}
		else {
			return '';
		}
	}
    
	function addSpFields($row,$under)
	{
        echo "<script>
        $(document).ready(function(){
            $('.iframeLink').fancybox({
         'width' : '75%',
         'height' : '75%',
         'autoScale' : false,
         'transitionIn' : 'none',
         'transitionOut' : 'none',
         'type' : 'iframe'
     });
        });
        </script>";
        return 1;
	}
    
    function saveCache() {
        $time = date('U');
        $q = "TRUNCATE TABLE extra_params_cache";
        pdoExec($q);
        $q = "INSERT INTO extra_params_cache SELECT * FROM extra_params_cache_view";
        pdoExec($q);
        
        return date('U') - $time;
    }

	function afterAdd($row)
	{
		if (empty($row['name_alt']))
		{
			$qup = "UPDATE `extra_params` SET `name_alt` = '" . strtolower(Translit($row['name_1'], '_')) ."' WHERE `id`=" . $row['id'];
			pdoExec($qup);
		}
	}
	function afterEdit($row)
	{
		if (empty($row['name_alt']))
		{
			$qup = "UPDATE `extra_params` SET `name_alt` = '" . strtolower(Translit($row['name_1'], '_')) ."' WHERE `id`=" . $row['id'];
			pdoExec($qup);
		}
		
		self::saveCache();
	}
}
/*
 * Диапазоны дополнительных параметров
 * */
class admin_extra_params_ranges extends AdminTable
{
	var $fld;
	var $SORT='sort asc';
	var $ECHO_ID='id';
	var $SHOW_NUM=100;
    var $FIELD_UNDER="param_id";
    
	var $name="Характеристики товаров";
    var $NAME2 = "диапазон";
		
	function __construct()
	{
		$this->fld = array(
			new Field("name_1","Название",1),
            new Field("value_min","Значение min",0,1,0),
            new Field("value_max","Значение max",0,1,0),
			new Field("param_id","Относится к параметру №",3),
			new Field("sort","SORT",4)
		);
	}

	function addSpFields($row,$under)
	{
		return 1;
	}
    
    function afterAdd($row) {
        $this->afterEdit($row);
    }
    
    function afterEdit($row) {
        
        if (!empty($row['value_min']) && !empty($row['value_max'])) {
            
            $q = "UPDATE extra_params_values SET range_id = " . $row['id'] . " 
                WHERE param_id = " . $row['param_id'] . " 
                AND value_float >= " . $row['value_min'] . " AND value_float < " . $row['value_max'];
            //echo $q. ' -> ' . 
            mysql_query($q);
            
            echo mysql_query();
        }
        else{
            var_dump($row);
        }
    }
    
}

/*
 * Варианты значений дополнительных параметров
 * */
class admin_extra_params_values extends AdminTable
{
	var $SORT='param_id asc,name_1 asc';
	var $ECHO_ID='id';
	var $ECHO_NAME='name_1';
	var $SHOW_NUM=500;
    var $FIELD_UNDER="param_id";
    
    
	var $name="Характеристики товаров";
		
	function __construct()
	{
		$this->fld = array(
			new Field("name_1","Вариант значения рус",1),
			new Field("name_2","Вариант значения укр",1, array('showInList'=>1, 'editInList'=>1)),
           // new Field("value_float","Значение цифровое",3),
			new Field("param_id","Относится к параметру",9,array(
			'showInList'=>1, 'editInList'=>0, 'valsFromTable'=>'extra_params', 'valsFromCategory'=>NULL,
			'valsEchoField'=>'name_1'))

           // new Field("range_id","Относится к диапазону",3),
			//new Field("sort","SORT",4)
		);
		
		$this->SP_WHERE_AND = "AND name_1 NOT REGEXP '^[0-9]'";
	}

	function addSpFields($row,$under)
	{

        return 1;
	}
	
	function getValuesByParam($params) {
		
		$q = "SELECT * FROM extra_params_values 
				WHERE param_id = " . $params['paramId'] . " ORDER by value_float ASC, name_1 ASC";
		$res = pdoFetchAll($q);
		
		return $res;
	}
	
	function margeValues($params) {
		
		$ids = explode(',', $params['margeIds']);
		
		$margeIds = array();
		
		foreach ($ids as $id) {
			if ($id > 0 && $id != $params['targetId']) {
				$margeIds[] = $id;
			}
		}
		
		$q = "UPDATE extra_params_products_assoc SET
			value_id = " . $params['targetId'] . "
				WHERE param_id = " . $params['paramId'] . " AND value_id IN (" . implode(',', $margeIds) . ")";
		$res = pdoExec($q);
		
		$q = "DELETE FROM extra_params_values 
		WHERE id IN (" . implode(',', $margeIds) . ")";
		$res = pdoExec($q);
		
		return $res;
	}
}
/*
 * Валюты
 */
class admin_currences extends AdminTable
{
    public $TABLE = 'currences';
    public $SORT = 'id';
    public $NAME="Каталог валют";
    public $NAME2 = "валюту";
    public $IMG_NUM = 0;
    public $ECHO_NAME = 'name';
    public $ECHO_ID = 'id';
    function __construct()
	{
				$this->fld[] = new Field("name","Название",1);
				$this->fld[] = new Field("base","Базовая",6,array('showInList'=>1, 'editInList'=>0));
                                $this->fld[] = new Field("coef","Курс относительно базовой",0,array('showInList'=>1, 'editInList'=>0));
        }
}

/*
 * Поставщики
 */
class admin_suppliers extends AdminTable
{
    public $SORT = 'id';
    public $NAME="Поставщики";
    public $NAME2 = "поставщика";
    public $IMG_NUM = 0;
    public $ECHO_NAME = 'name';
    public $ECHO_ID = 'id';
    function __construct() {
				$this->fld[] = new Field("name","Название",1);
        }
}

// Скидки
class admin_catalog_discounts extends AdminTable
{
	var $fld;
	var $SORT='id desc';
	var $SHOW_NUM = 100;
        var $ECHO_NAME = 'cat_id';
        var $NAME = "Скидки";
        var $NAME2 = "скидку";
    
	function __construct()
	{
		$this->fld[] = new Field("cat_id","Категория",9,array(
			'showInList'=>0, 'editInList'=>1, 'valsFromTable'=>'catalog_categories', 'valsFromCategory'=>-1,
			'valsEchoField'=>'name'));
		$this->fld[] = new Field("supplier_id","Группа брендов",9,array(
			'showInList'=>1, 'editInList'=>0, 'valsFromTable'=>'suppliers', 'valsFromCategory'=>NULL,
			'valsEchoField'=>'name'));
		$this->fld[] = new Field("brand_id","Бренд",9,array(
			'showInList'=>1, 'editInList'=>0, 'valsFromTable'=>'extra_params_values', 'valsFromCategory'=>33,
			'valsEchoField'=>'name_1'));
		$this->fld[] = new Field("discount","Скидка",1, ['showInList'=>1]);
		$this->fld[] = new Field("apply","Применить скидку", 4, ['showInList'=>1]);
	}
	
	function showit_apply($row) {
		if (!empty($row['id']))
			return '<a class="btn btn-info" role="button" href="catalog.php?tabler=&tablei=catalog_discounts&srci=items.php&apply_discount=' . $row['id'] . '">Применить</a><br/>';
		else
			return 'Применить скидку можно только после сохранения!<br/><br/>';
	}
	
	function pre_Table() {
		if (!empty($_GET['apply_discount'])) {
			$this->apply($_GET['apply_discount']);
		}
	}
	
	
	function apply($id) {
		$row = FetchID('catalog_discounts', $id);
		
		//var_dump($row);
		
		$wh = array();
		$subRubsIds = array();
		
		if (!empty($row['cat_id'])) {
			
			$subRubsIds[] = $row['cat_id'];
			
			$subRubs = pdoFetchAll("SELECT id FROM catalog_categories WHERE parent_id = " . $row['cat_id']);
			
			foreach($subRubs as $subRub) {
				$subRubsIds[] = $subRub['id'];
			}
			
			$wh[] = "id IN (SELECT product_id FROM catalog_products_categories_assoc WHERE cat_id IN (".implode(',', $subRubsIds)."))";
		}
		
		if (!empty($row['supplier_id']) && $row['supplier_id'] > 0) {
			
			$wh[] = "supplier_id = " . $row['supplier_id'];
		}
		
		if (!empty($row['brand_id']) && $row['brand_id'] > 0) {
			
			$wh[] = "id IN (SELECT product_id FROM extra_params_products_assoc WHERE value_id = " . $row['brand_id'] . ")";
		}
		
		$qUp = "UPDATE catalog_products SET discount_formula = '".$row['discount']."' WHERE " . implode(" AND ", $wh);
		
		pdoExec($qUp);
		
		$nRows = pdoFetchAll("SELECT count(*) as num from catalog_products WHERE " . implode(" AND ", $wh));

		
		echo '<strong><span class="label label-success">'.$nRows[0]['num'].'</span> товаров обновлено!</strong><br/><br/>';
		
	}
}
