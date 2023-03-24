<?php

// Importa il file di caricamento
require_once 'load.php';

// Controlla se l'utente è loggato
function controlloLogin() {
    if (isset($_SESSION['utente'])) {
        return true;
    } else {
        return false;
    }
}

// Se l'utente è già loggato, reindirizza alla pagina principale
if (controlloLogin()) {
    header('Location: ' . 'index.php');
    exit;
}

// Gestione del login
if(isset($_POST['loginBtn'])) {
    $accessoEffettuato = false;
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $query = "SELECT id_utente, nome, cognome, username, password, livello FROM utente WHERE (email = ?)"; // Creazione query
    $statement = $mysqli->prepare($query); // Preparazione query per esecuzione
    $statement->bind_param("s", $email); // Associazione parametri
    $statement->execute(); // Esecuzione query
    if($statement->execute()) {
        $statement->store_result(); // Memorizza il risultato della query
        $statement->bind_result($id_utente, $nome, $cognome, $username, $password_hash, $livello); // Associa i risultati della query alle variabili
        while($statement->fetch()) {
            // Verifica la password
            if(password_verify($password, $password_hash)) {
                $_SESSION['utente'] = array(
                    'id_utente' => $id_utente,
                    'nome' => $nome,
                    'cognome' => $cognome,
                    'username' => $username,
                    'livello' => $livello
                );
                $accessoEffettuato = true;
                // Reindirizza alla pagina principale
                header('Location: ' . 'index.php');
                exit;
            }
        }
    } else {
        $messaggioLogin = '<div class="alert alert-danger" role="alert">Si è verificato un errore durante l\'accesso.</div>';
    }
    // Chiudi la connessione
    $statement->close();
    // Mostra errore se le credenziali non sono corrette
    if(!$accessoEffettuato) {
        $messaggioLogin = '<div class="alert alert-danger" role="alert">Le credenziali inserite non sono corrette.</div>';
    }
}

// Carica l'head
mensaHead('Login');

?>

<div class="vh-100 login-container d-flex justify-content-center align-items-center">
    <div class="login-wrapper">
        <h1 class="login-titolo">Effettua l'accesso</h1>
        <?php
        // Mostra il messaggio di errore, se presente
        if(isset($messaggioLogin)) { 
            echo $messaggioLogin; 
        } 
        ?>
        <form method="post" class="login-form">
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control login-form-input" placeholder="indirizzo@mail.it" required>
            </div>
            <div class="form-group mb-4">
                <div class="d-flex justify-content-between">
                    <label class="mb-2 fw-bold" for="password">Password</label>
                    <a href="reset-password.php" class="login-forgot">Hai dimenticato la password?</a>
                </div>
                <div class="login-password-container">
                    <input type="password" name="password" id="password" class="form-control login-form-input" placeholder="Inserisci la password" required>
                    <i id="password-eye" class="fa-solid fa-eye login-password-eye"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <button name="loginBtn" type="submit" class="btn btn-primary w-100 login-button">Login</button>
            </div>
        </form>
    </div>
</div>

<?php

// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
