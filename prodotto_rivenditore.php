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

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">WeBuy</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="catalogo_rivenditore.php">Catalogo Rivenditore</a>
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
    <h2 class="mt-5">Dettaglio Prodotto</h2>

    <?php
    session_start();
    // Controllo se esiste un messaggio di successo
    if (isset($_SESSION['messaggio'])) {
      echo '<div class="alert alert-success mt-4">' . $_SESSION['messaggio'] . '</div>';

      // Rimozione del messaggio di successo dalla sessione
      unset($_SESSION['messaggio']);
    }
    ?>

    <?php
    require_once 'config.php';

    // Connessione al database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Controllo della connessione
    if (!$conn) {
      die("Connessione al database fallita: " . mysqli_connect_error());
    }

    // Verifica se e stato passato l'ID del prodotto nella query string
    if (isset($_GET['id'])) {
      $idProdotto = $_GET['id'];

      // Query per ottenere le informazioni del prodotto dal database
      $query = "SELECT p.*, SUM(o.kg) AS quantita_ordinata
          FROM prodotti p
          LEFT JOIN ordini o ON p.id = o.idProdotto
          WHERE p.id = $idProdotto
          GROUP BY p.id";

      $result = mysqli_query($conn, $query);

      // Controllo se il prodotto � stato trovato
      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nomeProdotto = $row['nome'];
        $descrizioneProdotto = $row['descrizione'];
        $prezzoProdotto = $row['prezzo'];
        $immagineProdotto = $row['immagine'];
	      $idProduttore = $row['idProduttore'];
        $quantitaOrdinata = $row['quantita_ordinata'];
        $kgGiornalieri = $row['kgGiornalieri'];
        $hub = $row['hub'];
        $cella = $row['cella'];
        ?>


<?php


// Connessione al database
$conn = new mysqli($host, $username, $password, $dbname);

// Controllo della connessione
if (!$conn) {
  die("Connessione al database fallita: " . mysqli_connect_error());
}

// Funzione per eliminare il prodotto dal database
function eliminaProdotto($idProdotto, $conn) {
  // Query per eliminare il prodotto dal database
  $query = "DELETE FROM prodotti WHERE id = $idProdotto";

  if (mysqli_query($conn, $query)) {
    // Prodotto eliminato con successo
    $_SESSION['messaggio'] = "Prodotto eliminato con successo.";
    header("Location: catalogo_rivenditore.php");
    exit();
  } else {
    // Errore durante l'eliminazione del prodotto
    $_SESSION['messaggio'] = "Siè verificato un errore durante l'eliminazione del prodotto: " . mysqli_error($conn);
    header("Location: dettaglio_prodotto.php?id=$idProdotto");
    exit();
  }
}

// Verifica se � stato passato l'ID del prodotto tramite una richiesta GET
if (isset($_GET['id'])) {
  $idProdotto = $_GET['id'];

  // Verifica se � stato inviato un modulo di conferma eliminazione
  if (isset($_POST['confermaEliminazione'])) {
    eliminaProdotto($idProdotto, $conn);
  }
} else {
  $_SESSION['messaggio'] = "ID del prodotto non specificato.";
  header("Location: catalogo_rivenditore.php");
  exit();
}
?>

        <div class="row mt-4">
          <div class="col-md-4">
            <img src="<?php echo $immagineProdotto; ?>" class="img-fluid" alt="Immagine Prodotto">
          </div>
          <div class="col-md-8">
            <h3><?php echo $nomeProdotto; ?></h3>
            <p><?php echo $descrizioneProdotto; ?></p>
            <p>Prezzo: <?php echo $prezzoProdotto; ?> €/kg</p>
            <form method="POST" action="prodotto_rivenditore.php?id=<?php echo $idProdotto; ?>">
              <button type="submit" class="btn btn-danger" name="confermaEliminazione">Elimina prodotto</button>
            </form>
            <p>Quantità ordinata:</p>
      <div class="progress mb-3">
      <div class="progress-bar" role="progressbar" style="width: <?php echo ($quantitaOrdinata / $kgGiornalieri) * 100; ?>%;" aria-valuenow="<?php echo $quantitaOrdinata; ?>" aria-valuemin="0" aria-valuemax="<?php echo $kgGiornalieri - $quantitaOrdinata; ?>">
      <?php echo $quantitaOrdinata . "kg / " . $kgGiornalieri . "kg"?>
    </div>
  </div>
          </div>
          <?php
  // Query per ottenere i dati dalla tabella specificata
  $queryTabella = "SELECT * FROM ordini WHERE idProdotto = $idProdotto";
  $resultOrdini = mysqli_query($conn, $queryTabella);       

  // Controllo se ci sono ordini per il prodotto
  if (mysqli_num_rows($resultOrdini) > 0) {
    ?>
    <h2 class="mt-5">Ordini per questo prodotto</h2>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID Ordine Singolo Prodotto</th>
            <th>Data</th>
            <th>ID Rivenditore</th>
            <th>ID Utente</th>
            <th>Kg</th>
            <th>Hub</th>
            <th>Cella</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Ciclo sui risultati degli ordini e li mostro nella tabella
          while ($rowOrdine = mysqli_fetch_assoc($resultOrdini)) {
            $idOrdineSingoloProdotto = $rowOrdine['idOrdineSingoloProdotto'];
            $dataOrdine = $rowOrdine['data'];
            $idRivenditore = $rowOrdine['idRivenditore'];
            $idUtente = $rowOrdine['idUtente'];
            $kg = $rowOrdine['kg'];
            $hub = $rowOrdine['hub'];
            $cella = $rowOrdine['cella'];
            ?>
            <tr>
              <td><?php echo $idOrdineSingoloProdotto; ?></td>
              <td><?php echo $dataOrdine; ?></td>
              <td><?php echo $idRivenditore; ?></td>
              <td><?php echo $idUtente; ?></td>
              <td><?php echo $kg; ?></td>
              <td><?php echo $hub; ?></td>
              <td><?php echo $cella; ?></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
    </div>
    <?php
  } else {
    echo '<p>Nessun ordine per questo prodotto.</p>';
  }
?>
        </div>
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
