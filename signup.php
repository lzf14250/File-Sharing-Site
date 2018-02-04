<!DOCTYPE html>
    <head>
        <meta charset='utf-8'>
        <title>sign up</title>
    </head>
    <body>
        searchresult=0;
        <?php
        $username=$_POST['username'];
        if($username=="")
        {
            //username is empty
            echo "<p><h1>Please enter a username</h1></p>";
            echo "<p>Click <a href=\"signup.html\">here</a> if not respond.</p>";
            header("refresh:1;url=signup.html");
        }
        else
        {
            //username is not empty
            $namefile=fopen("users.txt","r");
            while(!feof($namefile))
            {
                if($_POST['username']==fgets($namefile))
                {
                    $searchresult=1;
                }
            }
            fclose($namefile);
            if($searchresult==1)
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
                fwrite($namefile,$username);
                fclose($namefile);
                echo "<p>Successfully sign up!</p>";
                echo "<p>Click <a href=\"login.html\">here</a> if not respond.</p>";
            }
        }
        ?>
    </body>