<?php
session_start();
?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <title>Portail Clinique</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
    <div class="container">
        <br/>
        <h2>Veuillez saisir vos codes d'accès au serveur</h2>
        <br/>
        <form action="ldap/ldapManager.php" method="post" class="form-group">
            <div>
                <label for="username" class="col-2">Identifiant</label>
                <input type="text" name="username">
            </div>
            <div>
                <label for="password" class="col-2">Mot de passe</label>
                <input type="password" name="password">
            </div>
            <br/>

            <?php 
            if ( isset($_SESSION["connectionAttempt"]) && $_SESSION["connectionAttempt"] == 5 ) {
                ?>
                <div class="form-check">
                    <input class="form-check-input" name="checkbox_captcha" type="checkbox" id="checkbox_captcha">
                    <label class="form-check-label" for="checkbox_captcha">
                        [CAPTCHA] Je ne suis pas un robot
                    </label>
                </div>
                <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3">Combien font 2 + 7 ? </span>
                </div>
                <input type="text" name="question_result" class="form-control col-2" id="question_result" placeholder="18">
                </div>
                <br/>
                <?php
            }
            ?>

            <div>
                <input type="submit" value="Se connecter" class="btn btn-success">
            </div>
        </form>

        <div>tester les routes sans avoir passer les étapes d'authentification :</div>
        <div>http://cliinique-le-chatelet-access-page/template/code_auth_page.php</div>
        <div>http://cliinique-le-chatelet-access-page/template/welcome.php</div>
        <br/>

        <?php 
            if (isset($_SESSION["connectionAttempt"])) 
                echo 'nombre de tentatives de connexion => ' . $_SESSION["connectionAttempt"];
        ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>
