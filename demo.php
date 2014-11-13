<?php
require_once 'PhpReverseProxy.class.php';

$proxy=new PhpReverseProxy();
$proxy->port="80";
$proxy->host="www.xiumu.org";
//$proxy->ip="1.1.1.1";
$proxy->forward_path="";
$proxy->connect();
$proxy->output();
?>