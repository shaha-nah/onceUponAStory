<?php
    if (isset($_POST['search'])){

        $input=$_POST['search'];
        header("Location: searchresults.php?input=$input");
    }
?>
<div class="navbar navbar-custom navbar-fixed-top">
    <ul class="sidenav">
        <li class="menuli"><a href="home.php" <?php if ($activemenu=="home"){echo "class=\"activemenu\"";} ?> style="font-size: 20px;font-weight: 500;">ONCE UPON A STORY</a></li>
        <li class="dropdown">
            <a href="javascript:void(0)"  <?php if ($activemenu=="discover"){echo "class=\"activemenu\"";} ?>class="dropbtn">Discover</a>
            <div class="dropdown-content">
                <a href="discover.php">All categories</a>
                <?php
                    require_once "db_connect.php";
                    $catQuery = "   SELECT  DISTINCT    category
                                    FROM                story
                                    ORDER BY            category";
                    $catResult=$conn->query($catQuery);
                    while($row=$catResult->fetch()){
                        echo '<a href="discover.php?category='.$row['category'].'">'.$row['category'].'</a>';
                    }
                ?>
            </div>
        </li>
        <li class="menuli"><a href="storydetails.php" <?php if ($activemenu=="create"){echo "class=\"activemenu\"";} ?>>Create</a></li>
        <div class="search">
            <form method="post">
                <input type="text" id="search" name="search" placeholder="Search">
                <!-- <input type="submit" value=">>"> -->
                <!-- <input type="submit" class="btn fa-input" value=&#xf002;> -->
                <input type="submit" class="btn fa-input" value="&#xf002">
            </form>
        </div>
        <li class="rdropdown">
            <a href="javascript:void(0)" class="dropbtn">Account</a>
            <div class="rdropdown-content">
                <a href="profile.php">Profile</a>
                <a href="reads.php?type=currentreads">Reads</a>
                <a href="settings.php">Settings</a>
                <a href="logout.php">Logout</a>
            </div>
        </li>
    </ul>
</div>
