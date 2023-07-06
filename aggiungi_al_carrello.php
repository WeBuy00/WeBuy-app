<?php
// Avvio della sessione
session_start();

// Recupero i dati del prodotto dal form
$prodotto_id = $_POST['prodotto_id'];
$prodotto_nome = $_POST['prodotto_nome'];
$prodotto_prezzo = $_POST['prodotto_prezzo'];
$prodotto_quantita = $_POST['quantita'];
$produttore_id = $_POST['id_produttore'];
$user_id = $_SESSION['user_id'];
$_SESSION['messaggio'] = "Prodotto aggiunto al carrello";



// Controllo se il prodotto è già presente nel carrello
$prodotto_trovato = false;
foreach ($_SESSION['carrello'] as &$item) {
  if ($item['id'] === $prodotto_id) {
    // Il prodotto è già presente, aggiorno la quantità e il prezzo
    $item['quantita'] += $prodotto_quantita;
    $item['prezzo'] += $prodotto_prezzo * $prodotto_quantita;
    $prodotto_trovato = true;
    break;
  }
}

// Se il prodotto non è stato trovato nel carrello, lo aggiungo
if (!$prodotto_trovato) {
  // Creazione dell'array del prodotto
  $prodotto = array(
    'id' => $prodotto_id,
    'nome' => $prodotto_nome,
    'quantita' => $prodotto_quantita,
    'prezzo' => $prodotto_prezzo * $prodotto_quantita,
    'produttore_id' => $produttore_id,
    'user_id' => $user_id
  );

  // Aggiunta del prodotto al carrello
  $_SESSION['carrello'][] = $prodotto;

}

// Impostazione del messaggio di successo
$_SESSION['messaggio'] = "Prodotto aggiunto al carrello";

// Reindirizzamento alla pagina del catalogo dei prodotti
header("Location: catalogo_prodotti.php?idProduttore=". $produttore_id);
exit();
?>