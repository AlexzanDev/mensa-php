<?php

// Importa il file di caricamento
require_once 'load.php';

// Controlla se esiste già un utente amministratore
$query = "SELECT id_utente FROM utenti WHERE (livello = 1)";
$statement = $mysqli->prepare($query);
if($statement->execute()) {
    $statement->store_result();
    // Se esiste già un utente amministratore, reindirizza alla pagina di login
    if($statement->num_rows > 0) {
        die('Setup concluso. <a href="login.php">Accedi</a>');
    }
}

// Gestisci creazione utente amministratore
if(isset($_POST['setupBtn'])) {
    // Ottieni i dati
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordVerifica = $_POST['passwordVerifica'];
    $ruolo = 1;
    $indirizzo = $_POST['indirizzo'];
    $numeroTelefono = $_POST['telefono'];
    $codiceFiscale = $_POST['codiceFiscale'];
    $dataNascita = $_POST['dataNascita'];
    $nuovaData = date_create_from_format("d/m/Y", $dataNascita); // Converti data
    $dataNascita = date_format($nuovaData, "Ymd"); // Genera data da inserire nel database
    // Controlla se le password corrispondono
    if($password != $passwordVerifica) {
        $messaggio = '<div class="alert alert-danger" role="alert">Le password non corrispondono.</div>';
    } else {
        // Crea l'utente
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO utenti (nome, cognome, username, email, password, indirizzo, telefono, codice_fiscale, data_nascita, livello) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $mysqli->prepare($query);
        $statement->bind_param("sssssssssi", $nome, $cognome, $username, $email, $password, $indirizzo, $numeroTelefono, $codiceFiscale, $dataNascita, $ruolo);
        if($statement->execute()) {
            // Se l'utente è stato creato, reindirizza alla pagina di login
            $messaggio = '<div class="alert alert-success mt-3" role="alert">Utente amministratore creato con successo. Effettua l\'accesso.</div>';
            $_SESSION['messaggio'] = $messaggio;    
            header('Location: login.php');
            exit;
        } else {
            $messaggio = '<div class="alert alert-danger" role="alert">Errore durante la creazione dell\'utente.</div>';
        }
    }
}

// Carica l'head
require_once 'head.php';
mensaHead('Setup');
?>

<div class="vh-100 login-container d-flex justify-content-center align-items-center pt-3 pb-3">
    <div class="login-wrapper">
        <h1 class="login-titolo">Crea un utente amministratore</h1>
        <?php
        // Mostra un messaggio, se presente
        if(isset($messaggio)) { 
            echo $messaggio; 
        } 
        ?>
        <div class="alert alert-primary" role="alert">
            Per poter utilizzare la mensa, è necessario almeno un utente amministratore.
        </div>
        <form method="post" class="login-form">
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="nome">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control login-form-input" placeholder="Inserisci il nome" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="cognome">Cognome</label>
                <input type="text" name="cognome" id="cognome" class="form-control login-form-input" placeholder="Inserisci il cognome" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control login-form-input" placeholder="Inserisci uno username" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control login-form-input" placeholder="indirizzo@mail.it" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="password">Password</label>
                <div class="login-password-container">
                    <input type="password" name="password" id="password" class="form-control login-form-input" placeholder="Inserisci la password" required>
                    <i id="password-eye" class="fa-solid fa-eye login-password-eye"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="passwordVerifica">Conferma la nuova password</label>
                <div class="login-password-container">
                    <input type="password" name="passwordVerifica" id="passwordVerifica" class="form-control login-form-input" placeholder="Conferma password" required>
                    <i id="verifica-password-eye" class="fa-solid fa-eye login-password-eye"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="indirizzo">Indirizzo</label>
                <input type="text" name="indirizzo" id="indirizzo" class="form-control login-form-input" placeholder="Inserisci l'indirizzo" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="telefono">Numero di telefono</label>
                <input type="text" name="telefono" id="telefono" class="form-control login-form-input" placeholder="Inserisci il numero di telefono" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="codiceFiscale">Codice fiscale</label>
                <input type="text" name="codiceFiscale" id="codiceFiscale" class="form-control login-form-input" placeholder="Inserisci il codice fiscale" required>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="dataNascita">Data di nascita</label>
                <input type="text" name="dataNascita" id="dataNascita" class="form-control login-form-input" placeholder="Inserisci la data di nascita" required>
            </div>
            <div class="form-group mb-4">
                <button name="setupBtn" type="submit" class="btn btn-primary w-100 login-button">Crea utente</button>
            </div>
        </form>
    </div>
</div>

<?php

// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
