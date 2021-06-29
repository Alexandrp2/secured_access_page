<?php
	// Session infos
	session_start();
	$nb_tentatives_connexion = $_SESSION["connectionAttempt"];

    // Récupérer les infos du formulaire
	$ldap_username = $_POST["username"];
	$ldap_password = $_POST["password"];
	//$robot_check_value = isset($_POST["checkbox_captcha"]) && $_POST["checkbox_captcha"] ? $_POST["checkbox_captcha"] : 0;
	//$question_result = isset($_POST["question_result"]) ? $_POST["question_result"] : 0;
	$isValidCaptcha = ( 
		isset($_POST["checkbox_captcha"]) == 1 
		&& $_POST["checkbox_captcha"] != null
		&& isset($_POST["question_result"]) == 1
		&& $_POST["question_result"] == 9 
	) ? true : false;
    

	// Connexion au serveur LDAP
	$ldap_dn = "uid=".$ldap_username.",dc=example,dc=com";
	$ldap_con = ldap_connect("ldap.forumsys.com");
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

    // Demande d'authentification du user sur le serveur LDAP
	// Cas : succès
	if( @ldap_bind($ldap_con,$ldap_dn,$ldap_password) ) {
		if ( $nb_tentatives_connexion == 5 && !$isValidCaptcha ) {
			header('Location:../index.php');
		} else {
			$_SESSION["auth_step1"] = 'success';
        	header('Location:../template/code_auth_page.php');
		}
	// Cas : échec		
	} else {
		// Compteur de tentatives de connexions infructueuses
		if ( $nb_tentatives_connexion == 5 && $isValidCaptcha ) {
			// Reset
			$_SESSION["connectionAttempt"] = 1;
		} else if ( $nb_tentatives_connexion == 5 && !$isValidCaptcha )  {
			// Do nothing
		} else {
			// Incrémenter
			$_SESSION["connectionAttempt"] += 1;
		}
		header('Location:../index.php');
	}
?>