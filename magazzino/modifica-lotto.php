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
$statementRicerca = $mysqli->prepare($query);
if ($statementRicerca->execute()) {
    $statementRicerca->store_result();
    $statementRicerca->bind_result($idIngrediente, $nome, $unitaMisura);
    $ingredienti = array();
    // Salva gli ingredienti in un array
    while ($statementRicerca->fetch()) {
        $ingrediente = array(
            'id_ingrediente' => $idIngrediente,
            'nome' => $nome,
            'unita_misura' => $unitaMisura
        );
        array_push($ingredienti, $ingrediente);
    }
}
$statementRicerca->close();

// Controlla se un ID viene passato o meno
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID lotto non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste un lotto con questo ID
    $idLotto = $_GET['id'];
    $checkLotto = true;
    $query = "SELECT id_ingrediente, descrizione, data_scadenza, prezzo, quantita, stato, ultima_modifica, id_utente FROM magazzino WHERE (id_lotto = ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idLotto);
    if($statement->execute()) {
        $statement->store_result();
        // Se non esiste un lotto con questo ID, mostra un messaggio di errore
        if($statement->num_rows == 0) {
            echo 'Non esiste un lotto con questo ID.';
            $checkLotto = false;
            exit();
        } else {
            // Se esiste un lotto con questo ID, salva i dati
            $statement->bind_result($idIngrediente, $descrizione, $dataScadenza, $prezzo, $quantita, $stato, $ultimaModifica, $idUtente);
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
    $idIngrediente = $_POST['ingrediente'];
    $descrizione = $_POST['descrizione'];
    $quantita = $_POST['quantita'];
    $prezzo = $_POST['prezzo'];
    $dataScadenza = $_POST['dataScadenza'];
    $ultimaModifica = date('Y-m-d H:i:s');
    $nuovaData = date_create_from_format("d/m/Y", $dataScadenza); // Converti data
    $dataScadenza = date_format($nuovaData, "Ymd"); // Genera data da inserire nel database
    $idUtente = $_SESSION['utente']['id_utente'];
    $stato = $stato;
    // Controlla se i campi obbligatori sono vuoti
    if(empty($idIngrediente) || empty($quantita) || empty($prezzo) || empty($dataScadenza)) {
        $messaggio = '<div class="alert alert-danger mt-3" role="alert">Compila tutti i campi.</div>';
    } else {
        // Aggiorna il lotto
        $query = "UPDATE magazzino SET id_ingrediente = ?, descrizione = ?, data_scadenza = ?, prezzo = ?, quantita = ?, stato = ?, ultima_modifica = ?, id_utente = ? WHERE (id_lotto = ?)";
        $statement = $mysqli->prepare($query);
        $statement->bind_param("isssssssi", $idIngrediente, $descrizione, $dataScadenza, $prezzo, $quantita, $stato, $ultimaModifica, $idUtente, $idLotto);
        if($statement->execute()) {
            $idLotto = $statement->insert_id;
            $messaggio = '<div class="alert alert-success mt-3" role="alert">Lotto modificato con successo.</div>';
        } else {
            $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante la modifica del lotto.</div>';
        }
        $statement->close();
    }
}

// Ottieni gli ingredienti del lotto
$queryIngredienti = "SELECT nome FROM ingredienti WHERE (id_ingrediente = ?)";
$statementIngredienti = $mysqli->prepare($queryIngredienti);
$statementIngredienti->bind_param("i", $idIngrediente);
if($statementIngredienti->execute()) {
    $statementIngredienti->store_result();
    $statementIngredienti->bind_result($nomeIngrediente);
    $statementIngredienti->fetch();
}
$statementIngredienti->close();

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Modifica lotto | Magazzino');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Modifica Lotto</h1>
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
                    <?php
                    // Mostra l'ingrediente aggiunto
                    if(isset($idIngrediente)) {
                        echo '<div class="d-flex ingrediente-elemento">' . 
                            '<input type="hidden" name="ingrediente" value="' . $idIngrediente . '">'
                            . '<input type="hidden" name="quantita" value="' . $quantita . '">'
                            . '<p class="edit-form-text mt-2">' . $nomeIngrediente . ': ' . $quantita . ' pezzi</p>'
                            . '<button type="button" id="rimuoviIngredienteBtn" class="btn btn-danger mt-2"><i class="fas fa-times"></i></button></div>';
                    }
                    ?>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="descrizione">Note</label>
                    <textarea name="descrizione" id="descrizione"><?php echo $descrizione; ?></textarea>
                    <p class="edit-form-text text-muted mt-2">Inserisci delle note per il lotto.</p>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="mb-2 fw-bold" for="dataScadenza">Data di scadenza</label>
                    <input type="text" name="dataScadenza" id="dataScadenza" class="form-control" placeholder="Inserisci la data di scadenza" required value="<?php echo date("d/m/Y", strtotime($dataScadenza)); ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci la data di scadenza del lotto.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold" for="prezzo">Prezzo</label>
                    <input type="number" class="form-control mt-2" id="prezzo" name="prezzo" placeholder="Prezzo" required step="0.01" value="<?php echo $prezzo; ?>">
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
