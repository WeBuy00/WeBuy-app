<?php
require_once 'config.php';

// Connessione al database
$conn = new mysqli($host, $username, $password, $dbname);

// Controllo della connessione
if (!$conn) {
  die("Connessione al database fallita: " . mysqli_connect_error());
}

// Verifica se è stato passato l'ID del prodotto tramite una richiesta POST
if (isset($_POST['id'])) {
  $idProdotto = $_POST['id'];

  // Query per eliminare il prodotto dal database
  $query = "DELETE FROM prodotti WHERE id = $idProdotto";

  if (mysqli_query($conn, $query)) {
    // Prodotto eliminato con successo
    echo "Prodotto eliminato con successo.";
  } else {
    // Errore durante l'eliminazione del prodotto
    echo "Si è verificato un errore durante l'eliminazione del prodotto: " . mysqli_error($conn);
  }
} else {
  // ID del prodotto non specificato
  echo "ID del prodotto non specificato.";
}

// Chiusura della connessione al database
mysqli_close($conn);
?>
