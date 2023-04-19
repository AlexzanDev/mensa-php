<?php

// Importa il file di caricamento
require_once '../load.php';
// Carica l'head e l'header
require_once '../head.php';
mensaHead('Modifica lotto | Magazzino');
require_once ABSPATH . '/layout/components/header.php';
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';

// Controlla se un ID viene passato o meno
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID ingrediente non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste un ingrediente con questo ID
    $idLotto = $_GET['id'];
    $checkIngrediente = true;
    $query = "SELECT i.nome,m.id_lotto, m.descrizione, m.data_scadenza, m.quantita,m.prezzo,m.ultima_modifica,m.stato,m.id_ingrediente FROM magazzino m,ingredienti i WHERE i.id_ingrediente=m.id_ingrediente and (id_lotto = ?);";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idLotto);
    if($statement->execute()) {
        $statement->store_result();
        // Se non esiste un ingrediente con questo ID, mostra un messaggio di errore
        if($statement->num_rows == 0) {
            echo 'Non esiste un ingrediente con questo ID.';
            $checkIngrediente = false;
            exit();
        } else {
            // Se esiste un ingrediente con questo ID, salva i dati
            $statement->bind_result($nomeIngrediente,$idLotto, $descrizione, $dataScadenza, $quantita,$prezzo,$ultimaModifica,$stato,$idUtente);
            //$statement->bind_result($nomeIngrediente, $nome, $descrizione, $unitaMisura, $ultimaModifica, $idUtente); // Abbina i risultati della query alle variabili
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

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $descrizione = $_POST['descrizione'];
    $quantita = $_POST['quantita'];
    $prezzo = $_POST['prezzo'];
    $dataScadenza=$_POST['dataScadenza'];
    $modificaTempo = date('Y-m-d H:i:s');
    // Query per aggiungere l'ingrediente
    $query = "UPDATE magazzino SET  descrizione = ?, data_scadenza = ?, quantita = ?, prezzo = ? ,ultima_modifica=?, id_utente=? WHERE id_lotto = ?";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ssiisii', $descrizione, $dataScadenza, $quantita, $prezzo, $modificaTempo,$idUtente, $idLotto);
    // Esegui la query
    if($statement->execute()) {
        $messaggio = '<div class="alert alert-success mt-3" role="alert">Lotto modificato con successo. <a href="visualizza-lotto.php?id=' . $idLotto . '">Visualizza lotto</a>.</div>';
        $ultimaModifica = $modificaTempo;
    } else {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante la modifica del lotto.</div>';
    }
    $statement->close();
}
if(isset($_POST['arcvBtn'])) {
    // Query per aggiungere l'ingrediente
    $modificaTempo = date('Y-m-d H:i:s');
    $query = "UPDATE magazzino SET stato=?, ultima_modifica=?, id_utente=? WHERE id_lotto=?;";
    $stato=0;
    $statement = $mysqli->prepare($query);
    $statement->bind_param('isii', $stato, $modificaTempo, $idUtente, $idLotto);
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
            <h1>Modifica lotto:<?php echo $idLotto; ?> (<?php echo $nomeIngrediente; ?>)</h1>
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
                    <label class="fw-bold" for="nome">Nome ingrediente</label>
                    <input type="text" class="form-control mt-2" id="nome" name="nome" placeholder="Nome" required value="<?php echo $nomeIngrediente; ?>" readonly>
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
            <div style="display: flex;">
                <div class="edit-form-footer mt-3">
                    <button type="submit" class="btn btn-primary" name="addBtn">Salva</button>
                </div>
                <div class="edit-form-footer mt-3" style="margin-left: 10px;">
                    <button type="submit" class="btn btn-danger" name="arcvBtn">Archivia</button>
                </div>
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



