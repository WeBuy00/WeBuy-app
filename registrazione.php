<?php
/*Just for your server-side code*/
header('Content-Type: text/html; charset=ISO-8859-1');
session_start();
require_once 'config.php';

// Connessione al database
$conn = new mysqli($host, $username, $password, $dbname);

// Controllo se il form di registrazione è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Elaborazione dei dati di registrazione
  $username = $_POST['username'];
  $password = $_POST['password'];
  $distributore = $_POST['distributore'];
  $categoria = $_POST['categoria'];
  $nome = $_POST['nome'];
  $cognome = $_POST['cognome'];
  $codicefiscale = $_POST['codicefiscale'];
  $indirizzoresidenza = $_POST['indirizzoresidenza'];
  $CAP = $_POST['CAP'];
  $numerotelefonico = $_POST['numerotelefonico'];
  $email = $_POST['email'];
  $datadinascita = $_POST['datadinascita'];
  $urlfotoprofilo = $_POST['urlfotoprofilo'];

  // Verifica se l'utente esiste già nel database
  $query = "SELECT username FROM utenti WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // L'utente esiste già, gestisci l'errore
    echo "Username già in uso. Scegli un altro username.";
  } else {
    // Funzione per generare l'hash della password
    function generateHash($password) {
      $salt = bin2hex(random_bytes(16));
      $options = [
          'cost' => 12 // Costo di iterazione (work factor)
      ];
      $hash = password_hash($password . $salt, PASSWORD_BCRYPT, $options);
      return [$hash, $salt];
    }

    list($hash, $salt) = generateHash($password);

    // Esegui query per inserire i dati di registrazione nel database
    $query = "INSERT INTO utenti (username, hash, salt, distributore, categoria, nome, cognome, codicefiscale, indirizzoresidenza, CAP, numerotelefonico, email, datadinascita, urlfotoprofilo) VALUES ('$username', '$hash', '$salt', '$distributore', '$categoria', '$nome', '$cognome', '$codicefiscale', '$indirizzoresidenza', '$CAP', '$numerotelefonico', '$email', '$datadinascita', '$urlfotoprofilo')";
    $result = $conn->query($query);

    if ($result) {
      // Registrazione riuscita
      $_SESSION['messaggio'] = "Registrazione riuscita! Ora puoi effettuare il login.";
      header("Location: index.php");
    } else {
      // Registrazione fallita, gestisci l'errore
      echo "Si è verificato un errore durante la registrazione.";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pagina di Registrazione</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="container">
    <h2 class="mt-5">Pagina di Registrazione</h2>

    <form method="POST" action="registrazione.php" id="registrationForm">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" name="password" required>
      </div>

      <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" class="form-control" name="nome" required>
      </div>

      <div class="form-group">
        <label for="cognome">Cognome:</label>
        <input type="text" class="form-control" name="cognome" required>
      </div>

      <div class="form-group">
        <label for="codicefiscale">Codice Fiscale:</label>
        <input type="text" class="form-control" name="codicefiscale" required>
      </div>

      <div class="form-group">
        <label for="indirizzoresidenza">Indirizzo di Residenza:</label>
        <input type="text" class="form-control" name="indirizzoresidenza" required>
      </div>

      <div class="form-group">
        <label for="CAP">CAP:</label>
        <input type="text" class="form-control" name="CAP" required>
      </div>

      <div class="form-group">
        <label for="numerotelefonico">Numero Telefonico:</label>
        <input type="text" class="form-control" name="numerotelefonico" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" name="email" required>
      </div>

      <div class="form-group">
        <label for="datadinascita">Data di Nascita:</label>
        <input type="date" class="form-control" name="datadinascita" required>
      </div>

      <div class="form-group">
        <label for="urlfotoprofilo">URL Foto Profilo:</label>
        <input type="text" class="form-control" name="urlfotoprofilo" required>
      </div>

      <div class="form-group">
		<label for="distributore">Sei un rivenditore?</label>
		<select class="form-control" name="distributore" required>
		  <option value="0">No</option>
		  <option value="1">Sì</option>
		</select>
	  </div>

       <div class="form-group">
		<label for="categoria">Se sì, cosa vendi?</label>
			<select class="form-control" name="categoria" required>
			<option value="0">Non sono un distributore</option>
			<option value="carne">Carne</option>
			<option value="pesce">Pesce</option>
			<option value="fev">Frutta e Verdura</option>
			</select>
	  </div>

      <button type="submit" class="btn btn-primary">Registrati</button>
    </form>

	<p class="mt-3">Sei già registrato? <a href="index.php">Accedi</a></p>
	
  </div>
</body>
</html>
