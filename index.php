<?php

// Importa il file di caricamento
require_once 'load.php';

// Ottieni livello utente
if(isset($_SESSION['utente'])) {
    $livelloUtente = $_SESSION['utente']['livello'];
} else {
    $livelloUtente = 0;
}

// Controlla ruolo
controllaRuoloUtente($livelloUtente);
