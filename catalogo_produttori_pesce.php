<?php
    session_start();
    require_once 'config.php';
    // Connessione al database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Controllo della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Produttori di Pesce</title>
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
        <h2 class="mt-5">Produttori di Pesce</h2>

        <div class="row">
            <?php
                // Query per ottenere i produttori di carne dal database
                $query = "SELECT *
                          FROM utenti
                          WHERE utenti.categoria = 'pesce'";

                $result = $conn->query($query);

                // Ciclo per visualizzare i produttori di carne
                while ($row = $result->fetch_assoc()) {
                    $idProduttore = $row['id'];
                    $nomeProduttore = $row['username'];
                    $fotoproduttore = $row['urlfotoprofilo'];

                    ?>
                    <div class="col-md-4 mt-4">
                        <div class="card product-card">
                            <a href="catalogo_prodotti.php?idProduttore=<?php echo $idProduttore; ?>">
                                <img src="<?php echo $fotoproduttore; ?>" class="card-img-top product-image" alt="Immagine Prodotto">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $nomeProduttore; ?></h5>
                                    </div>
                            </a>
                        </div>
                    </div>


                    <?php
                }

                // Chiusura del risultato della query
                $result->close();
            ?>
        </div>
    </div>

    <?php
        // Chiusura della connessione al database
        $conn->close();
    ?>
</body>
</html>
