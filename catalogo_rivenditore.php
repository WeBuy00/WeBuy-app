<?php
    /*Just for your server-side code*/
    header('Content-Type: text/html; charset=ISO-8859-1');
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
      height: 100%;
    }
    
    .product-image {
      object-fit: cover;
      height: 30%;
    }
  </style>

  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
  


  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">WeBuy</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="catalogo_rivenditore.php">Catalogo</a>
        </li>
		<li class="nav-item">
          <a class="nav-link" href="aggiungi_prodotto.php">Aggiungi Prodotto</a>
        </li>
        
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </nav>


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

    <h2 class="mt-5">Lista prodotti aggiunti da te</h2>

    <div class="row">
      <?php

      require_once 'config.php';

      // Connessione al database
      $conn = new mysqli($host, $username, $password, $dbname);

      // Controllo della connessione
      if (!$conn) {
        die("Connessione al database fallita: " . mysqli_connect_error());
      }

      // Query per ottenere i prodotti dal database
      $query = "SELECT * FROM prodotti WHERE idProduttore =" . $_SESSION['user_id'];
      $result = mysqli_query($conn, $query);

      // Ciclo per visualizzare i prodotti
      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $nome = $row['nome'];
        $descrizione = $row['descrizione'];
        $prezzo = $row['prezzo'];
        $immagine = $row['immagine'];
		    //$dataScadenza = $row['dataScadenza'];
	
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
              <a href="prodotto_rivenditore.php?id=<?php echo $id; ?>" class="btn btn-primary btn-block">Più dettagli</a>
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
