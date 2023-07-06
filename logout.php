<?php
// Avvio della sessione
session_start();

// Cancella i dati della sessione
session_unset();

// Distruggi la sessione
session_destroy();

// Reindirizzamento alla pagina di accesso
header("Location: index.php");
exit();
?>
