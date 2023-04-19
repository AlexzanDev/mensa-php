<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla se l'utente è loggato
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit();
}

// Controlla se un ID viene passato o meno
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID utente non valido.';
    exit();
} else {
    // Se l'ID è valido, controlla se esiste un utente con questo ID
    $idUtente = $_GET['id'];
    $checkUtente = true;
    $query = "SELECT id_utente, nome, cognome, username, email, indirizzo, telefono, codice_fiscale, livello, data_nascita FROM utenti WHERE (id_utente = ?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $idUtente);
    if($statement->execute()) {
        $statement->store_result();
        // Se non esiste un utente con questo ID, mostra un messaggio di errore
        if($statement->num_rows == 0) {
            echo 'Non esiste nessun utente con questo ID.';
            $checkUtente = false;
            exit();
        } else {
            // Se esiste un utente con questo ID, salva i dati
            $statement->bind_result($idUtente, $nome, $cognome, $username, $email, $indirizzo, $telefono, $codice_fiscale, $ruolo, $dataNascita); // Abbina i risultati della query alle variabili
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

// Controlla i permessi
if($idUtente != $_SESSION['utente']['id_utente'] && $_SESSION['utente']['livello'] != 1) {
    die('Non hai i permessi per accedere a questa pagina.');
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Modifica ' . $nome . ' ' . $cognome . ' | Mensa');
require_once ABSPATH . '/layout/components/header.php';

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $indirizzo = $_POST['indirizzo'];
    $telefono = $_POST['telefono'];
    $codice_fiscale = $_POST['codice_fiscale'];
    if(isset($_POST['ruolo'])) {
        $livello = $_POST['ruolo'];
    } else {
        $livello = $_SESSION['utente']['livello'];
    }
    $dataNascita = $_POST['dataNascita'];
    // Controlla se l'email esiste già
    $queryRicerca = "SELECT * FROM utenti WHERE email = ?";
    $statementRicerca = $mysqli->prepare($queryRicerca);
    $statementRicerca->bind_param('s', $email);
    if($statementRicerca->execute()) {
        $statementRicerca->store_result();
        if($statementRicerca->num_rows > 0) {
            $messaggio = '<div class="alert alert-danger mt-3" role="alert">L\'email inserita esiste già.</div>';
            $statementRicerca->close();
        } else {
            // Modifica l'utente nel database
            $nuovaData = date_create_from_format("d/m/Y", $dataNascita); // Converti data
            $dataNascita = date_format($nuovaData, "Ymd"); // Genera data da inserire nel database
            // Query per modificare l'utente
            $query = "UPDATE utenti SET nome = ?, cognome = ?, email = ?, indirizzo = ?, telefono = ?, codice_fiscale = ?,  data_nascita = ?, livello = ? WHERE id_utente = ?";
            $statement = $mysqli->prepare($query);
            $statement->bind_param('sssssssii', $nome, $cognome, $email, $indirizzo, $telefono, $codice_fiscale, $dataNascita, $livello, $idUtente);
            // Esegui la query
            if($statement->execute()) {
                $messaggio = '<div class="alert alert-success mt-3" role="alert">Utente modificato con successo.</div>';
            } else {
                $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante la modifica dell\'utente.</div>';
            }
            $statement->close();
        }
    }
}
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Modifica <?php echo $nome . ' ' . $cognome; ?></h1>
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
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="cognome">Cognome</label>
                    <input type="text" class="form-control mt-2" id="cognome" name="cognome" placeholder="Cognome" required value="<?php echo $cognome; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci il cognome dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="username">Nome utente</label>
                    <input type="text" class="form-control mt-2" id="username" name="username" placeholder="Username" required disabled value="<?php echo $username; ?>">
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="email">Email</label>
                    <input type="email" class="form-control mt-2" id="email" name="email" placeholder="Email" required value="<?php echo $email; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci l'email dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="indirizzo">Indirizzo</label>
                    <input type="text" class="form-control mt-2" id="indirizzo" name="indirizzo" placeholder="Indirizzo" required value="<?php echo $indirizzo; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci l'indirizzo dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="telefono">Telefono</label>
                    <input type="text" class="form-control mt-2" id="telefono" name="telefono" placeholder="Telefono" required value="<?php echo $telefono; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci il telefono dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="codice_fiscale">Codice Fiscale</label>
                    <input type="text" class="form-control mt-2" id="codice_fiscale" name="codice_fiscale" placeholder="Codice Fiscale" required value="<?php echo $codice_fiscale; ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci il codice fiscale dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="mb-2 fw-bold" for="dataNascita">Data di nascita</label>
                    <input type="text" name="dataNascita" id="dataNascita" class="form-control" placeholder="Inserisci la data di nascita" required value="<?php echo date("d/m/Y", strtotime($dataNascita)); ?>">
                    <p class="edit-form-text text-muted mt-2">Inserisci la data di nascita dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <?php
                if($_SESSION['utente']['id_utente'] != $idUtente) {
                ?>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="ruolo">Ruolo</label>
                    <select class="form-select mt-2" id="ruolo" name="ruolo" required>
                        <option value="1" <?php if($ruolo == 1) { echo 'selected'; } ?>>Amministratore</option>
                        <option value="2" <?php if($ruolo == 2) { echo 'selected'; } ?>>Magazziniere</option>
                        <option value="3" <?php if($ruolo == 3) { echo 'selected'; } ?>>Cuoco</option>
                        <option value="4" <?php if($ruolo == 4) { echo 'selected'; } ?>>Food Manager</option>
                        <option value="5" <?php if($ruolo == 5) { echo 'selected'; } ?>>Utente normale</option>
                        <option value="6" <?php if($ruolo == 6) { echo 'selected'; } ?>>Disabilitato</option>
                    </select>
                    <p class="edit-form-text text-muted mt-2">Seleziona il ruolo dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="edit-form-footer mt-3">
                <button type="submit" class="btn btn-primary" name="addBtn">Salva</button>
            </div>
        </form>
    </div>
</div>

<?php

// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
