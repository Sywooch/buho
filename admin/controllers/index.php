<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $PROJECT_NAME.': '.$word[$ALANG]['adminpanel']; ?></title>
<?php
$App->insertHead();
?>
</head>

<body>
<?php
require_once("controllers/menu.php");
?>
<br />
<br />
<div class="container">
         <h2><span class="glyphicon glyphicon-map-marker" style="color:#1E90FF" aria-hidden="true"></span>
        <?php 
        echo $word[$ALANG]['hello']; ?>, <strong><?=$row_user['name'];?></strong>!</h2>
        
        <br />

        <?php if (in_array($_SESSION['admin']['group_id'],[1,5])) { ?>
			<h1>
			  <span class="glyphicon glyphicon-stats" style="color:#1E90FF" aria-hidden="true"></span> 
			Новые заказы:</h1>
			<?php
			$O = new admin_orders();
			
			$O->showOrdersWidget("status_id = 3 ORDER by id DESC");
		}
        ?>
</div>

<?php
include("inc/Bottom.php");
?>
</body>
</html>
