<?php
/*Just for your server-side code*/
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
<head>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <title>Dettaglio Prodotto</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>

  <?php include 'menu.php';?>


  <div class="container">
    <h2 class="mt-5">Dettagli Prodotto</h2>

    

    <?php
    require_once 'config.php';

    // Connessione al database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Controllo della connessione
    if (!$conn) {
      die("Connessione al database fallita: " . mysqli_connect_error());
    }

    // Verifica se � stato passato l'ID del prodotto nella query string
    if (isset($_GET['id'])) {
      $idProdotto = $_GET['id'];

      $query = "SELECT p.*, SUM(o.kg) AS quantita_ordinata
          FROM prodotti p
          LEFT JOIN ordini o ON p.id = o.idProdotto
          WHERE p.id = $idProdotto
          GROUP BY p.id";

      $result = mysqli_query($conn, $query);


      // Query per ottenere le informazioni del prodotto dal database
      //$query = "SELECT * FROM prodotti WHERE id = $idProdotto";
      //$result = mysqli_query($conn, $query);

      // Controllo se il prodotto è stato trovato
      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nomeProdotto = $row['nome'];
        $descrizioneProdotto = $row['descrizione'];
        $prezzoProdotto = $row['prezzo'];
        $immagineProdotto = $row['immagine'];
        $idProduttore = $row['idProduttore'];
        $quantitaOrdinata = $row['quantita_ordinata'];
        $kgGiornalieri = $row['kgGiornalieri'];

        ?>

        <div class="row mt-4">
  <div class="col-md-4 mb-4">
    <img src="<?php echo $immagineProdotto; ?>" class="img-fluid" alt="Immagine Prodotto">
  </div>
    <div class="col-md-8">
      <h3><?php echo $nomeProdotto; ?></h3>
      <p><?php echo $descrizioneProdotto; ?></p>
      <p>Prezzo: <?php echo $prezzoProdotto; ?> €/kg</p>
      <p>Quantità ordinata da altri utenti:</p>
      <div class="progress mb-3">
    <div class="progress-bar" role="progressbar" style="width: <?php echo ($quantitaOrdinata / $kgGiornalieri) * 100; ?>%;" aria-valuenow="<?php echo $quantitaOrdinata; ?>" aria-valuemin="0" aria-valuemax="<?php echo $kgGiornalieri - $quantitaOrdinata; ?>">
      <?php echo number_format($quantitaOrdinata, 1) . "kg / " . $kgGiornalieri . "kg"?>
    </div>
  </div>

      <form action="aggiungi_al_carrello.php" method="POST">
        <div class="form-group">
          <label for="quantita">Quantità:</label>
          <div class="input-group">
            <input type="number" name="quantita" id="quantita" class="form-control" min="0.1" max="<?php echo $kgGiornalieri - $quantitaOrdinata; ?>" step="0.1" value="0.1" required>

            <div class="input-group-append">
              <span class="input-group-text">kg</span>
            </div>

          </div>
        </div>

        <div class="form-group">
          <input type="hidden" name="prodotto_id" value="<?php echo $idProdotto; ?>">
          <input type="hidden" name="prodotto_nome" value="<?php echo $nomeProdotto; ?>">
          <input type="hidden" name="prodotto_prezzo" value="<?php echo $prezzoProdotto; ?>">
	        <input type="hidden" name="id_produttore" value="<?php echo $idProduttore; ?>">
          <button type="submit" class="btn btn-primary">Aggiungi al carrello</button>
        </div>
      </form>

    </div>
  </div>

        <?php
      } else {
        echo '<p>Prodotto non trovato.</p>';
      }
    } else {
      echo '<p>Prodotto non specificato.</p>';
    }

    // Chiusura della connessione al database
    mysqli_close($conn);
    ?>
  </div>
</body>
</html>
