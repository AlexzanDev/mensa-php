<?php

// Importa il file di caricamento
require_once '../load.php';
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
    $query = "SELECT id_lotto, descrizione, data_scadenza, quantita,prezzo,ultima_modifica,stato,id_utente FROM magazzino";
    $statement = $mysqli->prepare($query);
    // Esegui la query
    if ($statement->execute()) {
        $statement->store_result();
        // Controlla se ci sono risultati
        if ($statement->num_rows == 0) {
            echo '<div class="alert alert-warning mt-3" role="alert">Nessun lotto trovato.</div>';
            exit;
        } else {
            $statement->bind_result($nome, $descrizione, $unitaMisura, $idLotto);
        ?>
            <table class="table table-view table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 25%">Nome</th>
                        <th scope="col" style="width: 25%">Descrizione</th>
                        <th scope="col" style="width: 25%">Unità di misura</th>
                        <th scope="col" style="width: 25%">Quantità in magazzino</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($statement->fetch()) {
                    ?>
                        <tr>
                            <td>
                                <a class="link-primary table-link" href="modifica-lotto.php?id=<?php echo $idLotto; ?>"><?php echo $nome; ?></a>
                            </td>
                            <td><?php echo strip_tags($descrizione); ?></td>
                            <td><?php echo $unitaMisura; ?></td>
                            <td><?php echo $idLotto; ?></td>
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