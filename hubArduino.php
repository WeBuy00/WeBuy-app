<?php

//LEGGENDA 0==LIBERO (peso=0) 1==ORDINE EFFETTUATO (peso=0) 2==ORDINE ARRIVATO (peso=50)  IF (2 && peso=50) -> stato_cella=0

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $stato = $_GET["stato"];
    $peso = $_GET["peso"];
    $daritirare = $_GET["daritirare"];
    
    if($stato == 2 && $peso > 50){
    
      // Crea connessione al database
      $conn = new mysqli($host, $username, $password, $dbname);

      // Controlla la connessione
      if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
      } 

      // Query SQL per aggiornare il valore nella tabella
      $sql = "UPDATE hubArduino SET occupato = '$stato' WHERE id_cella = '1'";

      // Esegue la query SQL
      if ($conn->query($sql) === TRUE) {
          echo "Record aggiornato con successo";
      } else {
          echo "Errore nell'aggiornamento del record: " . $conn->error;
      }

      // Query SQL per aggiornare il valore nella tabella

      $sql = "UPDATE ordini SET stato_ordine = 'arrivato' WHERE hub = '5'";

      if ($conn->query($sql) === TRUE) {
          echo "Record aggiornato con successo";
      } else {
          echo "Errore nell'aggiornamento del record: " . $conn->error;
      }

      // Chiude la connessione al database
      $conn->close();
   } elseif($stato == 2 && $peso < 50 && $daritirare == 2){
    
    
      // Crea connessione al database
      $conn = new mysqli($host, $username, $password, $dbname);


      $sql = "UPDATE ordini SET stato_ordine = 'ritirato' WHERE hub = '5'";

      if ($conn->query($sql) === TRUE) {
          echo "Record aggiornato con successo";
      } else {
          echo "Errore nell'aggiornamento del record: " . $conn->error;
      }

      // Chiude la connessione al database
      $conn->close();

   } else {
    echo "Non sono stati inviati i dati giusti";
    }
   
   
   
}
else {
    echo "Nessun dato inviato tramite HTTP GET.";
}
?>
