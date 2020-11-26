<?php

use taskApp\mydb;
require_once "../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  header('Content-Type: application/json');
	
  $dbmodel= new mydb();
  $taskList = [];
	$data= [      
        "title" => $_POST['list'],
    ];
    $where = [
        "id" => $_POST['tid'],
    ];

      if ($dbmodel -> update("task", $data, $where)) {
        $results = $dbmodel->view('task', '*');

        if ($results->num_rows > 0) {
          while ($d= $results->fetch_assoc()) {
            $taskList[] = $d;
          }
        }
      }

      $dataList['tasks'] = $taskList;

      echo json_encode($dataList);

}
