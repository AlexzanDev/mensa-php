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

// Controllo password
function controlloPassword($password, $passwordVerifica) {
    if($password === $passwordVerifica) {
        return true;
    } else {
        return false;
    }
}

// Gestione del ripristino della password
if(isset($_POST['reimpostaBtn'])) {
    $passwordModificata = false;
    $utenteTrovato = false;
    $username = $_POST['username'];
    $password = $_POST['nuovaPassword'];
    $passwordVerifica = $_POST['nuovaPasswordVerifica'];
    // Controlla se le password inserite sono uguali
    if(controlloPassword($password, $passwordVerifica)) {
        // Cerca l'utente
        $search_query = "SELECT username FROM utenti WHERE (username = ?)";
        $search_statement = $mysqli->prepare($search_query);
        $search_statement->bind_param("s", $username);
        if($search_statement->execute()) {
            $search_statement->store_result();
            // Se la ricerca non restituisce risultati, allora mostra errore e non andare avanti
            if($search_statement->num_rows() == 0) {
                $utenteTrovato = false;
                $messaggioRipristino = '<div class="alert alert-danger" role="alert">L\'utente non esiste.</div>';
            } else {            
                $utenteTrovato = true;
                $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash della password
                $query = "UPDATE utenti SET password = ? WHERE (username = ?)"; // Creazione query
                $statement = $mysqli->prepare($query); // Preparazione query per esecuzione
                $statement->bind_param("ss", $passwordHash, $username); // Associazione parametri
                if($statement->execute()) {
                    $passwordModificata = true;
                    $messaggioConferma = '<div class="alert alert-success" role="alert">La password è stata reimpostata con successo.</div>';
                    $statement->close();
                } else {
                    $messaggioRipristino = '<div class="alert alert-danger" role="alert">Si è verificato un errore durante il ripristino della password.</div>';
                }
            }
            $search_statement->close();
        }
    } else {
        $messaggioRipristino = '<div class="alert alert-danger" role="alert">Le password inserite non corrispondono.</div>';
    }
}

// Carica l'head
require_once 'head.php';
mensaHead('Ripristina la password');
?>

<div class="vh-100 login-container d-flex justify-content-center align-items-center">
    <div class="login-wrapper">
        <h1 class="login-titolo">Ripristina la password</h1>
        <?php
            if(isset($messaggioConferma)) {
                echo $messaggioConferma;
            }
            if(isset($messaggioRipristino)) {
                echo $messaggioRipristino;
            }
        ?>
        <form method="post" class="login-form">
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="username">Nome utente</label>
                <input type="text" name="username" id="username" class="form-control login-form-input" placeholder="Nome utente" required>
            </div>          
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="password">Nuova password</label>
                <div class="login-password-container">
                    <input type="password" name="nuovaPassword" id="password" class="form-control login-form-input" placeholder="Nuova password" required>
                    <i id="password-eye" class="fa-solid fa-eye login-password-eye"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="mb-2 fw-bold" for="password">Conferma la nuova password</label>
                <div class="login-password-container">
                    <input type="password" name="nuovaPasswordVerifica" id="password" class="form-control login-form-input" placeholder="Conferma password" required>
                    <i id="password-eye" class="fa-solid fa-eye login-password-eye"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <button name="reimpostaBtn" type="submit" class="btn btn-primary w-100 login-button">Reimposta la password</button>
            </div>
        </form>
        <div class="page-navigation">
            <a class="login-text" href="login.php">Torna al login</a>
        </div>
    </div>
</div>

<?php

// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
