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
        <title>Story details</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php


            require_once "../includes/db_connect.php";
            $storyid=0;
            $storynameErr=$storydescriptionErr=$categoryErr="";
            $storyname=$storydescription=$category=$tags="";
            $agerestriction=0;
            $update=false;
            $storypicture="defaultcover.jpg";
            if (isset($_GET['storyid'])){
                $storyid=$_GET['storyid'];

                $storyQuery="   SELECT  storyname, storydescription, category, tags, agerestriction, storypicture
                                FROM    story
                                WHERE   storyid=$storyid";
                $storyResult=$conn->query($storyQuery);
                $row=$storyResult->fetch();
                $storyname=$row['storyname'];
                $storydescription=$row['storydescription'];
                $category=$row['category'];
                $tags=$row['tags'];
                $agerestriction=$row['agerestriction'];
                $storypicture=$row['storypicture'];
                $update=true;
            }
            else{
                $activemenu="create";
                include('../includes/menu.php');
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if ($_POST['submit'] == 'cancel'){
                    header("Location: profile.php");
                }
                else{
                    if (empty($_POST['storyid'])){
                        $sQuery = " SELECT  MAX(storyid) AS storyid
                                    FROM    story";
                        $sResult=$conn->query($sQuery);
                        $sRows=$sResult->fetch();
                        $storyid=$sRows['storyid']+1;
                    }
                    else{
                        $storyid=$_POST['storyid'];
                    }

                    if (!empty($_POST['file-image'])){
                        $path=$_POST['file-image'];
                        $temp=explode('\\', $path);
                        $storypicture=array_pop($temp);
                    }else{
                        $storypicture="defaultcover.jpg";
                    }

                    if (empty($_POST['storyname'])){
                        $storynameErr="Story name is required";
                    }
                    else{
                        $storyname=$_POST['storyname'];
                    }
                    $datecreated=date("Y/m/d");
                    if (empty($_POST['category'])){
                        $categoryErr="Category is required";
                    }
                    else{
                        $category=$_POST['category'];
                    }
                    $tags=$_POST['tags'];
                    $language=$_POST['language'];
                    if (empty($_POST['description'])){
                        $storydescriptionErr="Description is required";
                    }
                    else{
                        $storydescription=$_POST['description'];
                    }
                    if (empty($_POST['agerestriction'])){
                        $agerestriction=0;
                    }
                    else{
                        $agerestriction=$_POST['agerestriction'];
                    }

                    if ($_POST['submit'] == 'next'){
                        if ($storynameErr=="" && $storydescriptionErr == "" && $categoryErr == ""){
                            $storyInsert="  INSERT INTO story(storyid, storyname, author, datecreated, category, tags, storydescription, agerestriction, storypicture)
                                            VALUES      ($storyid, ".$conn->quote($storyname).", '$user', '$datecreated', ".$conn->quote($category).", ".$conn->quote($tags).", ".$conn->quote($storydescription).", '$agerestriction', '$storypicture')";
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $storyResult=$conn->exec($storyInsert);

                            if ($storyResult){
                                header("Location: editor.php?storyid=$storyid");
                            }
                            else{
                                header("Location: storydetails.php?storyid=$storyid");
                            }
                        }
                    }
                    if ($_POST['submit'] == 'update'){
                        if ($storynameErr=="" && $storydescriptionErr == "" && $categoryErr == ""){
                            $storyid=$_POST['storyid'];
                            $storyUpdate="  UPDATE  story
                                            SET     storyname=".$conn->quote($storyname).", category=".$conn->quote($category).", tags=".$conn->quote($tags).", storydescription=".$conn->quote($storydescription).", agerestriction='$agerestriction', storypicture='$storypicture'
                                            WHERE   storyid=$storyid
                                            AND     author='$user'";
                            echo $storyUpdate;
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $updateResult=$conn->exec($storyUpdate);
                            if ($updateResult){
                                header("Location:profile.php");
                            }
                        }
                    }
                }


            }
        ?>
        <form id="msform" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" style="width:900px;">
            <fieldset>
            Cover picture <br/>
            <img src="../images/<?php echo $storypicture;?>" width="100px" height="150px">
            <input type="file" id="file-input" name="imagefile"
                onchange="document.getElementsByName('file-image')[0].value = document.getElementById('file-input').value;">
            <input type="hidden" name="file-image" value="<?php echo $storypicture;?>">
            <input type="hidden" name="storyid" id="storyid" value="<?php echo $storyid;?>">
            Story title <br/>
            <input type="text" id="storyname" name="storyname" value="<?php echo $storyname;?>" placeholder="Story title"/><br/><br/>
            <span class="fs-subtitle"><?php echo $storynameErr;?></span><br/><br/>
            Description: <br/>
            <textarea rows="7" cols="30" name="description" id="description" placeholder="Description"><?php echo $storydescription;?></textarea><br/><br/>
            <span class="fs-subtitle"><?php echo $storydescriptionErr;?></span><br/><br/>
            <?php
                $categoryQuery="    SELECT	COLUMN_TYPE AS category
                                    FROM 	information_schema.`COLUMNS` 
                                    WHERE 	TABLE_NAME = 'story' 
                                    AND 	COLUMN_NAME = 'category'";
                $categoryResult=$conn->query($categoryQuery);
                $category=$categoryResult->fetch();
                $category=$categoryResult->fetch();
                preg_match_all('/(["\'])([^"\']+)\1/', $category['category'], $matches );
            ?>
            Category <br/>
            <select name="category" id="category">
                <?php
                    foreach($matches[2] as $enum){
                        echo '<option value= "'.$enum.'">'.$enum.'</option>';
                    }
                ?>
            </select>
            <span class="fs-subtitle"><?php echo $categoryErr;?></span><br/><br/>
            Tags <br/>
            <input type="text" id="tags" name="tags" value="<?php echo $tags;?>" placeholder="Tags"/><br/><br/>
            Language <br/>
            <input type="text" id="language" name="language" placeholder="Language"/><br/><br/>
            Age Restriction <br/>
            <select name="agerestriction" id="agerestriction">
                <option value= "0" selected >None</option>
                <option value= "13" <?php if($agerestriction == "13") {echo "selected";} ?> >13</option>
                <option value= "15" <?php if($agerestriction == "15") {echo "selected";} ?> >15</option>
                <option value= "18" <?php if($agerestriction == "18") {echo "selected";} ?> >18</option>
            </select>
            <br/><br/><br/>
            <button name="submit" type="submit" value="cancel">Cancel</button>
            <?php
                if ($update){
                    echo '<button name="submit" type="submit" value="update">Update</button>';
                }
                else{
                    echo '<button name="submit" type="submit" value="next">Next</button>';
                }
            ?>
            </fieldset>
        </form>
    </body>
</html>
