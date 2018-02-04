<!DOCTYPE html>
    <head>
        <meta charset='utf-8'>
        <title>sign up</title>
    </head>
    <body>
        <?php
        $searchresult=0;
        if($_POST['username']=="")
        {
            //username is empty
            echo "<p><h1>Please enter a username</h1></p>";
            echo "<p>Click <a href=\"signup.html\">here</a> if not respond.</p>";
            header("refresh:1;url=signup.html");
        }
        else if(!preg_match('/^[\w_\-]+$/',$_POST['username']))
        {
            //invalid username signup
            echo "Invalid username!";
            header("refresh:1;url=signup.html");
        }
        else
        {
            //username is not empty and is valid
            $namefile=fopen("users.txt","r");
            while(!feof($namefile))
            {
                if($_POST['username']==fgets($namefile))
                {
                    $GLOBALS['searchresult']=1;
                }
            }
            fclose($namefile);
            if($GLOBALS['searchresult']==1)
            {
                //username has already existed
                echo "<h1>This username has already existed</h1>";
                echo "<p>Click <a href=\"signup.html\">here</a> if not respond.</p>";
                header("refresh:1;url=signup.html");
            }
            else
            {
                //sign up a new username
                $namefile=fopen("users.txt","a");
                fwrite($namefile,"\n");
                fwrite($namefile,$_POST['username']);
                fclose($namefile);
                header("refresh:1;url=index.html");
                echo "<p>Successfully sign up!</p>";
                echo "<p>Click <a href=\"index.html\">here</a> if not respond.</p>";
            }
        }
        ?>
    </body>