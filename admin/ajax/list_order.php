<?php
/*
 * Управление товарами в заказе
 * */
 
$orderId = (int)$_GET['orderId'];
$addArt = $_GET['addArt'];
$del = (int)$_GET['del'];

/*
if ($oid>0)
{
if (file_exists("connect.php")) include("connect.php"); else include("../includes/connect.php");

	if ($add>0)
	{
		$rowi=FetchID($TABLE_CATALOG,$add);
		$qi="INSERT INTO `$TABLE_ORDERS_ITEMS` (`id` ,`orderID`,`itemID`,`item_name`,`price`,`count`) VALUES (NULL , '$oid', '$add', '$rowi[name_1]', '$rowi[price]', '1')";
		mQuery($qi);
		//echo $qi.mError();
	}
	
	if ($del>0)
	{
		$qd="DELETE FROM $TABLE_ORDERS_ITEMS WHERE id=$del";
		mQuery($qd);
	}
	
echo "<strong style=\"font-size:15px;\">Товары в заказе №$oid</strong><br />
<br />";
$query="SELECT * FROM $TABLE_ORDERS_ITEMS WHERE orderID=$oid ORDER BY price asc";
$result = mQuery($query);
$num=mNumRows($result);
$sum=0;
echo '<table border="0">';
if ($num) for ($i=0; $i<$num;$i++)
	{
	$row=mFetchArray($result);
	$row[item_name]=stripslashes($row[item_name]);
	echo '<tr><td>'.imfile." <a href=\"items.php?tabler=catalog_rubs&tablei=catalog&id=".$row['itemID']."#header\" target=\"_blank\">$row[item_name]</a></td><input name=\"count".$row['id']."\" type=\"text\" id=\"count".$row['id']."\" value=\"".$row['count']."\" onkeyup=\"updateItemVal('".$TABLE_ORDERS_ITEMS."','count','count".$row['id']."','".$row['id']."');\" style=\"width:20px;\"/>шт</td><td><input name=\"price".$row['id']."\" type=\"text\" id=\"price".$row['id']."\" value=\"".$row['price']."\" onkeyup=\"updateItemVal('".$TABLE_ORDERS_ITEMS."','price','price".$row['id']."','".$row['id']."');\" style=\"width:55px;\"/>грн</td><td></td><td> [ <a  href=\"javascript:list_ord(0,".$row['id'].")\">".imdelm."</a> ]</td></tr>";
	$sum+=$row['price']*$row['count'];
	}
echo '</table><br />Добавить товар по ИД: <input name="addid" type="text" id="addid" style="width:50px;" />
<a href="javascript:list_ord(\'addid\',0)"><u>добавить</u></a><br /><br />
Сумма заказа: <strong style=\"font-size:15px;\">'.$sum.' грн</strong> / <a href="javascript:list_ord(0,0)"><u>пересчитать</u></a>';
	if (file_exists("disconnect.php")) include("disconnect.php"); else include("../includes/disconnect.php"); 
}*/
$order = new admin_orders();

if (!empty($addArt)) {
    $order->addProduct($orderId, $addArt);
    $order->reEvaluate($orderId);
}

if (!empty($del)) {
    $order->removeProduct($orderId, $del);
    $order->reEvaluate($orderId);
}

$orderInfo = $order->getOrder($orderId);
$products = $order->getProducts($orderId);
$insVal = FetchID('slovar', 9);
$install_base = $insVal['value_1'];
        
$total_install = 0;
?>
    <table class="table table-bordered">
<thead>
<tr>
				<th>Товар</th>
                                <th >Id</th>
                                <th >Кол-во</th>
				<th >Цена, за шт</th>
                                <th >Цена розница</th>
				<th >Всего</th>
				<th >Монтаж</th>
				<th >Действия</th>
			</tr>
                    </thead>
                    <tbody>
		<?php
		foreach ($products as $item) {
                        $total_install += $item['qty']*$item['installation'];
			?>
                        <tr valign="top">
                            <td width="280"><a href="<?=$item['url']?>" target="_blank"><?=$item['name']?></a></td>
                            <td><?=$item['id']?></td>
                            <td align="center">
                                <input name="count<?=$item['assoc_id']?>" type="text" id="count<?=$item['assoc_id']?>" value="<?=$item['qty']?>" onkeyup="updateItemVal('orders_items','count','count<?=$item['assoc_id']?>','<?=$item['assoc_id']?>');" style="width:30px;"/></td>
                            <td align="center"><?=$item['price']?></td>
                            <td align="center"><?=$item['price_r']?></td>
                            <td align="center"><?=($item['qty']*$item['price'])?></td>
                            <td align="center"><input type="hidden" value="<?=$item['prod_install']?>" id="pins_<?=$item['assoc_id']?>"/><input type="checkbox" <?=($item['installation']>0?'checked="checked"':'""')?>  id="ins_<?=$item['assoc_id']?>" onChange="setInstall(<?=$item['assoc_id']?>)" class="switcher" data-reverse></td>
                            
                            <td align="center">
                                <a onclick="listOrder(<?=$orderId?>)" class="btn btn-success" data-toggle="tooltip" title="" role="button" data-original-title="Удалить">
                                    <i class="glyphicon glyphicon-refresh"></i>
                                </a>
                                <a onclick="if (confirm('Удалить?')) listOrder(<?=$orderId?>,0,<?=$item['assoc_id']?>);" class="btn btn-danger" data-toggle="tooltip" title="" role="button" data-original-title="Удалить">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                <?php
		}
		?>
                        <tr>
                            <td><strong>Скидка, %</strong></td>
                                <td></td>
                                <td align="center"><input type="text" id="discount<?=$orderInfo['id']?>" value="<?=$orderInfo['discount']?>" onkeyup="updateItemVal('orders','discount','discount<?=$orderInfo['id']?>','<?=$orderInfo['id']?>');" style="width:30px;"/></td>
				<td ></td>
                                <td></td>
                                <td ></td>
                                <td ></td>
				<td align="center"><a onclick="listOrder(<?=$orderId?>)" class="btn btn-success" data-toggle="tooltip" title="" role="button" data-original-title="Удалить">
                                    <i class="glyphicon glyphicon-refresh"></i>
                                </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                </td>
			</tr>
                        <tr>
                            <td><strong>Итого, грн</strong></td>
                                <td></td>
                                <td></td>
				<td ></td>
                                <td></td>
                                <td align="center"><strong><?=$orderInfo['total']?></strong></td>
                                 <td align="center"><strong><?=($total_install>0?$install_base+$total_install:0)?></strong></td>
				<td></td>
			</tr>
                        <tr>
                            <td><strong>Добавить товар</strong></td>
                            <td colspan="6"><input type="text" id="addArt" placeholder="артикул" style="width:150px;"></td>
                            <td align="center">
                                <a onclick="listOrder(<?=$orderId?>,1)" class="btn btn-success" data-toggle="tooltip" title="" role="button" data-original-title="Удалить">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </a>
                                <a  onclick="$('#addArt').val('')" class="btn btn-danger" data-toggle="tooltip" title="" role="button" data-original-title="Очистить">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
		</table>
    
    

