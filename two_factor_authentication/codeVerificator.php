<?php 
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once (__ROOT__.'/vendor/autoload.php');

    use OTPHP\TOTP;

    $otp = TOTP::create('VP73MCXZJWTRMJYLSVOGFH32BE4D65BFGSRMDBPUCYN5Y35YONJQ5DCGCH3LH6LF3E4M45EA2ATGOYTFZDW22F3OWV6POLNXE76H45I');

    // On verifie si le code existe et si il est valide
    if( !empty($_POST['code']) ){

        if( $otp->verify(htmlspecialchars($_POST['code'])) ) {
            header('Location: ../welcome.php');
            die();
        } else {
            header('Location: ../code_auth_page.php?verif=not_valid_code');
            die();
        }
    }else{
        header('Location: ../code_auth_page.php'); 
        die();
    }