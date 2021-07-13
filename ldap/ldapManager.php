<?php

    // Session
	session_start();
	$nb_tentatives_connexion = $_SESSION["connectionAttempt"];

    // Récupérer les infos du formulaire
	$ldap_username = htmlspecialchars($_POST["username"]);
	$ldap_password = htmlspecialchars($_POST["password"]);
    $is_password_set = strlen($ldap_password) > 0;
	$isValidCaptcha = (
		isset($_POST["checkbox_captcha"]) == 1
		&& $_POST["checkbox_captcha"] != null
		&& isset($_POST["question_result"]) == 1
		&& htmlspecialchars($_POST["question_result"]) == 9
	) ? true : false;


    // Check identifiants
    if ( !$is_password_set ){
        header('Location:../index.php?status=403');
    }



    /**
     * CHECK IF PWD HAS BEEN PAWNED
     */

    // Hash sha1 du password utilisateur
    $pwd_hash = sha1($ldap_password);

    // Overture du fichier HaveIBeenPwnd
    $ressource = fopen($_SERVER['PATH_DB_PWNED'], 'r');

    // Lit la ligne et extrait le hash sha1
    $ligne = fgets($ressource);
    $ligne_hash = explode(":", $ligne)[0];
    //echo 'Ligne : ' . $ligne . '<br>';
    //echo ' hash = ' . $ligne_hash . '<br>';

    // find
    $compare_hash = strcasecmp($pwd_hash, $ligne_hash[0]);
    $is_pwd_in_pwnd_list = 0;

    // Boucle sur tous les mots de passe du fichier
    //jusqu'à trouver le même hash que le password de l'utilisateur
    while ( $compare_hash != 0 ) {

        $ligne = fgets($ressource);

        // false = fin du fichier
        if ($ligne == false) {
            break;
        }

        $ligne_hash = explode(":", $ligne)[0];
        //echo $pwd_hash . ' comparé à  ' . $ligne_hash . '<br>';

        $compare_hash = strcasecmp($pwd_hash, $ligne_hash);
        //echo "  -> Résultat de la comparaison = " . $compare_hash . '<br>';

        // Mot de passe trouvé dans la liste
        if( $compare_hash == 0){
            $is_pwd_in_pwnd_list = 1;
            break;
        }

    }

    //echo "Le mot de passse a été pawned ? " . $is_pwd_in_pwnd_list . '<br><br>';
    fclose($ressource);


    /*
     * CONFIGURATION DE LA CONNEXION AU SERVEUR LDAP
     */

    $port="389";
    //$ldap_identifiant = "cn=". $_SERVER['LDAP_AUTH_LOGIN'] .",".$_SERVER['LDAP_AUTH_DC'];
    $ldap_identifiant = "cn=PORTAIL\\". $ldap_username .",". $_SERVER['LDAP_AUTH_DC'];
    $ldapVersionProtocole = 3;

    /*
     * CONNEXION
     */

    // Connexion au serveur LDAP
    $ldap_establish_connection = ldap_connect($_SERVER['LDAP_SERVER_URL']);

    if( !$ldap_establish_connection ) {
        header('Location:../index.php?status=403');
    }

    /*
     * AUTHENTIFICATION
     */

    ldap_set_option($ldap_establish_connection, LDAP_OPT_PROTOCOL_VERSION, $ldapVersionProtocole);
    $bind = @ldap_bind(
        $ldap_establish_connection,
        "PORTAIL\\".$ldap_username,
        $ldap_password
        //$_SERVER['LDAP_AUTH_LOGIN'],
        //$_SERVER['LDAP_AUTH_PW']
    );

    if( !$bind ) {
        header('Location:../index.php?status=403');

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

    // Si password pawned
    if( $is_pwd_in_pwnd_list == 1 ) {
        header('Location:../index.php?status=400');
    }

?>
