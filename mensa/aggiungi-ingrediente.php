<?php

// Importa il file di caricamento
require_once '../load.php';
// Carica l'head e l'header
mensaHead('Aggiungi ingrediente | Mensa');
require_once ABSPATH . '/layout/components/header.php';

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $descrizione = $mysqli->real_escape_string($_POST['descrizione']);
    $unitaMisura = $mysqli->real_escape_string($_POST['unita-misura']);
    $ultimaModifica = date('Y-m-d H:i:s');
    $idUtente = $_SESSION['utente']['id_utente'];
    // Query per aggiungere l'ingrediente
    $query = "INSERT INTO ingredienti (nome, descrizione, unita_misura, ultima_modifica, id_utente) VALUES (?, ?, ?, ?, ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ssssi', $nome, $descrizione, $unitaMisura, $ultimaModifica, $idUtente);
    // Esegui la query
    if($statement->execute()) {
        $messaggio = '<div class="alert alert-success mt-3" role="alert">Ingrediente aggiunto con successo.</div>';
    } else {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante l\'aggiunta dell\'ingrediente.</div>';
    }
}
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Aggiungi ingrediente</h1>
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
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome dell'ingrediente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="descrizione">Descrizione</label>
                    <textarea name="descrizione" id="descrizione"></textarea>
                    <p class="edit-form-text text-muted mt-2">Inserisci la descrizione dell'ingrediente.</p>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="unita-misura">Unità di misura</label>
                    <input type="text" class="form-control mt-2" id="unita-misura" name="unita-misura" placeholder="Unità di misura" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci l'unità di misura dell'ingrediente.</p>
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
<!-- Embed TinyMCE -->
<script src="<?php echo ABSPATH . '/assets/vendor/tinymce/tinymce.min.js'; ?>"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '#descrizione',
        promotion: false,
        language: 'it',
        plugins: 'code, media',
        setup: function (editor) {
            editor.on('init', function () {
                console.log('TinyMCE caricato correttamente!');
            });
        }
    });
</script>
<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
