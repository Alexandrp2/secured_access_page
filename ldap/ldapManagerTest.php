<?php

	/*
	 *  LDAP DE TEST UTILISANT FROMSYS.COM
	*/

	// Session
	session_start();
	$nb_tentatives_connexion = $_SESSION["connectionAttempt"];

    // Récupérer les infos du formulaire
	$ldap_username = $_POST["username"];
	$ldap_password = $_POST["password"];
	$isValidCaptcha = ( 
		isset($_POST["checkbox_captcha"]) == 1 
		&& $_POST["checkbox_captcha"] != null
		&& isset($_POST["question_result"]) == 1
		&& $_POST["question_result"] == 9 
	) ? true : false;
    
	// Configuration de la connexion au serveur LDAP
	$server_URL = "ldap.forumsys.com";
	$ldap_identifiant = "uid=".$ldap_username.",dc=example,dc=com";
	$ldapVersionProtocole = 3;
	
	// Connexion au serveur LDAP
	$ldap_establish_connection = ldap_connect($server_URL);
	ldap_set_option($ldap_establish_connection, LDAP_OPT_PROTOCOL_VERSION, $ldapVersionProtocole);
	$bind = @ldap_bind(
		$ldap_establish_connection,
		$ldap_identifiant,
		$ldap_password
	);

    // Demande d'authentification du user sur le serveur LDAP
	// Cas : succès
	if( $bind ) {
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