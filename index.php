<!DOCTYPE html>
<head>
    <title>index.php</title>
    <meta charset="utf-8">
</head>
<body>
    <?php
    $searchresult=0;
    session_start();
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $username=$_SESSION['username'];
        if(empty($username))
        {
            //username is empty
            echo "Please input your username";
            session_destroy('username');
        }
        else
        {   //username is not empty
            $namefile=fopen("users.txt","r");
            while(!feof($namefile))
            {
                if($username==fgets($namefile))
                {
                    $searchresult=1;
                }
            }
            fclose($namefile);
            if($searchresult)
            {
                header("refresh:1;url=home.php");
                echo "you will be redirected in about 1 second, click <a href=\"home.php\">HERE</a> if not respond";
            }
            else{
                //username not found
                session_destroy('username');
                header("refresh:1;url=login.html");
                echo "username not found, please enter again. click <a href=\"login.html\">here</a> if not respond";
            }

        }
    }
    ?>
</body>

