<?php
require_once 'config.php';




  // Connessione al database
  $conn = new mysqli($host, $username, $password, $dbname);
  

  mysqli_query($conn, "UPDATE hub1 SET occupato = 0;");
  mysqli_query($conn, "UPDATE hub2 SET occupato = 0;");
  mysqli_query($conn, "UPDATE hub3 SET occupato = 0;");
  mysqli_query($conn, "UPDATE hub4 SET occupato = 0;");
  mysqli_query($conn, "UPDATE hubArduino SET occupato = 0;");

  mysqli_query($conn, "UPDATE ordini SET stato_ordine = 'non ritirato in tempo' WHERE stato_ordine != 'ritirato';");


  





  // Chiusura della connessione al database
  mysqli_close($conn);

?>

