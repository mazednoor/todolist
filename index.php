<?php

use taskApp\mydb;
require_once "vendor/autoload.php";
$dbmodel = new mydb()
?>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="<?php echo "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/' ?>" name="url">
  <title>Todo List App</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="">
          <div class="text-center">
            <h1 class="todos">todos</h1>
          </div>
          <div class="allSection">
            <div class="inputField">            
              <input type="text" placeholder="What needs to be done?" id="press_enter">
            </div>
            <div class="tableArea">
              <table id="task">
                <?php
                    $result= $dbmodel->view("task", "*");
                    $counter = 0;
                    $anyData = 0;
                    while ( $d= $result->fetch_object()) {
                      if($d->status){
                        echo "<tr class='data-remove done' id='dbc-{$d->id}'>";
                        echo "<td class='data-change check-box-show'><i class='far fa-check-circle'></i></td>";
                        echo "<td class='task-dbl strike-through'><span>{$d->title}</span></td>";
                        echo "<td class='button-close-right'><i class='far fa-times-circle'></i></td>";
                        echo "</tr>";
                        $taskStatus =$d->status;
                        
                      } else {
                        echo "<tr class='data-remove' id='dbc-{$d->id}'>";
                        echo "<td class='data-change check-box-hide'><i class='far fa-check-circle'></i></td>";
                        echo "<td class='task-dbl'><span>{$d->title}</span></td>";
                        echo "<td class='button-close-right'><i class='far fa-times-circle'></i></td>";
                        echo "</tr>";
                        $counter++;
                      }
                      $anyData++;
                    }
                ?>
              </table>
            </div>
            <div style="<?php echo $anyData>0 ?'display: block': 'display: none' ?>" id="menu">
              <nav class="navbar navbar-expand-lg navbar-light">
                <div class="collapse navbar-collapse" id="navbarNav">
                  <ul class="navbar-nav">
                    <li class="nav-item active left-item">
                      <a class="nav-link"><span id="num-items"><?php echo $counter ?></span> Items Left <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active middle-all">
                      <a class="nav-link all-data">All <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active middle-active">
                      <a class="nav-link active-data">Active <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                      <a class="nav-link complete-data">Completed <span class="sr-only">(current)</span></a>
                    </li>
                    
                      <li class="nav-item active right-item">
                      <?php
                      if(isset($taskStatus)){
                    ?>
                        <a class="nav-link del-complete">Clear Completed <span class="sr-only">(current)</span></a>
                        <?php
                      }
                    ?>
                      </li>
                    
                  </ul>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>