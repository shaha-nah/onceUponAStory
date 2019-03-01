<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: alogin.php");
    }
    else{
        $admin=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title>Reported Reviews</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
      	<link href="../css/font-awesome.min.css" rel="stylesheet">
      	<link href="../css/datepicker3.css" rel="stylesheet">
      	<link href="../css/styles.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
          table{
            table-layout: fixed;

          }
          tr{
            border-bottom: 1px solid rgb(220,220,220) ;
          }
          td{
            padding-top: 5px;
            padding-bottom: 5px;
            vertical-align: top;
            border-bottom: 1px solid rgb(220,220,220) ;
          }
          td.u{
            width: 12%;
          }
          td.rr{
            padding-left: 10px;
            width: 76%;
          }
          td.rb{
            padding-left: 10px;
            width: 12%;
          }
          #usr tr:hover {background-color: #ddd;}

        </style>
    </head>

    <body>

        <?php
            $activemenu = "review";
            require_once "../includes/db_connect.php";
            include('../includes/amenu.php');

        ?>

        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
      		<div class="row">
      			<ol class="breadcrumb">
      				<li><a href="#">
      					<em class="fa fa-comments-o"></em>
      				</a></li>
      				<li class="active">Reported Comments</li>
      			</ol>
      		</div>
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">Reported Comments</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-container">
                <?php
                    include('../includes/amenu.php');
                    require_once "../includes/db_connect.php";
                    if (isset($_GET['action'])){
                      $action=$_GET['action'];
                      if (isset($_GET['profile'])){
                        $profile=$_GET['profile'];
                        if ($action == "ban"){
                          $aQuery = " UPDATE  user
                                      SET     ban = 1
                                      WHERE   user.username = '$profile'; ";
                          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                          $updateResult=$conn->exec($aQuery);
                        }
                      }
                    }
                    //Display list of flagged Users
                    $uQuery =  "  SELECT  user, reportreason, username
                                  FROM    block
                                  WHERE   report = 1
                                  LIMIT   10";
                    $uResult = $conn->query($uQuery);
                    echo '<div class="panel-body">';
                      echo '<table id= "usr"><tr><th>Reported user</th><th style = "padding-left: 10px;">Report Reason</th><th style = "padding-left: 10px;">Reported by</th></tr>';
                          while ($row = $uResult->fetch()){
                            echo '<tr>';
                              echo '<td class = "u"><a href="aviewuser.php?profile=' .$row['user'].'"target=_blank >'.$row['user'].'</a> </td>';
                              echo '<td class = "rr">' . $row['reportreason'] . '</td>';
                              echo '<td class = "rb"><a href="aviewuser.php?profile=' .$row['username'].'"target=_blank >'.$row['username'].'</a> </td>';
                            echo '</tr>';
                          }//end while
                      echo '</table>';
                    echo '</div>'; //panel-body
                  ?>
                </div>  <!-- panel panel-container -->
              </div>  <!-- col md 12 -->
          </div>    <!-- row -->
        </div>


        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
