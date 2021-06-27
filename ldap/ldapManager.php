<?php

    // Récupérer les infos du formulaire
	$ldap_dn = "uid=".$_POST["username"].",dc=example,dc=com";
	$ldap_password = $_POST["password"];
	
    // Connexion au serveur LDAP
	$ldap_con = ldap_connect("ldap.forumsys.com");
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

    // Demande d'authentification du user sur le serveur LDAP
	if(@ldap_bind($ldap_con,$ldap_dn,$ldap_password))
		//echo "Authenticated";
        header('Location:../code_auth_page.php');
	else
        header('Location:../index.php');
?>