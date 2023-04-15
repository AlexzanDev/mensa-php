<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla se un ID viene passato o meno
if( !isset( $_GET['id'] ) || !is_numeric( $_GET['id'] ) ) {
    echo 'ID ingrediente non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste un ingrediente con questo ID
    $idIngrediente = $_GET['id'];
    $checkIngrediente = true;
    $query = "SELECT id_ingrediente, nome, descrizione, unita_misura, ultima_modifica, id_utente FROM ingredienti WHERE (id_ingrediente = ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idIngrediente);
    if( $statement->execute() ) {
        $statement->store_result();
        // Se non esiste un ingrediente con questo ID, mostra un messaggio di errore
        if( $statement->num_rows == 0 ) {
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
            if( $statementUtente->execute() ) {
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
}

// Carica l'head e l'header
mensaHead('Modifica ' . $nome . ' | Mensa');
require_once ABSPATH . '/layout/components/header.php';

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $descrizione = $mysqli->real_escape_string($_POST['descrizione']);
    $unitaMisura = $mysqli->real_escape_string($_POST['unita-misura']);
    $modificaTempo = date('Y-m-d H:i:s');
    // Query per aggiungere l'ingrediente
    $query = "UPDATE ingredienti SET nome = ?, descrizione = ?, unita_misura = ?, ultima_modifica = ? WHERE id_ingrediente = ?";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ssssi', $nome, $descrizione, $unitaMisura, $modificaTempo, $idIngrediente);
    // Esegui la query
    if($statement->execute()) {
        $messaggio = '<div class="alert alert-success mt-3" role="alert">Ingrediente modificato con successo.</div>';
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
                    <textarea name="descrizione" id="descrizione"></textarea>
                    <p class="edit-form-text text-muted mt-2">Inserisci la descrizione dell'ingrediente.</p>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="unita-misura">Unità di misura</label>
                    <input type="text" class="form-control mt-2" id="unita-misura" name="unita-misura" placeholder="Unità di misura" required value="<?php echo $unitaMisura; ?>">
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
                editor.setContent('<?php echo $descrizione; ?>');
            });
        }
    });
</script>
<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
