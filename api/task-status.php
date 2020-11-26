<?php

use taskApp\mydb;
require_once "../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
  $dbmodel= new mydb();
	$data= [      
        "status" => $_POST['status'],
    ];
    $where = [
        "id" => $_POST['tstatus'],
    ];

    $dbmodel -> update("task", $data, $where);
      
    echo 1;

}
