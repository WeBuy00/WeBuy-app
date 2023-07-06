<?php
session_start();
require_once 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];

    if($_SESSION['tipo_utente'] != "Produttore") {
        echo "Ciao";
        exit;
    }
} else {
    header("Location: accesso_negato.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $prezzo = $_POST['prezzo'];
    $immagine = $_POST['immagine'];
    $kgGiornalieri = $_POST['kg_giornalieri'];

    // Connessione al database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Controllo della connessione
    if (!$conn) {
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    // Query per inserire il prodotto nel database
    $query = "INSERT INTO prodotti (nome, descrizione, prezzo, immagine, idProduttore, kgGiornalieri) VALUES ('$nome', '$descrizione', '$prezzo', '$immagine', '$user_id', '$kgGiornalieri')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['messaggio'] = "Prodotto aggiunto con successo!";
        header("Location: catalogo_rivenditore.php");
        exit;
    } else {
        echo "Errore durante l'aggiunta del prodotto: " . mysqli_error($conn);
    }

    // Chiusura della connessione al database
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aggiungi Prodotto</title>
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
                <a class="nav-link" href="catalogo_rivenditore.php">Catalogo</a>
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
    <h2 class="mt-5">Aggiungi Prodotto</h2>

    <form method="POST" class="mt-4">
        <div class="form-group">
            <label for="nome">Nome Prodotto:</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="form-group">
            <label for="descrizione">Descrizione Prodotto:</label>
            <textarea class="form-control" name="descrizione" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="prezzo">Prezzo Prodotto al kg:</label>
            <input type="number" step="0.01" class="form-control" name="prezzo" required>
        </div>
        <div class="form-group">
            <label for="immagine">URL Immagine:</label>
            <input type="text" class="form-control" name="immagine" required>
        </div>
        <div class="form-group">
            <label for="kg_giornalieri">KG vendibili giornalmente:</label>
            <input type="number" class="form-control" name="kg_giornalieri" required>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi</button>
    </form>
</div>

</body>
</html>
