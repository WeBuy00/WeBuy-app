<?php
    /*Just for your server-side code*/
    header('Content-Type: text/html; charset=utf-8');
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
  

  <style>
    .product-card {
      height: 90%;
    }
    
    .product-image {
      object-fit: cover;
      height: 40%;
    }
  </style>

  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
  


  <?php include 'menu.php';?>


  <div class="container">

    <?php
    session_start();
    // Controllo se esiste un messaggio di successo
    if (isset($_SESSION['messaggio'])) {
      echo '<div class="alert alert-success mt-4">' . $_SESSION['messaggio'] . '</div>';

      // Rimozione del messaggio di successo dalla sessione
      unset($_SESSION['messaggio']);
    }
    ?>

    <h2 class="mt-5">Catalogo Prodotti</h2>

    <div class="row">
      <?php
      unset($_SESSION['messaggio']);

      require_once 'config.php';

      // Connessione al database
      $conn = new mysqli($host, $username, $password, $dbname);

      // Controllo della connessione
      if (!$conn) {
        die("Connessione al database fallita: " . mysqli_connect_error());
      }
      $idProduttore = $_GET['idProduttore'];
      // Query per ottenere i prodotti dal database
      $query = "SELECT * FROM prodotti
                WHERE idProduttore = $idProduttore";
      $result = mysqli_query($conn, $query);

      // Ciclo per visualizzare i prodotti <button type="submit" class="btn btn-primary">Aggiungi al carrello</button>
      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $nome = $row['nome'];
        $descrizione = $row['descrizione'];
        $prezzo = $row['prezzo'];
        $immagine = $row['immagine'];
		$dataScadenza = $row['dataScadenza'];
	
        if(strlen($descrizione) > 150) {
          $descrizione = substr($descrizione, 0, strpos(wordwrap($descrizione, 150), "\n"));
        }

        ?>

        <div class="col-md-4 mt-4">
          <div class="card product-card">
            <img src="<?php echo $immagine; ?>" class="card-img-top product-image" alt="Immagine Prodotto">
            <div class="card-body">
              <h5 class="card-title"><?php echo $nome?></h5>
              <p class="card-text"><?php echo $descrizione; ?>...</p>
              <p class="card-text">Prezzo: <?php echo $prezzo; ?> €/Kg</p>
              <a href="prodotto.php?id=<?php echo $id; ?>" class="btn btn-primary btn-block">Piu dettagli</a>
              <form action="aggiungi_al_carrello.php" method="POST" class="d-inline-block">
                <input type="hidden" name="prodotto_id" value="<?php echo $id; ?>">
                <input type="hidden" name="prodotto_nome" value="<?php echo $nome; ?>">
                <input type="hidden" name="prodotto_prezzo" value="<?php echo $prezzo; ?>">
                
              </form>
            </div>
          </div>
        </div>

        <?php
      }

      // Chiusura della connessione al database
      mysqli_close($conn);
      ?>
    </div>
  </div>
</body>
</html>
