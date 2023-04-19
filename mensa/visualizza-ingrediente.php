<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla i permessi
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit;
} elseif($_SESSION['utente']['livello'] == 5 || $_SESSION['utente']['livello'] == 2) { 
    die('Non hai i permessi per accedere a questa pagina.');
}

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

// Mostra ricette che contengono questo ingrediente
$queryRicette = "SELECT r.id_ricetta, r.nome FROM correlazioniir c, ricette r WHERE (c.id_ingrediente = ? AND c.id_ricetta = r.id_ricetta)";
$statementRicette = $mysqli->prepare($queryRicette);
$statementRicette->bind_param("i", $idIngrediente);
if($statementRicette->execute()) {
    $statementRicette->store_result();
    if($statementRicette->num_rows > 0) {
        $statementRicette->bind_result($idRicetta, $nomeRicetta); // Abbina i risultati della query alle variabili
        $ricette = array();
        while($statementRicette->fetch()) {
            $ricette[] = array(
                'id' => $idRicetta,
                'nome' => $nomeRicetta
            );
        }
        $checkRicette = true;
        $statementRicette->close();
    } else {
        $checkRicette = false;
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
                <div class="ingrediente-testo">
                    <p><?php echo $descrizione; ?></p>
                    <?php 
                    if($checkRicette) {
                        echo '<div class="ingrediente-ricette">';
                        echo '<h3>Ricette che contengono questo ingrediente</h3>';
                        echo '<ul>';
                        foreach($ricette as $ricetta) {
                            echo '<li><a href="' . ABSPATH . '/mensa/visualizza-ricetta.php?id=' . $ricetta['id'] . '">' . $ricetta['nome'] . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '<div class="ingrediente-ricette">';
                        echo '<p>Questo ingrediente non è contenuto in nessuna ricetta.</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
