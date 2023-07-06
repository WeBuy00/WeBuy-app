<?php
  session_start();
  ?>

<!DOCTYPE html>
<html>
<head>
  <title>Catalogo Prodotti</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
  
  <?php include 'menu.php';?>

  <div class="container">
    <?php
    // Controllo se esiste un messaggio di successo
    if (isset($_SESSION['messaggio'])) {
      echo '<div class="alert alert-success mt-4">' . $_SESSION['messaggio'] . '</div>';

      // Rimozione del messaggio di successo dalla sessione
      unset($_SESSION['messaggio']);
    }
    ?>

    <h2 class="mt-5">Categorie Prodotti</h2>

    <div class="row">
      <div class="col-md-4 mt-4">
        <div class="card product-card">
          <a href="catalogo_produttori_carne.php">
            <img src="https://media.istockphoto.com/id/1212824120/it/foto/assortimento-di-carne-e-frutti-di-mare.jpg?s=612x612&w=0&k=20&c=13NKHUf2edGlQX9rC8SFu-nMXXDpTxhWIAC-j6A0yKU=" class="card-img-top product-image" alt="Immagine Carne">
            <div class="card-body">
              <h5 class="card-title">Carne</h5>
            </div>
          </a>
        </div>
      </div>

      <div class="col-md-4 mt-4">
        <div class="card product-card">
          <a href="catalogo_produttori_pesce.php">
            <img src="https://www.salepepe.it/files/2017/02/IN-PESCHERIA.jpg" class="card-img-top product-image" alt="Immagine Pesce">
            <div class="card-body">
              <h5 class="card-title">Pesce</h5>
            </div>
          </a>
        </div>
      </div>

      <div class="col-md-4 mt-4 mb-4">
        <div class="card product-card">
          <a href="catalogo_produttori_fev.php">
            <img src="https://media-assets.lacucinaitaliana.it/photos/61fb16daf9bff304ce3ec60f/3:2/w_1500,h_1000,c_limit/2021-anno-fao-frutta-e-verdura.jpg" class="card-img-top product-image" alt="Immagine Frutta e Verdura">
            <div class="card-body">
              <h5 class="card-title">Frutta e Verdura</h5>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<?php
// Chiusura della connessione al database
mysqli_close($conn);
?>
