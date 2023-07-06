<!-- index.php (Pagina di login) -->
<?php
session_start();
require_once 'config.php';

// Connessione al database
$conn = new mysqli($host, $username, $password, $dbname);

// Controllo se il form di login � stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Elaborazione delle credenziali di accesso
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query di autenticazione - evita SQL infection
  $query = "SELECT * FROM utenti WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Accedi agli attributi
    $id = $row['id'];
    $hash = $row['hash'];
    $salt = $row['salt'];
    $produttore = $row['distributore'];
    $categoria = $row['categoria'];

    function verifyPassword($password, $hash, $salt) {
      $newHash = password_hash($password . $salt, PASSWORD_BCRYPT);
      return password_verify($password . $salt, $hash);
    }

    if (verifyPassword($password, $hash, $salt)) {
      $_SESSION['user_id'] = $id;
      $_SESSION['username'] = $username;
      $_SESSION['produttore'] = $produttore;
      $_SESSION['categoria'] = $categoria;
      $_SESSION['messaggio'] = "Autenticazione riuscita! Benvenuto!";

      if ($produttore == 1) {
        $_SESSION['tipo_utente'] = "Produttore";
        $_SESSION['messaggio'] = "Autenticazione riuscita! Hai effettuato il login come produttore.";
        header("Location: catalogo_rivenditore.php");
        exit();
      } else {
        header("Location: catalogo_categorie.php");
        exit();
      }
    } else {
      $_SESSION['messaggio'] = "Username o password errata.";
    }
  } 
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Pagina di Login</title>
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="container">
  <div class="">
    <div class="text-center">
      <img src="logo.jpeg" class="img-fluid  text-center" style="max-width: 300px;"></img>
    </div>
    <h3 class="mt-5 text-center">Benvenuto su WeBuy! Effettua il login</h3>

    <?php
    // Controllo se esiste un messaggio di successo o errore
    if (isset($_SESSION['messaggio'])) {
      $alertType = ($_SESSION['messaggio'] == "Registrazione riuscita! Ora puoi effettuare il login.") ? "success" : "danger";
      echo '<div class="alert alert-' . $alertType . ' mt-4">' . $_SESSION['messaggio'] . '</div>';
      unset($_SESSION['messaggio']);
    }
    ?>

    <form id="loginForm" action="index.php" method="POST">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <button type="submit" class="btn btn-primary">Accedi</button>
    </form>

    <p class="mt-3">Non hai ancora un account? <a href="registrazione.php">Registrati</a></p>
  </div>
</div>


</html>
