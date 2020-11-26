<?php

use taskApp\mydb;
require_once "../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
  $dbmodel= new mydb();
  $taskId = $_POST['taskid'];

  foreach($taskId as $id){ 
    $dbmodel -> delete("task", array("id" => $id));
  }

  $results = $dbmodel->view('task', '*');
  $task = $results->num_rows;
  echo $task;
}
