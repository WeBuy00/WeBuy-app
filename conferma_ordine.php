<?php
    /*Just for your server-side code*/
    header('Content-Type: text/html; charset=utf-8');
?>


<?php
session_start();

// Connessione al database
require_once 'config.php';
$conn = new mysqli($host, $username, $password, $dbname);

// Controllo della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Recupera l'ultimo idOrdineComplessivo dal database
$sql = "SELECT MAX(idOrdineComplessivo) AS ultimo_id FROM ordini";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$ultimo_id_ordine_complessivo = $row['ultimo_id'];

// Calcola il nuovo idOrdineComplessivo incrementando di 1 l'ultimo id salvato
$nuovo_id_ordine_complessivo = $ultimo_id_ordine_complessivo + 1;



// Recupero i dati dal carrello
$carrello = $_SESSION['carrello'];

// Recupero l'hub scelto nella pagina precedente
$nome_hub = $_POST["luogo_ritiro"];
$id_hub = 0;
if ($nome_hub == "hub1") $id_hub = 1;
if ($nome_hub == "hub2") $id_hub = 2;
if ($nome_hub == "hub3") $id_hub = 3;
if ($nome_hub == "hub4") $id_hub = 4;
if ($nome_hub == "hubArduino") $id_hub = 5;





// Query per selezionare l'id della prima cella libera
$sql = "SELECT id_cella FROM $nome_hub WHERE occupato = 0 LIMIT 1";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // Cella libera trovata, recupera l'id e imposta la cella come occupata
    $row = $result->fetch_assoc();
    $id_cella_libera = $row['id_cella'];

    // Query per impostare la cella come occupata
    $sql_update = "UPDATE $nome_hub SET occupato = 1 WHERE id_cella = $id_cella_libera";
    $conn->query($sql_update);
} else {
    // Nessuna cella libera trovata (doppio controllo)
    // echo "Nessuna cella libera disponibile.";
}



// Inserimento di ogni prodotto nel carrello come un nuovo ordine
foreach ($carrello as $prodotto) {




    $idProdotto = $prodotto['id'];
    $idRivenditore = $prodotto['produttore_id'];
    $idUtente = $prodotto['user_id'];
    $kg = $prodotto['quantita'];
    $stato = "confermato";

    // Inserimento dell'ordine nella tabella "ordini"
    $sql = "INSERT INTO ordini (idOrdineComplessivo, data, idProdotto, idRivenditore, idUtente, kg, stato_ordine, hub, cella) VALUES ('$nuovo_id_ordine_complessivo', DATE(NOW()), '$idProdotto', '$idRivenditore', '$idUtente', '$kg', '$stato', '$id_hub', '$id_cella_libera')";


    // Esecuzione della query di inserimento
    if ($conn->query($sql) === TRUE) {
        // Ottieni l'ID dell'ultimo ordine inserito
        $ultimo_id_ordine = $conn->insert_id;
        
    } else {
        echo "Errore nell'inserimento dell'ordine: " . $conn->error;
        $conn->close();
        
    }
  
}


//Rimuovi il carrello
unset($_SESSION['carrello']);
        
//Chiudi la connessione al database
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Conferma Ordine</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  

</head>
<body>
  <?php include 'menu.php';?>
  <div class="container">
    <h2 class="mt-5">Conferma Ordine</h2>
    <div class="alert alert-success mt-4">
      Il tuo ordine è stato confermato con successo.
      <br>
      ID Ordine: <?php echo $ultimo_id_ordine; ?> <br>
      Hub scelto: <?php echo $nome_hub; ?> <br>
      Cella assegnata: <?php echo $id_cella_libera; ?>
    </div>
       <a class="btn btn-primary mt-4" href="/catalogo_categorie.php">Torna al catalogo</a>
  </div>
</body>
</html>
