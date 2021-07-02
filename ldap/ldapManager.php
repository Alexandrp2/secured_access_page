<?php

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
    $port="389";
    $ldap_identifiant = "cn=". $_SERVER['LDAP_AUTH_LOGIN'] .",dc=portail,dc=chatelet,dc=BEMS";
    $ldapVersionProtocole = 3;
    
    // Connexion au serveur LDAP
    $ldap_establish_connection = ldap_connect($_SERVER['LDAP_SERVER_URL']);

    /*
     * CONNECTION
     */
     if( !$ldap_establish_connection ) {
        header('Location:../index.php');
     }
 

    /*
     * AUTHENTIFICATION
     */

    ldap_set_option($ldap_establish_connection, LDAP_OPT_PROTOCOL_VERSION, $ldapVersionProtocole);
    $bind = @ldap_bind(
        $ldap_establish_connection,
        $_SERVER['LDAP_AUTH_LOGIN'],
        $_SERVER['LDAP_AUTH_PW']
    );

    if( !$bind ) {
        header('Location:../index.php');
    }

   /*
    * SEARCH
    */

    $requete = ldap_search(
        $ldap_establish_connection,
        'CN=Users,dc=portail,dc=chatelet,dc=BEMS',
        '(Name='.$ldap_username.')'
    );
    
    if( !$requete ) {
        header('Location:../index.php');    
    }

    /*
     * RESULTATS
     */

    $resultats = ldap_get_entries(
        $ldap_establish_connection, 
        $requete
    );

    // Cas : succès
	if( count($resultats) > 1 ) {
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