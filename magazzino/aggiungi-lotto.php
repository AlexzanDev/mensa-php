<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla i permessi
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit;
} elseif($_SESSION['utente']['livello'] != 1 && $_SESSION['utente']['livello'] != 2) { 
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
    if(isset($_POST['ingrediente'])) {
        $idIngrediente = $_POST['ingrediente'];
    }
    $descrizione = $_POST['descrizione'];
    $quantita = $_POST['quantita'];
    $prezzo = $_POST['prezzo'];
    $dataScadenza = $_POST['dataScadenza'];
    $ultimaModifica = date('Y-m-d H:i:s');
    $nuovaData = date_create_from_format("d/m/Y", $dataScadenza); // Converti data
    $dataScadenza = date_format($nuovaData, "Ymd"); // Genera data da inserire nel database
    $idUtente = $_SESSION['utente']['id_utente'];
    $stato = 1;
    if(empty($idIngrediente) || empty($quantita) || empty($prezzo) || empty($dataScadenza)) {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Compila tutti i campi.</div>';
    } else {
        // Query per aggiungere il lotto
        $query = "INSERT INTO magazzino (id_ingrediente, descrizione, data_scadenza, prezzo, quantita, stato, ultima_modifica, id_utente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $mysqli->prepare($query);
        $statement->bind_param('issdiisi', $idIngrediente, $descrizione, $dataScadenza, $prezzo, $quantita, $stato, $ultimaModifica, $idUtente);
        // Esegui la query
        if($statement->execute()) {
            $idLotto = $statement->insert_id;
            $messaggio = '<div class="alert alert-success mt-3" role="alert">Lotto aggiunto con successo.</div>';
            $_SESSION['messaggio'] = $messaggio;
            header('Location: modifica-lotto.php?id=' . $idLotto . '');
        } else {
            $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante l\'aggiunta del lotto.</div>';
        }
        $statement->close();
    }
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Aggiungi lotto | Magazzino');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Aggiungi Lotto</h1>
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
                <div class="edit-form-group ingredienti-group">
                    <label class="fw-bold mb-2" for="ingredienti">Ingrediente</label>
                    <div class="d-flex ingrediente-elemento">
                        <select class="form-select mt-2" id="ingredientiLotto" name="ingredientiLotto">
                            <option value="" selected disabled>Seleziona un ingrediente</option>
                            <?php
                            // Mostra gli ingredienti
                            foreach($ingredienti as $ingrediente) {
                                echo '<option value="' . $ingrediente['id_ingrediente'] . '">' . $ingrediente['nome'] . '</option>';
                            }
                            // Controlla se non ci sono ingredienti
                            if(empty($ingredienti)) {
                                echo '<option value="" disabled>Nessun ingrediente disponibile</option>';
                            }
                            ?>
                        </select>
                        <input type="number" min="1" class="form-control mt-2" id="quantita" name="quantita" placeholder="Quantità">
                        <button type="button" class="btn btn-primary mt-2" id="addIngredienteLottoBtn"><i class="fas fa-plus"></i></button>
                    </div>
                    <p class="edit-form-text text-muted mt-2">Inserisci gli ingredienti della ricetta e le quantità (in pezzi).</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="descrizione">Note</label>
                    <textarea name="descrizione" id="descrizione"></textarea>
                    <p class="edit-form-text text-muted mt-2">Inserisci delle note per il lotto.</p>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="mb-2 fw-bold" for="dataScadenza">Data di scadenza</label>
                    <input type="text" name="dataScadenza" id="dataScadenza" class="form-control" placeholder="Inserisci la data di scadenza" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci la data di scadenza del lotto.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="prezzo">Prezzo</label>
                    <input type="number" class="form-control mt-2" id="prezzo" name="prezzo" placeholder="Prezzo" required step="0.01">
                    <p class="edit-form-text text-muted mt-2">Inserisci il prezzo del lotto (in euro).</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
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
