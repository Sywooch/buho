<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
//DB
$DB_HOST = 'forcecrm.mysql.ukraine.com.ua';
$DB_NAME = 'forcecrm_t15';
$DB_USER = 'forcecrm_t15'; 
$DB_PASSWORD = 'e6f25lgc'; 

           
//Admin
$ALANG = 'ru';
$PROJECT_NAME = "Enerline";
$ADMIN_SESSION_AUTH = 1;

$LANGS = array('1'=>'Рус','2'=>'Укр',);

//Tables
$TABLE_ITEMS="catalog_products";

$TABLE_DOCS_RUBS="docs_rubs";
$TABLE_DOCS="docs";
$TABLE_NEWS_RUBS="news_rubs";
$TABLE_NEWS="news";

$TABLE_USERS_RUBS="utypes";
$TABLE_USERS="users";
$TABLE_MAIL="emails";
$TABLE_TAGS = "tags";

$TABLE_ADMINS_GROUPS="admins_groups";
$TABLE_ADMINS="admins";
$TABLE_ADMINS_MENU="admins_menu";
$TABLE_ADMINS_MENU_ASSOC="admins_menu_assoc";
$TABLE_ADMINS_LOG="admins_log";
