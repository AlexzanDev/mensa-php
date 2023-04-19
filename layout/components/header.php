<?php

// Controlla se il file è stato richiamato direttamente
if (!defined('ABSPATH')) {
    die('Non puoi accedere a questo file.');
}

// Crea menu di navigazione
function creaMenu() {
    $menuFoodManager = array(
        'Aggiungi ricetta' => ABSPATH . '/mensa/aggiungi-ricetta.php',
        'Lista ricette' => ABSPATH . '/mensa/lista-ricette.php',
        'Aggiungi ingrediente' => ABSPATH . '/mensa/aggiungi-ingrediente.php',
        'Lista ingredienti' => ABSPATH . '/mensa/lista-ingredienti.php',
    );
    $menuCuoco = array(
        'Lista ricette' => ABSPATH . '/mensa/lista-ricette.php',
        'Lista ingredienti' => ABSPATH . '/mensa/lista-ingredienti.php',
        'Magazzino' => ABSPATH . '/magazzino/lista-lotti.php',
    );
    $menuMagazzino = array(
        'Aggiungi lotto' => ABSPATH . '/magazzino/aggiungi-lotto.php',
        'Lista lotto' => ABSPATH . '/magazzino/lista-lotti.php',
        'Archivio lotti' => ABSPATH . '/magazzino/archivio-lotti.php',
    );
    $menuAdmin = array(
        'Lista ingredienti' => ABSPATH . '/mensa/lista-ingredienti.php',
        'Lista ricette' => ABSPATH . '/mensa/lista-ricette.php',
        'Magazzino' => ABSPATH . '/magazzino/lista-lotti.php',
        'Lotti archiviati' => ABSPATH . '/magazzino/archivio-lotti.php',
        'Lista utenti' => ABSPATH . '/admin/lista-utenti.php',
    );
    return array($menuFoodManager, $menuCuoco, $menuMagazzino, $menuAdmin);
}

$sitoMenu = creaMenu();
?>

<header>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Mensa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <?php 
                        if(isset($_SESSION['utente'])) {
                            // Amministratore
                            if($_SESSION['utente']['livello'] == 1) {
                                foreach($sitoMenu[3] as $titolo => $url) {
                                    echo '<li class="nav-item"><a class="nav-link" href="' . $url . '">' . $titolo . '</a></li>';
                                }
                            // Magazziniere 
                            } else if($_SESSION['utente']['livello'] == 2) {
                                foreach($sitoMenu[2] as $titolo => $url) {
                                    echo '<li class="nav-item"><a class="nav-link" href="' . $url . '">' . $titolo . '</a></li>';
                                }
                            // Cuoco
                            } else if($_SESSION['utente']['livello'] == 3) {
                                foreach($sitoMenu[1] as $titolo => $url) {
                                    echo '<li class="nav-item"><a class="nav-link" href="' . $url . '">' . $titolo . '</a></li>';
                                }
                            // Food Manager
                            } else if($_SESSION['utente']['livello'] == 4) {
                                foreach($sitoMenu[0] as $titolo => $url) {
                                    echo '<li class="nav-item"><a class="nav-link" href="' . $url . '">' . $titolo . '</a></li>';
                                }
                            }
                            ?>
                            <li id="menuDropdown" class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"><?php echo $_SESSION['utente']['nome'] . ' ' . $_SESSION['utente']['cognome']; ?></a>
                                <ul id="dropdown" class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo ABSPATH . '/admin/modifica-utente.php?id=' . $_SESSION['utente']['id_utente']; ?>">Modifica utente</a></li>
                                    <?php if($_SESSION['utente']['livello'] == 1) { ?>
                                        <li><a class="dropdown-item" href="<?php echo ABSPATH . '/admin/lista-utenti.php'; ?>">Amministrazione</a></li>
                                    <?php } ?>
                                    <li><a class="dropdown-item" href="<?php echo ABSPATH . '/logout.php' ?>">Logout</a></li>
                                </ul>
                            </li>
                        <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
