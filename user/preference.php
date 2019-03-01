<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: login.php");
    }
    else{
        $user=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title>Preference</title>
        <link rel="stylesheet" href="../css/style.css">

        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        <?php 
            $activemenu="";
            require_once "../includes/db_connect.php";  
            
            $storyQuery="   SELECT      DISTINCT category, storyid, storyname, storydescription, storypicture
                            FROM        story
                            ORDER BY    RAND()
                            LIMIT       30";
            $storyResult=$conn->query($storyQuery);

            $preference="";
            $categoryArray=array();
            foreach(explode("&", $_SERVER["QUERY_STRING"]) as $tempArray){
                $split_param = explode("=", $tempArray);
                if ($split_param[0] == "category"){
                    $categoryArray[] = urldecode($split_param[1]);
                }
            }
            $categoryArray=array_unique($categoryArray);

            $preference=implode("','", $categoryArray);
            $preference="'".$preference."'";
            if ($preference != ""){
                $preferenceUpdate=" UPDATE  user
                                    SET     preference=\"$preference\"
                                    WHERE   username='$user'";
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $preferenceExec=$conn->exec($preferenceUpdate);
                if ($preferenceExec){
                    header("Location: home.php?referer=preference");
                }
            }
        

        ?>
        <div class="container">
            <h2>Select at least five stories</h2>
            <form id="categoryChoice">
                <?php
                    while ($storyRow=$storyResult->fetch()){
                        echo'   <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                                    <label class="image-checkbox">
                                        <img  class="img-responsive" src="../images/'.$storyRow['storypicture'].'" />
                                        <div class="middle">    
                                            <div class="text">
                                                <h3>'.$storyRow['storyname'].'</h3>
                                            </div>
                                        </div>
                                        <input type="checkbox" name="category" class= "category" value="'.$storyRow['category'].'" />
                                        <i class="fa fa-check hidden"></i>
                                    </label>
                                </div>
                        ';
                    }
                
                ?>
                <input id="submit" type="submit" value="Next" style = "display:none;">
                 
            </form>
        </div>

        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
        <script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
        <script>
            $(".image-checkbox").each(function () {
                if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
                    $(this).addClass('image-checkbox-checked');
                }
                else {
                    $(this).removeClass('image-checkbox-checked');
                }
            });

            $(".image-checkbox").on("click", function (e) {
                $(this).toggleClass('image-checkbox-checked');
                var $checkbox = $(this).find('input[type="checkbox"]');
                $checkbox.prop("checked",!$checkbox.prop("checked"));
                if ($('.category:checked').length >= 5 ){
                    $("#submit").show();
                }
                e.preventDefault();
            });
        </script>
        
    </body>
</html>