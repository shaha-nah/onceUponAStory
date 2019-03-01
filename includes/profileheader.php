 <?php
    if (isset($_GET['action'])){
        if ($_GET['action'] == "follow"){
            $follow="   INSERT INTO follow(follower, following)
                        VALUES      ('$user', '$profile')";
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $followResult=$conn->exec($follow);

            if($followResult){
                header("Location: profile.php?profile=$profile");
            }
        }

        if ($_GET['action'] == "unfollow"){
            $unfollow=" DELETE
                        FROM    follow
                        WHERE   follower='$user'
                        AND     following='$profile'";
            $unfollowResult=$conn->query($unfollow);

            if ($unfollowResult){
                header("Location: profile.php?profile=$profile");
            }
        }

        if ($_GET['action'] == "mute") {
            $muteInsert="   INSERT INTO block(username, user, mute)
                            VALUES      ('$user', '$profile', 1)";
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $muteResult=$conn->exec($muteInsert);
            if ($muteResult){
                header("Location: profile.php?profile=".$profile);
            }
        }
    }

    if ($profile<>$user){
        $followQuery="  SELECT  follower,following
                        FROM    follow
                        WHERE   follower='$user'
                        AND     following='$profile'";
        $followResult=$conn->query($followQuery);
        $followCount=$followResult->rowCount();
    }

    $uQuery = " SELECT  username, name, gender, dob, profiledescription,location, profilepicture
                FROM    user
                WHERE   username='$profile'";

    $uResult = $conn->query($uQuery);

    $row = $uResult->fetch();

    echo '  <aside class="profile-card"">
                <header>
                    <img src="../images/'.$row['profilepicture'].'">
                    <h1>'.$row['name'].'</h1>
                    <p>'.$row['username'].'<br/>'.$row['profiledescription'].'</p>
                </header>
                <ul class="profile-menu">
                    <li>
                        <a href="profile.php?profile='.$profile.'">Stories</a>
                    </li>
                    <li>
                        <a href="reads.php?type=archive&profile='.$profile.'">Archive</a>
                    </li>
                    <li>
                        <a href="reads.php?type=currentreads&profile='.$profile.'">Reads</a>
                    </li>
                    <li>
                        <a href="reads.php?type=readinglist&profile='.$profile.'">Reading List</a>
                    </li>
                    <li>
                        <a href="user.php?type=follower&profile='.$profile.'">Followers</a>
                    </li>
                    <li>
                        <a href="user.php?type=following&profile='.$profile.'">Following</a>
                    </li>
                    <li style="float:right;">';
                        if($profile != $user){
                            echo '<a href="report.php?profile='.$profile.'">Report</a>';
                            if ($followCount == 0){
                                echo '<a href="profile.php?action=follow&profile='.$profile.'">Follow</a>';
                            }
                            else{
                                echo '<a href="profile.php?action=unfollow&profile='.$profile.'">Unfollow</a>';
                            }
                        }
    echo'           </li>
                </ul>
            </aside>
            <br/><br/><br/>
    ';
?>
