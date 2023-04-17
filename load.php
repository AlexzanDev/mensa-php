<?php

// Avvia la sessione
session_start();

// Ottieni il nome della cartella "root" del progetto -- https://www.php.net/manual/en/function.basename.php#121405
function mb_basename($path) {
    if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    } else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    }
    return '';
}

// Ottieni il nome della cartella da cui viene richiamato il file
$nomeCartella = mb_basename(getcwd());

// Definisci i path assoluti per richiamare i file
if(!defined('ABSPATH')) {
    // Controlla se il file viene chiamato dalla cartella magazzino o mensa, in quel caso modifica l'ABSPATH
    if($nomeCartella == 'magazzino' || $nomeCartella == 'mensa' || $nomeCartella == 'admin') {
        define('ABSPATH', '..');
        define('MAGAZZINO', '../magazzino');
        define('MENSA', '../mensa');
        define('ADMIN', '../admin');
    } else {
        define('ABSPATH', '.');
        define('MAGAZZINO', './magazzino');
        define('MENSA', './mensa');
        define('ADMIN', './admin');
    }
}

// Controlla se il file di configurazione esiste
if(!file_exists(ABSPATH . '/config.php')) {
    die('File di configurazione non trovato.');
} else {
    // Includo il file per la connessione al database
    require_once ABSPATH . '/config.php';
}

// Controlla se la connessione al database è stata stabilita
function controllaConnessioneDB() {
    $connessione = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Se la connessione non è stata stabilita, mostra errore
    if (!$connessione) {
        die("Connessione fallita: " . mysqli_connect_error());
    } else {
        return $connessione;
    }
    mysqli_close($connessione);
}
$mysqli = controllaConnessioneDB();

// Reindirizza al setup se non esiste un utente nel database
function controllaUtenti($mysqli) {
    $query = "SELECT * FROM utenti";
    $statement = $mysqli->prepare($query);
    if($statement->execute()) {
        $statement->store_result();
        if($statement->num_rows == 0) {
            header('Location: ' . ABSPATH . '/setup.php');
        }
    }
    $statement->close();
}
if(!isset($_SESSION['utente']) && basename($_SERVER['PHP_SELF']) != 'setup.php') {
    controllaUtenti($mysqli);
}

// Reindirizza utente in base al ruolo
function controllaRuoloUtente($ruolo) {
    switch($ruolo):
        case 1:
            header('Location: ' . ADMIN . '/index.php');
            break;
        case 2:
            header('Location: ' . MAGAZZINO . '/index.php');
            break;
        case 3:
        case 4:
            header('Location: ' . MENSA . '/index.php');
            break;
        case 0:
            header('Location: ' . ABSPATH . '/login.php');
            break;
    endswitch;
}
