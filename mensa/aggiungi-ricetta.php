<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla i permessi
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit;
} elseif($_SESSION['utente']['livello'] != 1 && $_SESSION['utente']['livello'] != 4) { 
    die('Non hai i permessi per accedere a questa pagina.');
}

// Ottieni ingredienti da database
$query = "SELECT id_ingrediente, nome, unita_misura FROM ingredienti";
$statement = $mysqli->prepare($query);
if ($statement->execute()) {
    $statement->store_result();
    $statement->bind_result($idIngrediente, $nome, $unitaMisura);
    $ingredienti = array();
    // Salva gli ingredienti in un array
    while ($statement->fetch()) {
        $ingrediente = array(
            'id_ingrediente' => $idIngrediente,
            'nome' => $nome,
            'unita_misura' => $unitaMisura
        );
        array_push($ingredienti, $ingrediente);
    }
}
$statement->close();

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    if(isset($_POST['ingredienti'])) {
        $ingredientiRicetta = $_POST['ingredienti'];
    }
    $tempoPreparazione = $_POST['tempo-preparazione'];
    $tempoCottura = $_POST['tempo-cottura'];
    $ultimaModifica = date('Y-m-d H:i:s');
    $idUtente = $_SESSION['utente']['id_utente'];
    $sommario = $_POST['sommario'];
    // Controlla se i campi obbligatori sono vuoti
    if(empty($nome) || empty($ingredientiRicetta) || empty($tempoPreparazione)) {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Compila tutti i campi.</div>';
    } else {
        // Controlla se la ricetta esiste già
        $query = "SELECT id_ricetta FROM ricette WHERE nome = ?";
        $statement = $mysqli->prepare($query);
        $statement->bind_param('s', $nome);
        if ($statement->execute()) {
            $statement->store_result();
            if($statement->num_rows > 0) {
                $messaggio = '<div class="alert alert-danger mt-3" role="alert">La ricetta esiste già.</div>';
            } else {
                // Inserisci la ricetta nel database
                $query = "INSERT INTO ricette (nome, descrizione, tempo_preparazione, tempo_cottura, ultima_modifica, sommario, id_utente) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $statement = $mysqli->prepare($query);
                $statement->bind_param('ssiissi', $nome, $descrizione, $tempoPreparazione, $tempoCottura, $ultimaModifica, $sommario, $idUtente);
                if($statement->execute()) {
                    // Ottieni l'ID della ricetta
                    $idRicetta = $mysqli->insert_id;
                    // Controlla gli ingredienti
                    foreach($ingredientiRicetta as $ingrediente) {
                        $idIngrediente = $ingrediente;
                        $quantita = $_POST['quantita-' . $idIngrediente];
                        // Inserisci la correlazione tra ricetta e ingrediente nel database
                        $query = "INSERT INTO correlazioniir (quantita, id_ricetta, id_ingrediente) VALUES (?, ?, ?)";
                        $statement = $mysqli->prepare($query);
                        $statement->bind_param('iii', $quantita, $idRicetta, $idIngrediente);
                        $statement->execute();
                    }
                    $messaggio = '<div class="alert alert-success mt-3" role="alert">Ricetta aggiunta con successo. <a href="visualizza-ricetta.php?id=' . $idRicetta . '">Visualizza ricetta</a>.</div>';
                    $_SESSION['messaggio'] = $messaggio;
                    header('Location: modifica-ricetta.php?id=' . $idRicetta . '');    
                } else {
                    $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante l\'aggiunta della ricetta.</div>';
                }
                $statement->close();
            }
        }
    }
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Aggiungi ricetta | Mensa');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Aggiungi ricetta</h1>
        </div>
    </div>
    <?php
    if(isset($messaggio)) {
        echo $messaggio;
    }
    ?>
    <div class="edit-container mt-3">
        <form class="edit-form" method="POST">
            <div class="edit-form-content">
                <div class="edit-form-group">
                    <label class="fw-bold" for="nome">Nome</label>
                    <input type="text" class="form-control mt-2" id="nome" name="nome" placeholder="Nome" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome della ricetta.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="descrizione">Descrizione</label>
                    <textarea name="descrizione" id="descrizione"></textarea>
                    <p class="edit-form-text text-muted mt-2">Inserisci la descrizione della ricetta.</p>
                </div>
                <div class="edit-form-group ingredienti-group mt-4">
                    <label class="fw-bold mb-2" for="ingredienti">Ingredienti</label>
                    <div class="d-flex ingrediente-elemento">
                        <select class="form-select mt-2" id="ingredienti" name="ingredienti">
                            <option value="" selected disabled>Seleziona un ingrediente</option>
                            <?php
                            // Mostra gli ingredienti
                            foreach($ingredienti as $ingrediente) {
                                echo '<option value="' . $ingrediente['id_ingrediente'] . '">' . $ingrediente['nome'] . ' (' . $ingrediente['unita_misura'] . ')</option>';
                            }
                            // Controlla se non ci sono ingredienti
                            if(empty($ingredienti)) {
                                echo '<option value="" disabled>Nessun ingrediente disponibile</option>';
                            }
                            ?>
                        </select>
                        <input type="number" min="1" class="form-control mt-2" id="quantita" name="quantita" placeholder="Quantità">
                        <button type="button" class="btn btn-primary mt-2" id="addIngredienteBtn"><i class="fas fa-plus"></i></button>
                    </div>
                    <p class="edit-form-text text-muted mt-2">Inserisci gli ingredienti della ricetta e le quantità.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="tempo-preparazione">Tempo di preparazione</label>
                    <input type="number" min="1" class="form-control mt-2" id="tempo-preparazione" name="tempo-preparazione" placeholder="Tempo di preparazione" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il tempo di preparazione della ricetta in minuti.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="tempo-cottura">Tempo di cottura</label>
                    <input type="number" min="1" class="form-control mt-2" id="tempo-cottura" name="tempo-cottura" placeholder="Tempo di cottura">
                    <p class="edit-form-text text-muted mt-2">Inserisci il tempo di cottura della ricetta (se presente) in minuti.</p>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="sommario">Breve descrizione</label>
                    <input type="text" class="form-control mt-2" id="sommario" name="sommario" placeholder="Breve descrizione">
                    <p class="edit-form-text text-muted mt-2">Breve descrizione del piatto da mostrare nella lista delle ricette.</p>
                </div>
            </div>
            <div class="edit-form-footer mt-3">
                <button type="submit" class="btn btn-primary" name="addBtn">Salva</button>
            </div>
        </form>
    </div>
</div>
<!-- Embed CKEditor -->
<script src="<?php echo ABSPATH . '/assets/vendor/ckeditor/ckeditor.js'; ?>"></script>
<script src="<?php echo ABSPATH . '/assets/vendor/ckeditor/translations/it.js'; ?>"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#descrizione' ), {
            language: 'it',
            height: '800',
            mediaEmbed: {
                previewsInData: true
            },
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'alignment', '|', 'blockQuote', 'insertTable', 'mediaEmbed', '|', 'undo', 'redo' ],
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        defaultValue: true,
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    }
                }
            },
        } )
        .catch( error => {
            console.error( error );
        } );
</script>

<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
