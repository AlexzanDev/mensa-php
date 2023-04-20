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

// Gestisci archiviazione lotto
if(isset($_POST['archiviaBtn'])) {
    $modificaTempo = date('Y-m-d H:i:s');
    $idLotto = $_POST['idLotto'];
    // Query per archiviare il lotto
    $query = "UPDATE magazzino SET stato = ?, ultima_modifica = ?, id_utente = ? WHERE id_lotto = ?";
    $stato = 0; // Archiviato
    $statement = $mysqli->prepare($query);
    $statement->bind_param('isii', $stato, $modificaTempo, $_SESSION['utente']['id_utente'], $idLotto);
    // Esegui la query
    if($statement->execute()) {
        $messaggio = '<div class="alert alert-success mt-3" role="alert">Lotto archiviato con successo.</div>';
        $ultimaModifica = $modificaTempo;
    } else {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante l\'archiviazione del lotto.</div>';
    }
    $statement->close();
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
            <?php
            if($_SESSION['utente']['livello'] == 1 || $_SESSION['utente']['livello'] == 2) {
                echo '<a class="ms-3" href="aggiungi-lotto.php">
                    <button class="btn btn-outline-dark fs-6 fw-bold">Aggiungi nuovo</button>
                </a>';
            }
            ?>
        </div>
        <input id="ricerca" type="text" class="form-control search-input" placeholder="Cerca lotto">
    </div>
    <?php
    if(isset($messaggio)) {
        echo $messaggio;
    }
    ?>
    <?php
    // Query per ottenere i lotti
    $query = "SELECT i.nome, m.id_lotto, m.descrizione, m.data_scadenza, m.quantita, m.prezzo, m.stato, m.id_utente FROM magazzino m, ingredienti i WHERE (i.id_ingrediente = m.id_ingrediente AND m.stato = 1)";
    $statement = $mysqli->prepare($query);
    // Esegui la query
    if ($statement->execute()) {
        $statement->store_result();
        // Controlla se ci sono risultati
        if ($statement->num_rows == 0) {
            echo '<div class="alert alert-warning mt-3" role="alert">Nessun lotto trovato.</div>';
            exit;
        } else {
            $statement->bind_result($nomeIngrediente, $idLotto, $descrizione, $dataScadenza, $quantita, $prezzo, $stato, $idUtente);
        ?>
            <table class="table table-view table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 14.3%">Nome ingrediente</th>
                        <th scope="col" style="width: 14.3%">ID lotto</th>
                        <th scope="col" style="width: 14.3%">Note</th>
                        <th scope="col" style="width: 14.3%">Data scadenza</th>
                        <th scope="col" style="width: 14.3%">Quantità (pezzi)</th>
                        <th scope="col" style="width: 14.3%">Prezzo</th>
                        <th scope="col" style="width: 14.3%">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($statement->fetch()) {
                    ?>
                    <tr>
                        <td>               
                            <?php echo $nomeIngrediente; ?></a>
                            <?php
                            if($_SESSION['utente']['livello'] == 1 || $_SESSION['utente']['livello'] == 2) {
                                echo '<a class="lista-small-text" href="modifica-lotto.php?id=' . $idLotto . '">Modifica lotto</a>';
                            }
                            ?>
                        </td>
                        <td><?php echo $idLotto; ?></td>
                        <td><?php echo strip_tags($descrizione); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($dataScadenza)); ?></td>
                        <td><?php echo $quantita; ?></td>
                        <td><?php echo $prezzo; ?>€</td>
                        <td>
                            <form method="post">
                                <input type="submit" name="archiviaBtn" class="btn btn-danger" value="Archivia">
                                <input type="hidden" name="idLotto" value="<?php echo $idLotto; ?>">
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
