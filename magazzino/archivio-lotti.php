<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla i permessi
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit;
} elseif($_SESSION['utente']['livello'] != 1 && $_SESSION['utente']['livello'] != 3 && $_SESSION['utente']['livello'] != 2) { 
    die('Non hai i permessi per accedere a questa pagina.');
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Lista lotti | Magazzino');
require_once ABSPATH . '/layout/components/header.php';

?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Lotti</h1>
            <a class="ms-3" href="aggiungi-lotto.php">
                <button class="btn btn-outline-dark fs-6 fw-bold">Aggiungi nuovo</button>
            </a>
        </div>
        <input id="ricerca" type="text" class="form-control search-input" placeholder="Cerca lotto">
    </div>
    <?php
    // Query per ottenere gli ingredienti
    $query = "SELECT i.nome,m.id_lotto, m.descrizione, m.data_scadenza, m.quantita,m.prezzo,m.ultima_modifica,m.stato,m.id_ingrediente FROM magazzino m,ingredienti i WHERE i.id_ingrediente=m.id_ingrediente and m.stato=0;";
    $statement = $mysqli->prepare($query);
    // Esegui la query
    if ($statement->execute()) {
        $statement->store_result();
        // Controlla se ci sono risultati
        if ($statement->num_rows == 0) {
            echo '<div class="alert alert-warning mt-3" role="alert">Nessun lotto trovato.</div>';
            exit;
        } else {
            $statement->bind_result($nomeIngrediente,$idLotto, $descrizione, $dataScadenza, $quantita,$prezzo,$ultimaModifica,$stato,$idUtente);
        ?>
            <table class="table table-view table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 25%">Nome</th>
                        <th scope="col" style="width: 25%">ID Lotto</th>
                        <th scope="col" style="width: 25%">Descrizione</th>
                        <th scope="col" style="width: 25%">Data scadenza</th>
                        <th scope="col" style="width: 25%">Quantità (pezzi)</th>
                        <th scope="col" style="width: 25%">Prezzo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($statement->fetch()) {
                    ?>
                        <tr>
                            <td>
                                <a class="link-primary table-link" href="modifica-lotto.php?id=<?php echo $idLotto; ?>"><?php echo $nomeIngrediente; ?></a>
                            </td>
                            <td><?php echo $idLotto; ?></td>
                            <td><?php echo strip_tags($descrizione); ?></td>
                            <td><?php echo $dataScadenza; ?></td>
                            <td><?php echo $quantita; ?></td>
                            <td><?php echo $prezzo; ?>€</td>
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
?>