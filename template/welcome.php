<?php
session_start();
if ( !isset($_SESSION['auth_step2']) || $_SESSION['auth_step2'] != 'success' ) {
    header('location:../index.php');;
}
?>

<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8" />
        <title>Portail Clinique Le Chatelet</title>
    
    </head>
    <body>

    <h1>Portail de la Clinique Le Chatelet</h1>

   
    <?php 
        if ( isset($_POST['username']) ) {
            echo 'Bienvenue ' . $_POST['username'];
        } else { 
            echo 'Bienvenue sur le portail de la clinique';
        }
    ?>
  
    </body>
</html>
