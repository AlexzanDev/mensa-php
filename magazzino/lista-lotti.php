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
    if(isset($_POST['arcvBtn'])) {
    // Query per aggiungere l'ingrediente
    $modificaTempo = date('Y-m-d H:i:s');
    $query = "UPDATE magazzino SET stato=?, ultima_modifica=?, id_utente=? WHERE id_lotto=?;";
    $stato=0;
    $statement = $mysqli->prepare($query);
    $statement->bind_param('isii', $stato, $modificaTempo, $_SESSION['utente'], $idLotto);
    // Esegui la query
    if($statement->execute()) {
        $messaggio = '<div class="alert alert-success mt-3" role="alert">lotto modificato con successo</div>';
        $ultimaModifica = $modificaTempo;
    } else {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante la modifica del lotto.</div>';
    }
    $statement->close();

    }
    // Query per ottenere gli ingredienti
    $query = "SELECT i.nome,m.id_lotto, m.descrizione, m.data_scadenza, m.quantita,m.prezzo,m.ultima_modifica,m.stato,m.id_utente FROM magazzino m,ingredienti i WHERE i.id_ingrediente=m.id_ingrediente and m.stato=1;";
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
                        <th scope="col" style="width: 25%">Nome ingrediente</th>
                        <th scope="col" style="width: 25%">ID lotto</th>
                        <th scope="col" style="width: 25%">Descrizione</th>
                        <th scope="col" style="width: 25%">Data scadenza</th>
                        <th scope="col" style="width: 25%">Quantità rimanente</th>
                        <th scope="col" style="width: 25%">Prezzo</th>
                        <th scope="col" style="width: 25%">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($statement->fetch()) {
                    ?>
                        <tr>
                            <td>               
                                <?php echo $nomeIngrediente; ?></a>
                                <a class="lista-small-text" href="modifica-lotto.php?id=<?php echo $idLotto; ?>">Modifica lotto</a>
                            </td>
                            <td><?php echo $idLotto; ?></td>
                            <td><?php echo strip_tags($descrizione); ?></td>
                            <td><?php echo $dataScadenza; ?></td>
                            <td><?php echo $quantita; ?></td>
                            <td><?php echo $prezzo; ?>€</td>
                            <td>
                                <form method="post">
                                    <input type="submit" name="arcvBtn" class="btn btn-danger" value="Archivia">
                                </form>
                            </td>
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