<?php 

header('Access-Control-Allow-Origin: *'); 
require_once("tokenValidation.php"); 

$pendinglist=new TokenValidation();

$pendinglist->cbhipending(); 

