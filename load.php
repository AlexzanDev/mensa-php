<?php

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
    if($nomeCartella == 'magazzino' || $nomeCartella == 'mensa') {
        define('ABSPATH', '..');
        define('MAGAZZINO', '../magazzino');
        define('MENSA', '../mensa');
    }
    else {
        define('ABSPATH', '.');
        define('MAGAZZINO', './magazzino');
        define('MENSA', './mensa');
    }
}

// Controlla se il file di configurazione esiste
if(!file_exists(ABSPATH . '/config.php')) {
    die('File di configurazione non trovato.');
}
else {
    // Includo il file per la connessione al database
    require_once ABSPATH . '/config.php';
}

// Controlla se la connessione al database è stata stabilita
function controllaConnessioneDB() {
    $connessione = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Se la connessione non è stata stabilita, mostra errore
    if (!$connessione) {
        die("Connessione fallita: " . mysqli_connect_error());
    }
    mysqli_close($connessione);
}
controllaConnessioneDB();

// Carica tutti gli assets
function caricaAssets() {
    $bootstrapCSS = ABSPATH . '/assets/css/bootstrap.css';
    $style = ABSPATH . '/assets/css/style.css';
    $jquery = ABSPATH . '/assets/js/jquery.js';
    $sitoScript = ABSPATH . '/assets/js/sito.js';

    return array($bootstrapCSS, $style, $jquery, $sitoScript);
}
caricaAssets();

// Genera head della pagina
function mensaHead($titolo) {
    echo '<!DOCTYPE html>
    <html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . $titolo . '</title>
        <link rel="stylesheet" href="' . caricaAssets()[0] . '">
        <link rel="stylesheet" href="' . caricaAssets()[1] . '">
    </head>
    <body>';
}
