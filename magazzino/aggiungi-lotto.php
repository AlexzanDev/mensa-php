<?php

// Importa il file di caricamento
require_once '../load.php';
// Carica l'head e l'header
require_once '../head.php';
mensaHead('Aggiungi lotto | Magazzino');
require_once ABSPATH . '/layout/components/header.php';
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $descrizione = $_POST['descrizione'];
    $quantita = $_POST['quantita'];
    $prezzo = $_POST['prezzo'];
    $dataScadenza=$_POST['dataScadenza'];
    $ultimaModifica = date('Y-m-d H:i:s');
    $idUtente = $_SESSION['utente']['id_utente'];
    // Query per aggiungere l'ingrediente
    $query = "INSERT INTO magazzino (id_ingrediente, descrizione, data_scadenza, prezzo,quantita,stato,ultima_modifica, id_utente) VALUES (?, ?, ?, ?, ?,?,?,?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('issiiisi', $idIngrediente, $descrizione, $dataScadenza, $prezzo,$quantita,$stato,$ultimaModifica, $idUtente);
    // Esegui la query
    if($statement->execute()) {
        $lotto = $statement->insert_id;
        $messaggio = '<div class="alert alert-success mt-3" role="alert">Lotto aggiunto con successo. <a href="visualizza-ingrediente.php?id=' . $lotto . '">Visualizza ingrediente</a>.</div>';
        $_SESSION['messaggio'] = $messaggio;
        header('Location: modifica-lotto.php?id=' . $idLotto . '');
    } else {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante l\'aggiunta del lotto.</div>';
    }
}

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
                <div class="edit-form-group">
                    <label class="fw-bold" for="nome">Scegli l'ingrediente</label>
                    <input type="text" class="form-control mt-2" id="nome" name="nome" placeholder="Nome" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome del lotto.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>

                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="descrizione">Descrizione</label>
                    <textarea name="descrizione" id="descrizione"><?php echo $descrizione; ?></textarea>
                    <p class="edit-form-text text-muted mt-2">Inserisci la descrizione dell'ingrediente.</p>
                </div>

                <div class="edit-form-group">
                    <label class="fw-bold" for="nome">Data scadenza</label>
                    <input type="date" class="form-control mt-2" id="dataScadenza" name="dataScadenza" placeholder="dataScadenza" required value="<?php echo $dataScadenza; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci la data di scadenza aggiornata.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>

                <div class="edit-form-group">
                    <label class="fw-bold" for="nome">Quantità</label>
                    <input type="number" class="form-control mt-2" id="quantita" name="quantita" placeholder="Quantita" required value="<?php echo $quantita; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci la quantità.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>

                <div class="edit-form-group">
                    <label class="fw-bold" for="prezzo">Prezzo</label>
                    <input type="number" class="form-control mt-2" id="prezzo" name="prezzo" placeholder="prezzo" required value="<?php echo $prezzo; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci il prezzo del lotto.</p>
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
