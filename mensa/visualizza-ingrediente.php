<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla se un ID viene passato o meno
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID ingrediente non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste un ingrediente con questo ID
    $idIngrediente = $_GET['id'];
    $checkIngrediente = true;
    $query = "SELECT nome, descrizione FROM ingredienti WHERE (id_ingrediente = ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idIngrediente);
    if($statement->execute()) {
        $statement->store_result();
        // Se non esiste un ingrediente con questo ID, mostra un messaggio di errore
        if($statement->num_rows == 0) {
            echo 'Non esiste un ingrediente con questo ID.';
            $checkIngrediente = false;
            exit();
        } else {
            // Se esiste un ingrediente con questo ID, salva i dati
            $statement->bind_result($nome, $descrizione); // Abbina i risultati della query alle variabili
            $statement->fetch(); // Estrae i risultati dalla query
            $statement->close();
        }
    } else {
        echo 'Si è verificato un errore.';
    }
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead($nome . ' | Mensa');
require_once ABSPATH . '/layout/components/header.php';

?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1><?php echo $nome; ?></h1>
        </div>
    </div>
    <div class="content-view">
        <div class="content-view-body">
            <div class="content-view-body-text">
                <p><?php echo $descrizione; ?></p>
            </div>
        </div>
    </div>
</div>

<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
