<!DOCTYPE html>
<head>
    <title>login.php</title>
    <meta charset="utf-8">
</head>
<body>
    <?php
    $searchresult=0;
    session_start();
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $_SESSION['username']=$_POST['username'];
        if(empty($_POST['username']))
        {
            //username is empty
            echo "Please input your username";
            session_destroy();
        }
        else
        {   //username is not empty
            $namefile=fopen("users.txt","r");
            while(!feof($namefile))
            {
                if($_POST['username']==str_replace(array("\n","\r"),"",fgets($namefile)))
                {
                    $GLOBALS['searchresult']=1;
                }
            }
            fclose($namefile);
            if($GLOBALS['searchresult'])
            {
                header("refresh:1;url=home.php");
                echo "you will be redirected in about 1 second, click <a href=\"home.php\">HERE</a> if not respond";
            }
            else{
                //username not found
                session_destroy();
                header("refresh:1;url=index.html");
                echo "username not found, please enter again. click <a href=\"login.html\">here</a> if not respond";
            }

        }
    }
    ?>
</body>

