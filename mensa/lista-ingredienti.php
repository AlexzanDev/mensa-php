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
mensaHead('Lista ingredienti | Mensa');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Ingredienti</h1>
            <?php
            if($_SESSION['utente']['livello'] != 2 && $_SESSION['utente']['livello'] != 3 && $_SESSION['utente']['livello'] != 5) {
                echo '<a class="ms-3" href="aggiungi-ingrediente.php">
                        <button class="btn btn-outline-dark fs-6 fw-bold">Aggiungi nuovo</button>
                    </a>';
            }
            ?>
        </div>
        <input id="ricerca" type="text" class="form-control search-input" placeholder="Cerca ingrediente">
    </div>
    <?php
    // Query per ottenere gli ingredienti
    $query = "SELECT nome, unita_misura, id_ingrediente FROM ingredienti";
    $statement = $mysqli->prepare($query);
    // Esegui la query
    if ($statement->execute()) {
        $statement->store_result();
        // Controlla se ci sono risultati
        if ($statement->num_rows == 0) {
            echo '<div class="alert alert-warning mt-3" role="alert">Nessun ingrediente trovato.</div>';
            exit;
        } else {
            $statement->bind_result($nome, $unitaMisura, $idIngrediente);
        ?>
            <table class="table table-view table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 33.333%">Nome</th>
                        <th scope="col" style="width: 33.333%">Unità di misura</th>
                        <th scope="col" style="width: 33.333%">Quantità in magazzino</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($statement->fetch()) {
                    ?>
                        <tr>
                            <td>
                                <a class="link-primary table-link" href="visualizza-ingrediente.php?id=<?php echo $idIngrediente; ?>"><?php echo $nome; ?></a>
                                <?php
                                if($_SESSION['utente']['livello'] != 2 && $_SESSION['utente']['livello'] != 3 && $_SESSION['utente']['livello'] != 5) {
                                    echo '<a class="lista-small-text" href="modifica-ingrediente.php?id=' . $idIngrediente . '">Modifica</a>';
                                }
                                ?>
                            </td>
                            <td><?php echo $unitaMisura; ?></td>
                            <td><?php echo $idIngrediente; ?></td>
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
