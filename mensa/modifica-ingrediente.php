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

// Controlla se un ID viene passato o meno
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID ingrediente non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste un ingrediente con questo ID
    $idIngrediente = $_GET['id'];
    $checkIngrediente = true;
    $query = "SELECT id_ingrediente, nome, descrizione, unita_misura, ultima_modifica, id_utente FROM ingredienti WHERE (id_ingrediente = ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idIngrediente);
    if($statement->execute()) {
        $statement->store_result();
        // Se non esiste un ingrediente con questo ID, mostra un messaggio di errore
        if($statement->num_rows == 0) {
            echo 'Non esiste un ingrediente con questo ID.';
            $checkIngrediente = false;
            exit();
        } else {
            // Se esiste un ingrediente con questo ID, salva i dati
            $statement->bind_result($idIngrediente, $nome, $descrizione, $unitaMisura, $ultimaModifica, $idUtente); // Abbina i risultati della query alle variabili
            $statement->fetch(); // Estrae i risultati dalla query
            $queryUtente = "SELECT nome, cognome FROM utenti WHERE (id_utente = ?)";
            $statementUtente = $mysqli->prepare($queryUtente);
            $statementUtente->bind_param("i", $idUtente);
            if($statementUtente->execute()) {
                $statementUtente->store_result();
                $statementUtente->bind_result($nomeUtente, $cognomeUtente);
                $statementUtente->fetch();
            } else {
                echo 'Si è verificato un errore.';
            }
            $statement->close();
        }
    } else {
        echo 'Si è verificato un errore.';
    }
    // Mostra messaggio di aggiunta, se il redirect è avvenuto correttamente
    if(!empty($_SESSION['messaggio'])) {
        $messaggio = $_SESSION['messaggio'];
        unset($_SESSION['messaggio']);
    }
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Modifica ' . $nome . ' | Mensa');
require_once ABSPATH . '/layout/components/header.php';

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $nome = $_POST['nome'];
    $descrizione = $_POST['descrizione'];
    $unitaMisura = $_POST['unita-misura'];
    $modificaTempo = date('Y-m-d H:i:s');
    // Query per aggiungere l'ingrediente
    $query = "UPDATE ingredienti SET nome = ?, descrizione = ?, unita_misura = ?, ultima_modifica = ? WHERE id_ingrediente = ?";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ssssi', $nome, $descrizione, $unitaMisura, $modificaTempo, $idIngrediente);
    // Esegui la query
    if($statement->execute()) {
        $messaggio = '<div class="alert alert-success mt-3" role="alert">Ingrediente modificato con successo. <a href="visualizza-ingrediente.php?id=' . $idIngrediente . '">Visualizza ingrediente</a>.</div>';
        $ultimaModifica = $modificaTempo;
    } else {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante la modifica dell\'ingrediente.</div>';
    }
    $statement->close();
}
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Modifica <?php echo $nome; ?></h1>
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
                    <input type="text" class="form-control mt-2" id="nome" name="nome" placeholder="Nome" required value="<?php echo $nome; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome dell'ingrediente.</p>
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
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="unita-misura">Unità di misura</label>
                    <select class="form-select mt-2" id="unita-misura" name="unita-misura" required>
                        <option value="g" <?php if($unitaMisura == 'g') { echo 'selected'; } ?>>Grammi</option>
                        <option value="kg" <?php if($unitaMisura == 'kg') { echo 'selected'; } ?>>Chilogrammi</option>
                        <option value="l" <?php if($unitaMisura == 'l') { echo 'selected'; } ?>>Litri</option>
                        <option value="ml" <?php if($unitaMisura == 'ml') { echo 'selected'; } ?>>Millilitri</option>
                        <option value="pz" <?php if($unitaMisura == 'pz') { echo 'selected'; } ?>>Pezzi</option>
                    </select>
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
        <div class="edit-form-properties">
            <div class="edit-form-properties-group">
                <span class="edit-form-text mt-2">Ultima modifica: <b><?php echo date("d/m/Y - H:i", strtotime($ultimaModifica)); ?></b></span>
                <span class="edit-form-text mt-2">Autore: <b><?php echo $nomeUtente . ' ' . $cognomeUtente; ?></b></span>
            </div>
        </div>
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
