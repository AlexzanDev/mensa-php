<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla se un ID viene passato o meno
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID ricetta non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste una ricetta con questo ID
    $idRicetta = $_GET['id'];
    $checkRicetta = true;
    $query = "SELECT nome, descrizione, tempo_preparazione, tempo_cottura FROM ricette WHERE (id_ricetta = ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idRicetta);
    if($statement->execute()) {
        $statement->store_result();
        // Se non esiste una ricetta con questo ID, mostra un messaggio di errore
        if($statement->num_rows == 0) {
            echo 'Non esiste una ricetta con questo ID.';
            $checkRicetta = false;
            exit();
        } else {
            // Se esiste una ricetta con questo ID, salva i dati
            $statement->bind_result($nome, $descrizione, $tempoPreparazione, $tempoCottura); // Abbina i risultati della query alle variabili
            $statement->fetch(); // Estrae i risultati dalla query
            $statement->close();
        }
    } else {
        echo 'Si è verificato un errore.';
    }
}

// Ottieni gli ingredienti della ricetta
$queryIngredienti = "SELECT c.id_ingrediente, c.quantita, i.nome, i.unita_misura FROM correlazioniir c, ingredienti i WHERE (id_ricetta = ? AND c.id_ingrediente = i.id_ingrediente)";
$statementIngredienti = $mysqli->prepare($queryIngredienti);
$statementIngredienti->bind_param("i", $idRicetta);
if($statementIngredienti->execute()) {
    $statementIngredienti->store_result();
    $statementIngredienti->bind_result($idIngrediente, $quantita, $nomeIngrediente, $unitaMisura);
    $ingredientiRicetta = array();
    // Salva gli ingredienti in un array
    while ($statementIngredienti->fetch()) {
        $ingredienteRicetta = array(
            'id_ingrediente' => $idIngrediente,
            'nome' => $nomeIngrediente,
            'quantita' => $quantita,
            'unita_misura' => $unitaMisura
        );
        array_push($ingredientiRicetta, $ingredienteRicetta);
    }
}
$statementIngredienti->close();

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
                <div class="ricetta-tempi">
                    <div class="ricetta-tempi-preparazione">
                        <p class="fw-bold">Tempo di preparazione</p>
                        <p><?php echo $tempoPreparazione; ?> minuti</p>
                    </div>
                    <div class="ricetta-tempi-cottura">
                        <p class="fw-bold">Tempo di cottura</p>
                        <p><?php echo $tempoCottura; ?> minuti</p>
                    </div>
                </div>
                <div class="ricetta-ingredienti">
                    <h4>Ingredienti</h4>
                    <ul>
                        <?php
                            // Mostra gli ingredienti
                            foreach($ingredientiRicetta as $ingrediente) {
                                echo '<li>' . $ingrediente['quantita'] . ' ' . $ingrediente['unita_misura'] . ' di ' . lcfirst($ingrediente['nome']) . '</li>';
                            }
                            // Controlla se non ci sono ingredienti
                            if(empty($ingredientiRicetta)) {
                                echo '<li>Non ci sono ingredienti.</li>';
                            }
                        ?>
                    </ul>
                </div>
                <div class="ricetta-preparazione">
                    <p><?php echo $descrizione; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
