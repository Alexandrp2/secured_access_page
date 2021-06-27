<?php 
    require_once __DIR__.'/vendor/autoload.php';

    use OTPHP\TOTP;

    // Générer un One Time Password
    $otp = TOTP::create('VP73MCXZJWTRMJYLSVOGFH32BE4D65BFGSRMDBPUCYN5Y35YONJQ5DCGCH3LH6LF3E4M45EA2ATGOYTFZDW22F3OWV6POLNXE76H45I');
    $otp->setLabel('Clinique Le Chatelet');
    $qr_code_dimension = "300x300";
    $qr_code_url_to_encode = $otp->getProvisioningUri();
    $qr_code_url = 
        "https://chart.googleapis.com/chart?cht=qr&chs="
        .$qr_code_dimension
        ."&chl="
        .$qr_code_url_to_encode;
    
    // Afficher l'url du qrcode (var_dump) ou rediriger vers cet url (header)
    //var_dump($qr_code_url);
    //header('location: '.$qr_code_url);
    echo 'Le code généré pour une durée de 30 sec est : '
      . $otp->now();

?>

<!doctype html>
  <html lang="en">
  <head>
    <title>2AF Google</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <div class="col-12">
        <div class="text-center">
    
          <h1>Double authentification avec Google Authenticator</h1>
          <br/>
          
          <h2>Scannez le QR Code</h2>
          
          <img src="<?php echo $qr_code_url; ?>"/>
          <br/><br/>
          
          <!-- message d'erreur si le code est invalide -->
          <?php 
            if( !empty($_GET['verif']) ) {
              $codeVerificator_result = htmlspecialchars($_GET['verif']);
              
              if ( $codeVerificator_result == 'not_valid_code'){
                ?>
                <div class="alert alert-danger">Code non valide</div>
                <?php 
                
                } else {
                  ?>
                  <div class="alert alert-danger">Code non valide</div>
                  <?php 
                }
              }  
          ?>

          <!-- Formaulaire du code à 6 chiffres de Google Authentificator -->
          <h6 style="text-align:left;">Entrez le code à 6 chiffres fourni par Google Authentificator</h6>
          <form action="two_factor_authentication/codeVerificator.php" method="POST">
            <input type="text" name="code" class="form-control" placeholder="123456">
            <br/>
            <button type="submit" class="btn btn-success">Verifier</button>
          </form>

        </div>
      </div>
    </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>