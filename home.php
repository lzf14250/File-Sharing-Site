<?php
    function printFile($files,$auth){
        foreach($files as $value){
          $print_html = "<div class=\"file_list\">";
          $print_html .="<b>".htmlentities($value)."</b>";
          $print_html .="<form class=\"file_handle\" action=\"";
          $print_html .=htmlentities($_SERVER['PHP_SELF'])."\">";
          $print_html .="<input type=\"text\" value=\"$value\" name=\"filename\" style=\"display:none\">";
          $print_html .="<input type=\"text\" value=\"$auth\" name=\"auth\" style=\"display:none\">";
          $print_html .="<input type=\"submit\" value=\"preview\" name=\"preview\">";
          $print_html .="<input type=\"submit\" value=\"download\" name=\"download\">";
          $print_html .="<input type=\"submit\" value=\"delete\" name=\"delete\">";
          $print_html .="</form>";
          $print_html .="</div>";
          echo $print_html;
        }
    }
    function printNoFile($hint){
      $print_html = "<div class=\"no_file\">";
      $print_html .="<b>".htmlentities($hint)."</b>";
      $print_html .="</div>";
      echo $print_html;
    }
    function printUpload($user,$auth){
      $print_html = "<div class=\"file_list\">";
      $print_html .="<form class=\"upload\" id=\"".htmlentities($user)."_upload\" enctype=\"multipart/form-data\" ";
      $print_html .="method=\"POST\" action=\"".htmlentities($_SERVER['PHP_SELF'])."\">";
      $print_html .="<input type=\"file\" name=\"uploadfile\" id=\"".htmlentities($user);
      $print_html .="_file\" onchange=\"".htmlentities($user)."_upload.submit()\" style=\"display:none\">";
      $print_html .="<input type=\"text\" value=\"$auth\" name=\"auth\" style=\"display:none\">";
      $print_html .="<input type=\"text\" name=\"upload\" class=\"upload_button\" value=\"upload\" onclick=\"".htmlentities($user)."_file.click()\">";
      $print_html .="</form>";
      $print_html .="</div>";
      echo $print_html;
    }
    function deldir($dir) {
      if(file_exists($dir)){
        $dd=opendir($dir);
        while ($file=readdir($dd)) {
          if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
              unlink($fullpath);
            }else{
              deldir($fullpath);
            }
          }
        }
        closedir($dd);
        rmdir($dir);
      }
    }
    session_start();/*
    $user = $_SESSION['username'];
    //here using part of the code in cse330-php-wiki.
    //https://classes.engineering.wustl.edu/cse330/index.php?title=PHP#PHP_Language_Components
    if( !preg_match('/^[\w_\-]+$/', $username) ){
    	echo "Invalid username";
    	exit;
    }*/
    $user = "cjh";
    //-----------------------------
    if(isset($_GET['logout'])){
      session_destroy();
      header("Location: index.php");
      echo "gotcha logout";
      exit;
    }

    if(isset($_GET['close'])){
      //delete user
      $userlistFile = fopen("users.txt","r");
      $userlist = array();
      while(!feof($userlistFile)){
        $tmpLine = fgets($userlistFile);
        $tmpLine = str_replace(array("\r","\n"),"",$tmpLine);
        if($tmpLine!=$user&&$tmpLine!=""){
          $userlist[] = $tmpLine;
        }
      }
      fclose($userlistFile);
      $userlistFile = fopen("users.txt","w");
      foreach ($userlist as $value) {
        fwrite($userlistFile,"$value\r\n");
      }
      fclose($userlistFile);
      //delete files and dir
      $dir_path = getcwd().'/../../web_files/'.$user;
      if(file_exists($dir_path)){
        deldir($dir_path);
      }
      session_destroy();
      header("Location: login.html");
      exit;
    }

    if(isset($_GET['preview'])){

    }

    if(isset($_GET['download'])){

    }

    if(isset($_GET['delete'])){
      if($_GET["auth"]=="private"){
        $dir_path = getcwd().'/../../web_files/'.$user;
      }elseif($_GET["auth"]=="public"){
        $dir_path = getcwd().'/../../web_files/'."public";
      }
      $full_file = $dir_path."/".$_GET["filename"];
      if(file_exists($full_file)){
        unlink($full_file);
      }
    }
    if(isset($_POST["upload"])){
      //here using part of the code in cse330-php-wiki.
      //https://classes.engineering.wustl.edu/cse330/index.php?title=PHP#PHP_Language_Components
      $filename = basename($_FILES["uploadfile"]["name"]);
      if(!preg_match('/^[\w_\.\-]+$/', $filename)){
      	echo "Invalid filename";
      	exit;
      }
      if($_POST["auth"]=="private"){
        $full_upload_path = sprintf("/home/grp/web_files/%s/%s", $user, $filename);
      }else{
        $full_upload_path = sprintf("/home/grp/web_files/public/%s", $filename);
      }
      if(!move_uploaded_file($_FILES['uploadfile']['tmp_name'], $full_upload_path)){
      	echo "upload failed";
      	exit;
      }
    }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>File Sharing</title>
    <link rel="stylesheet" href="home.css" type="text/css">
  </head>
  <body>
    <h1>File Sharing</h1>
    <div class="user">
      <b>User:</b>
      &nbsp;&nbsp;
      <?php echo htmlentities($user);?>
      &nbsp;&nbsp;
      <form class="account" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
        <input type="submit" name="logout" value="logout">
        <?php
          if($user!="public"){
            echo "<input type=\"submit\" name=\"close\" value=\"close my account\">";
            echo "<label>\"close\" will delete your account and all the private files</label>";
          }
        ?>
      </form>
    </div>
    <hr />
    <div class="file">
      <div class="file_title">
        Private files:
      </div>
      <?php
          $file_path = getcwd().'/../../web_files';
          if($user!=="public"){
            $user_dir = $file_path."/".$user;
            if(file_exists($user_dir)){
              $fd = opendir($user_dir);
              $files = array();
              while(($fd_files = readdir($fd)) !== false){
                  if($fd_files != '.' && $fd_files != '..'&&!is_dir($user_dir."/".$fd_files)){
                    $files[] = $fd_files;
                  }
              }
              closedir($fd);
              if(sizeof($files)!=0){
                printFile($files,"private");
              }else{
                printNoFile("Sorry you've got no files in this directory");
              }
            }else{
              mkdir($user_dir);
            }
            printUpload($user,"private");
          }else{
            printNoFile("User 'public' does not have private files");
          }
      ?>
      <div class="file_title">
        Public files:
      </div>
      <?php
        $user_dir = $file_path."/public";
        if(file_exists($user_dir)){
          $fd = opendir($user_dir);
          $files = array();
          while(($fd_files = readdir($fd)) !== false){
              if($fd_files != '.' && $fd_files != '..'&&!is_dir($user_dir."/".$fd_files)){
                $files[] = $fd_files;
              }
          }
          closedir($fd);
          if(sizeof($files)!=0){
            printFile($files,"public");
          }else{
            printNoFile("Sorry there's no file in public directory");
          }
        }else {
          mkdir($user_dir);
        }
        printUpload("public","public");
      ?>
    </div>
  </body>
</html>
