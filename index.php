<!DOCTYPE html>
<head>
    <title>index.php</title>
    <meta charset="utf-8">
</head>
<body>
    <?php
    $searchresult=0;
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $username=$_POST['username'];
        if(empty($username))
        {
            echo "Please input your username";
        }
        else
        {   //username is not empty
            $namefile=fopen("users.txt","r");
            while(!feof($namefile))
            {
                if($_POST['username']==fgets($namefile))
                {
                    $searchresult=1;
                }
            }
            fclose($namefile);
            if($searchresult){
                header("refresh:1;url=home.php")
                echo "you will be redirected in about 1 second, click <a href="home.php">here</a> if not respond";
            }
            else{
                //username not found
                header("refresh:1;url=login.html")
                echo "username not found, please enter again. click <a href="login.html">here</a> if not respond";
                exit;
            }

        }
    }
    ?>
</body>