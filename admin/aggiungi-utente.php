<?php

// Importa il file di caricamento
require_once '../load.php';

// Controlla permessi
if(!isset($_SESSION['utente'])) {
    header('Location: ' . ABSPATH . '/login.php');
    exit();
} elseif($_SESSION['utente']['livello'] != 1) {
    die('Non hai i permessi per accedere a questa pagina.');
}

// Gestisci il form
if(isset($_POST['addBtn'])) {
    // Ottieni i dati
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $indirizzo = $_POST['indirizzo'];
    $telefono = $_POST['telefono'];
    $codice_fiscale = $_POST['codice_fiscale'];
    $dataNascita = $_POST['dataNascita'];
    $ruolo = $_POST['ruolo'];
    // Controlla se l'email o lo username esistono già
    $queryRicerca = "SELECT * FROM utenti WHERE email = ? OR username = ?";
    $statementRicerca = $mysqli->prepare($queryRicerca);
    $statementRicerca->bind_param('ss', $email, $username);
    if($statementRicerca->execute()) {
        $statementRicerca->store_result();
        if($statementRicerca->num_rows > 0) {
            $messaggio = '<div class="alert alert-danger mt-3" role="alert">L\'email o lo username inseriti esistono già.</div>';
            $statementRicerca->close();
        } else {
            // Inserisci l'utente nel database
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $nuovaData = date_create_from_format("d/m/Y", $dataNascita); // Converti data
            $dataNascita = date_format($nuovaData, "Ymd"); // Genera data da inserire nel database
            // Query per aggiungere l'ingrediente
            $query = "INSERT INTO utenti (nome, cognome, username, email, password, indirizzo, telefono, codice_fiscale, data_nascita, livello) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $mysqli->prepare($query);
            $statement->bind_param('sssssssssi', $nome, $cognome, $username, $email, $password_hash, $indirizzo, $telefono, $codice_fiscale, $dataNascita, $ruolo);
            // Esegui la query
            if($statement->execute()) {
                $idUtente = $statement->insert_id;
                $messaggio = '<div class="alert alert-success mt-3" role="alert">Utente aggiunto con successo.</div>';
                $_SESSION['messaggio'] = $messaggio;
                header('Location: modifica-utente.php?id=' . $idUtente . '');
            } else {
                $messaggio = '<div class="alert alert-danger mt-3" role="alert">Errore durante l\'aggiunta dell\'utente.</div>';
            }
            $statement->close();
        }
    }
}

// Carica l'head e l'header
require_once '../head.php';
mensaHead('Aggiungi utente | Mensa');
require_once ABSPATH . '/layout/components/header.php';
?>
<div class="container mt-3">
    <div class="heading-view">
        <div class="heading-view-title">
            <h1>Aggiungi utente</h1>
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
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="cognome">Cognome</label>
                    <input type="text" class="form-control mt-2" id="cognome" name="cognome" placeholder="Cognome" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il cognome dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="username">Nome utente</label>
                    <input type="text" class="form-control mt-2" id="username" name="username" placeholder="Nome utente" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il nome utente dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="email">Email</label>
                    <input type="email" class="form-control mt-2" id="email" name="email" placeholder="Email" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci l'email dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="password">Password</label>
                    <div class="login-password-container">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Inserisci la password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                        <i id="password-eye" class="fa-solid fa-eye login-password-eye"></i>
                    </div>
                    <p class="edit-form-text text-muted mt-2">Inserisci la password dell'utente. La password deve contenere almeno 8 caratteri, un numero, una lettera maiuscola e una minuscola.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="indirizzo">Indirizzo</label>
                    <input type="text" class="form-control mt-2" id="indirizzo" name="indirizzo" placeholder="Indirizzo" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci l'indirizzo dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="telefono">Telefono</label>
                    <input type="text" class="form-control mt-2" id="telefono" name="telefono" placeholder="Telefono" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il telefono dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="codice_fiscale">Codice fiscale</label>
                    <input type="text" class="form-control mt-2" id="codice_fiscale" name="codice_fiscale" placeholder="Codice Fiscale" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci il codice fiscale dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="mb-2 fw-bold" for="dataNascita">Data di nascita</label>
                    <input type="text" name="dataNascita" id="dataNascita" class="form-control" placeholder="Inserisci la data di nascita" required>
                    <p class="edit-form-text text-muted mt-2">Inserisci la data di nascita dell'utente.</p>
                    <div class="edit-form-disclaimer mt-2">
                        <i class="fa-solid fa-circle-exclamation" style="color: #ff0000;"></i>
                        <p class="edit-form-text ms-1 text-danger">Campo richiesto.</p>
                    </div>
                </div>
                <div class="edit-form-group mt-4">
                    <label class="fw-bold mb-2" for="ruolo">Ruolo</label>
                    <select class="form-select mt-2" id="ruolo" name="ruolo" required>
                        <option value="1">Amministratore</option>
                        <option value="2">Magazziniere</option>
                        <option value="3">Cuoco</option>
                        <option value="4">Food Manager</option>
                        <option value="5">Utente normale</option>
                        <option value="6">Disabilitato</option>
                    </select>
                    <p class="edit-form-text text-muted mt-2">Seleziona il ruolo dell'utente.</p>
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

<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
