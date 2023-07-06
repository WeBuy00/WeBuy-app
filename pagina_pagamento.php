<!DOCTYPE html>
<html>
<head>
  <title>Pagamento</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

  <?php include 'menu.php';?>


  <div class="container">
    <h2 class="mt-5">Pagamento</h2>
    <form action="conferma_ordine.php" method="POST">
      <div class="form-group">
        <label for="carta">Seleziona carta di pagamento:</label>
        <select class="form-control" id="carta_pagamento" name="carta_pagamento">
          <option value="">Seleziona carta di pagamento:</option>
          <?php
            require_once 'config.php';
            session_start();

            // Ottenere i dati dell'utente dal database
            $user_id = $_SESSION['user_id'];

            $conn = new mysqli($host, $username, $password, $dbname);

            if ($conn->connect_error) {
              die("Connessione fallita: " . $conn->connect_error);
            }

            $query_carte = "SELECT * FROM carte WHERE id_utente = '$user_id'";
            $result_carte = $conn->query($query_carte);

            // Genera le opzioni per le carte
            while ($row_carte = $result_carte->fetch_assoc()) {
              $id_carta = $row_carte['id_carta'];
              $numero_carta = $row_carte['numero'];
              $titolare_carta = $row_carte['titolare'];
              $scadenza_carta = $row_carte['scadenza'];

              echo "<option value='$id_carta'>$titolare_carta - $numero_carta (Scadenza: $scadenza_carta)</option>";
            }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="luogo_ritiro">Luogo di ritiro:</label>
        <select class="form-control" id="luogo_ritiro" name="luogo_ritiro">
          <option value="">Scegli l'hub di ritiro:</option>
          <?php
            $hubs = array("hub1", "hub2", "hub3", "hub4", "hubArduino");
		
		// Comunico l'hub alla pagina successiva
	    
            foreach ($hubs as $hub) {
	     
              $query = "SELECT * FROM $hub WHERE occupato = 0 LIMIT 1";
              $result = $conn->query($query);
              if ($result->num_rows > 0) {
                echo "<option value='$hub'>$hub</option>";
              }
            }

            $conn->close();
          ?>
        </select>
      </div>
      
      <button type="submit" class="btn btn-primary">Paga</button>
    </form>
  </div>
</body>
</html>
