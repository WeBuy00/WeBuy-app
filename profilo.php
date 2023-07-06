<?php
session_start();
require_once 'config.php';

// Controllo se l'utente � autenticato, altrimenti reindirizzalo alla pagina di login
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}

// Connessione al database
$conn = new mysqli($host, $username, $password, $dbname);

// Ottenere i dati dell'utente dal database
$username = $_SESSION['username'];
$query = "SELECT * FROM utenti WHERE username = '$username'";
$result = $conn->query($query);


if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();

  // Recupera i dati del profilo
  $id = $row['id'];
  $nome = $row['nome'];
  $cognome = $row['cognome'];
  $codicefiscale = $row['codicefiscale'];
  $indirizzoresidenza = $row['indirizzoresidenza'];
  $CAP = $row['CAP'];
  $numerotelefonico = $row['numerotelefonico'];
  $email = $row['email'];
  $datadinascita = $row['datadinascita'];
  $urlfotoprofilo = $row['urlfotoprofilo'];



  $query = "SELECT * 
            FROM ordini 
            INNER JOIN utenti ON ordini.idRivenditore = utenti.id
            INNER JOIN prodotti ON ordini.idProdotto = prodotti.id
            WHERE idUtente = $id
            ORDER BY ordini.data DESC";





  $storico_result = $conn->query($query);

} else {
  // L'utente non � presente nel database, reindirizzalo alla pagina di login
  header("Location: index.php");
  exit();
}



// Controllo se il form di aggiunta carta � stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero']) && isset($_POST['titolare']) && isset($_POST['scadenza']) && isset($_POST['cvv'])) {
  // Elaborazione dei dati di aggiunta carta
  $numero = $_POST['numero'];
  $titolare = $_POST['titolare'];
  $scadenza = $_POST['scadenza'];
  $cvv = $_POST['cvv'];

  // Esegui la query per aggiungere la nuova carta al database
  $query_aggiungi_carta = "INSERT INTO carte (id_utente, numero, titolare, scadenza, cvv) VALUES ('$id', '$numero', '$titolare', '$scadenza', '$cvv')";
  $result_aggiungi_carta = $conn->query($query_aggiungi_carta);

  if ($result_aggiungi_carta) {
    $_SESSION['messaggio'] = "Nuova carta aggiunta con successo.";
    header("Location: profilo.php");
    exit();
  } else {
    $_SESSION['messaggio'] = "Si � verificato un errore durante l'aggiunta della carta.";
  }
}

// Controllo se l'ID della carta � stato fornito nell'URL per la rimozione
if (isset($_GET['rimuovi_carta'])) {
  $id_carta = $_GET['rimuovi_carta'];

  // Esegui la query per eliminare la carta dal database
  $query_elimina_carta = "DELETE FROM carte WHERE id_carta = '$id_carta'";
  $result_elimina_carta = $conn->query($query_elimina_carta);

  if ($result_elimina_carta) {
    $_SESSION['messaggio'] = "Carta eliminata con successo.";
    header("Location: profilo.php");
    exit();
  } else {
    $_SESSION['messaggio'] = "Si � verificato un errore durante l'eliminazione della carta.";
  }
}

// Ottenere i dati delle carte dell'utente dal database
$query_carte = "SELECT * FROM carte WHERE id_utente = '$id'";
$result_carte = $conn->query($query_carte);





// Controllo se il form di modifica � stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Elaborazione dei dati di modifica
  $nome = $_POST['nome'];
  $cognome = $_POST['cognome'];
  $codicefiscale = $_POST['codicefiscale'];
  $indirizzoresidenza = $_POST['indirizzoresidenza'];
  $CAP = $_POST['CAP'];
  $numerotelefonico = $_POST['numerotelefonico'];
  $email = $_POST['email'];
  $datadinascita = $_POST['datadinascita'];

  // Esegui la query per aggiornare i dati del profilo utente nel database
  $query = "UPDATE utenti SET nome='$nome', cognome='$cognome', codicefiscale='$codicefiscale', indirizzoresidenza='$indirizzoresidenza', CAP='$CAP', numerotelefonico='$numerotelefonico', email='$email', datadinascita='$datadinascita' WHERE id='$id'";
  $result = $conn->query($query);

  if ($result) {
    // Aggiornamento riuscito
    $_SESSION['messaggio'] = "Dati del profilo aggiornati con successo.";
    header("Location: profilo.php");
    exit();
  } else {
    // Aggiornamento fallito, gestisci l'errore
    $error = "Si � verificato un errore durante l'aggiornamento dei dati del profilo.";
  }
}


// Chiusura della connessione al database

?>

<!DOCTYPE html>
<html>
<head>
  <title>Profilo Utente</title>
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
    <h2 class="mt-5">Profilo Utente</h2>

    <?php if (isset($error)) { ?>
      <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
    <?php } ?>

    <?php if (isset($_SESSION['messaggio'])) { ?>
      <div class="alert alert-success mt-4"><?php echo $_SESSION['messaggio']; ?></div>
      <?php unset($_SESSION['messaggio']); ?>
    <?php } ?>

    <div class="row">
      <div class="col-md-4 mt-4">
        <img src="<?php echo $urlfotoprofilo; ?>" class="img-fluid" alt="Foto Profilo">
      </div>
      <div class="col-md-8 mt-4">
        <form method="POST" action="profilo.php">
          <table class="table">
            <tbody>
              <tr>
                <th scope="row">Nome</th>
                <td><input type="text" class="form-control" name="nome" value="<?php echo $nome; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">Cognome</th>
                <td><input type="text" class="form-control" name="cognome" value="<?php echo $cognome; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">Codice Fiscale</th>
                <td><input type="text" class="form-control" name="codicefiscale" value="<?php echo $codicefiscale; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">Indirizzo di Residenza</th>
                <td><input type="text" class="form-control" name="indirizzoresidenza" value="<?php echo $indirizzoresidenza; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">CAP</th>
                <td><input type="text" class="form-control" name="CAP" value="<?php echo $CAP; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">Numero Telefonico</th>
                <td><input type="text" class="form-control" name="numerotelefonico" value="<?php echo $numerotelefonico; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">Email</th>
                <td><input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required></td>
              </tr>
              <tr>
                <th scope="row">Data di Nascita</th>
                <td><input type="date" class="form-control" name="datadinascita" value="<?php echo $datadinascita; ?>" required></td>
              </tr>
            </tbody>
          </table>

          <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        </form>

        <h2 class="mt-5">Metodi di Pagamento</h2>

        <div class="row">
          <div class="col-md-12 mt-4">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Numero Carta</th>
                  <th scope="col">Titolare</th>
                  <th scope="col">Scadenza</th>
                  <th scope="col">CVV</th>
                  <th scope="col">Azioni</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row_carte = $result_carte->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row_carte['numero'] . "</td>";
                  echo "<td>" . $row_carte['titolare'] . "</td>";
                  echo "<td>" . $row_carte['scadenza'] . "</td>";
                  echo "<td>" . $row_carte['cvv'] . "</td>";
                  echo "<td><a href=\"profilo.php?rimuovi_carta=" . $row_carte['id_carta'] . "\">Rimuovi</a></td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <h2 class="mt-5">Aggiungi Carta di Pagamento</h2>

        <div class="row">
          <div class="col-md-8 mt-4">
            <form method="POST" action="profilo.php">
              <table class="table">
                <tbody>
                  <tr>
                    <th scope="row">Numero Carta</th>
                    <td><input type="text" class="form-control" name="numero" required></td>
                  </tr>
                  <tr>
                    <th scope="row">Titolare</th>
                    <td><input type="text" class="form-control" name="titolare" required></td>
                  </tr>
                  <tr>
                    <th scope="row">Scadenza</th>
                    <td><input type="date" class="form-control" name="scadenza" required></td>
                  </tr>
                  <tr>
                    <th scope="row">CVV</th>
                    <td><input type="text" class="form-control" name="cvv" required></td>
                  </tr>
                </tbody>
              </table>

              <button type="submit" class="btn btn-primary">Aggiungi Carta</button>
            </form>
          </div>
        </div>



	<h2 class="mt-5">Storico Ordini</h2>

    <div class="row">
      <div class="col-md-12 mt-4">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID Ordine Complessivo</th>
              <th scope="col">Data</th>
              <th scope="col">Nome Rivenditore</th>
              <th scope="col">Nome Prodotto</th>
              <th scope="col">Kg Ordinati</th>
              <th scope="col">Hub</th>
              <th scope="col">Cella</th>
	            <th scope="col">Stato</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = $storico_result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['IdOrdineComplessivo'] . "</td>";
              echo "<td>" . $row['data'] . "</td>";
              echo "<td>" . $row['username'] . "</td>";
              echo "<td>" . $row['nome'] . "</td>";
              echo "<td>" . $row['kg'] . "</td>";
              echo "<td>" . $row['hub'] . "</td>";
              echo "<td>" . $row['cella'] . "</td>";
              echo "<td>" . $row['stato_ordine'] . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>



      </div>
    </div>
  </div>
</body>
</html>
