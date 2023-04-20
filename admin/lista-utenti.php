<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla permessi
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit();
} elseif($_SESSION['utente']['livello'] != 1) {
    die('Non hai i permessi per accedere a questa pagina.');
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Lista utenti | Mensa');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Utenti</h1>
            <a class="ms-3" href="aggiungi-utente.php">
                <button class="btn btn-outline-dark fs-6 fw-bold">Aggiungi nuovo</button>
            </a>
        </div>
        <input id="ricerca" type="text" class="form-control search-input" placeholder="Cerca utente">
    </div>
    <?php
    // Query per ottenere gli utenti
    $query = "SELECT nome, cognome, username, email, telefono, codice_fiscale, id_utente FROM utenti";
    $statement = $mysqli->prepare($query);
    // Esegui la query
    if ($statement->execute()) {
        $statement->store_result();
        // Controlla se ci sono risultati
        if ($statement->num_rows == 0) {
            echo '<div class="alert alert-warning mt-3" role="alert">Nessun utente trovato.</div>';
            exit;
        } else {
            $statement->bind_result($nome, $cognome, $username, $email, $telefono, $codice_fiscale, $idUtente);
        ?>
            <table class="table table-view table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 20%">Nome e cognome</th>
                        <th scope="col" style="width: 20%">Nome utente</th>
                        <th scope="col" style="width: 20%">Email</th>
                        <th scope="col" style="width: 20%">Telefono</th>
                        <th scope="col" style="width: 20%">Codice Fiscale</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($statement->fetch()) {
                    ?>
                        <tr>
                            <td>
                                <a class="link-primary table-link" href="modifica-utente.php?id=<?php echo $idUtente; ?>"><?php echo $nome . ' ' . $cognome; ?></a>
                            </td>
                            <td><?php echo $username; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $telefono; ?></td>
                            <td><?php echo $codice_fiscale; ?></td>
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
