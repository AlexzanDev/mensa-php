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

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Lista ricette | Mensa');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Ricette</h1>
            <?php
            if($_SESSION['utente']['livello'] != 2 && $_SESSION['utente']['livello'] != 3 && $_SESSION['utente']['livello'] != 5) {
                echo '<a class="ms-3" href="aggiungi-ricetta.php">
                        <button class="btn btn-outline-dark fs-6 fw-bold">Aggiungi nuovo</button>
                    </a>';
            }
            ?>
        </div>
        <input id="ricerca" type="text" class="form-control search-input" placeholder="Cerca ricetta">
    </div>
    <?php
    // Query per ottenere gli ingredienti
    $query = "SELECT nome, sommario, tempo_preparazione, tempo_cottura, id_ricetta FROM ricette";
    $statement = $mysqli->prepare($query);
    // Esegui la query
    if ($statement->execute()) {
        $statement->store_result();
        // Controlla se ci sono risultati
        if ($statement->num_rows == 0) {
            echo '<div class="alert alert-warning mt-3" role="alert">Nessuna ricetta trovata.</div>';
            exit;
        } else {
            $statement->bind_result($nome, $sommario, $tempoPreparazione, $tempoCottura, $idRicetta);
        ?>
            <table class="table table-view table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 25%">Nome</th>
                        <th scope="col" style="width: 25%">Breve descrizione</th>
                        <th scope="col" style="width: 25%">Tempo di preparazione</th>
                        <th scope="col" style="width: 25%">Tempo di cottura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($statement->fetch()) {
                    ?>
                        <tr>
                            <td>
                                <a class="link-primary table-link" href="visualizza-ricetta.php?id=<?php echo $idRicetta; ?>"><?php echo $nome; ?></a>
                                <?php
                                if($_SESSION['utente']['livello'] != 2 && $_SESSION['utente']['livello'] != 3 && $_SESSION['utente']['livello'] != 5) {
                                    echo '<a class="lista-small-text" href="modifica-ricetta.php?id=' . $idRicetta . '">Modifica</a>';
                                }
                                ?>
                            </td>
                            <td><?php echo strip_tags($sommario); ?></td>
                            <td><?php echo $tempoPreparazione; ?> minuti</td>
                            <td><?php echo $tempoCottura; ?> minuti</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
    } else {
        echo 'Si è verificato un errore.';
    }
    // Chiudi la connessione
    $statement->close();
    ?>
</div>
<?php

// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
