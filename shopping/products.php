<?php
session_start(); require_once __DIR__.'/../config/connection.php';
$term=trim($_GET['q']??'');
if($term!==''){header('Location: search.php?'.http_build_query(['q'=>$term,'category'=>'all']));exit;}
header('Location: men.php'); exit;
