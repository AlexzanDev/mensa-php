<?php

// Controlla se il file è stato richiamato direttamente
if (!defined('ABSPATH')) {
    die('Non puoi accedere a questo file.');
}

// Carica tutti gli assets
function caricaAssets() {
    $bootstrapCSS = ABSPATH . '/assets/css/bootstrap.css';
    $style = ABSPATH . '/assets/css/style.css';
    $jquery = ABSPATH . '/assets/js/jquery.js';
    $sitoScript = ABSPATH . '/assets/js/sito.js';
    $fontAwesome = ABSPATH . '/assets/vendor/fontawesome/css/fontawesome-all.css';
    $jqueryUiCSS = ABSPATH . '/assets/vendor/jquery-ui/jquery-ui.min.css';
    $jqueryUiJS = ABSPATH . '/assets/vendor/jquery-ui/jquery-ui.min.js';

    return array($bootstrapCSS, $style, $jquery, $sitoScript, $fontAwesome, $jqueryUiCSS, $jqueryUiJS);
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
        <link rel="stylesheet" href="' . caricaAssets()[4] . '">
        <link rel="stylesheet" href="' . caricaAssets()[5] . '">
    </head>
    <body>';
}
