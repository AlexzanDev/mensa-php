<?php

// Controlla se il file è stato richiamato direttamente
if (!defined('ABSPATH')) {
    die('Non puoi accedere a questo file.');
}

// Crea menu di navigazione
function creaMenu() {
    $menuMensa = array(
        'Aggiungi ricetta' => ABSPATH . '/mensa/aggiungi-ricetta.php',
        'Lista ricette' => ABSPATH . '/mensa/lista-ricette.php',
        'Aggiungi ingrediente' => ABSPATH . '/mensa/aggiungi-ingrediente.php',
        'Lista ingredienti' => ABSPATH . '/mensa/lista-ingredienti.php',
    );
    $menuMagazzino = array(
        'Aggiungi lotto' => ABSPATH . '/magazzino/aggiungi-lotto.php',
        'Lista lotto' => ABSPATH . '/magazzino/lista-lotti.php',
        'Archivio lotti' => ABSPATH . '/magazzino/archivio-lotti.php',
    );
    $menuAdmin = array(
        'Mensa' => ABSPATH . '/mensa/lista-ingredienti.php',
        'Magazzino' => ABSPATH . '/magazzino/lista-lotti.php',
        'Lista utenti' => ABSPATH . '/admin/lista-utenti.php',
    );
    return array($menuMensa, $menuMagazzino, $menuAdmin);
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
                        // In base al livello dell'utente mostra il menu corrispondente
                        if (isset($_SESSION['utente']['livello'])) {
                            if ($_SESSION['utente']['livello'] == '3' || $_SESSION['utente']['livello'] == '4') {
                                foreach ($sitoMenu[0] as $key => $value) {
                                    echo '<li class="nav-item">
                                        <a class="nav-link" href="' . $value . '">' . $key . '</a>
                                    </li>';
                                }
                            } elseif ($_SESSION['utente']['livello'] == '2') {
                                foreach ($sitoMenu[1] as $key => $value) {
                                    echo '<li class="nav-item">
                                        <a class="nav-link" href="' . $value . '">' . $key . '</a>
                                    </li>';
                                }
                            } elseif ($_SESSION['utente']['livello'] == '1') {
                                foreach ($sitoMenu[2] as $key => $value) {
                                    echo '<li class="nav-item">
                                        <a class="nav-link" href="' . $value . '">' . $key . '</a>
                                    </li>';
                                }
                            }
                        }
                    ?>
                    <li id="menuDropdown" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"><?php echo $_SESSION['utente']['nome'] . ' ' . $_SESSION['utente']['cognome']; ?></a>
                        <ul id="dropdown" class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo ABSPATH . '/admin/modifica-utente.php?id=' . $_SESSION['utente']['id_utente']; ?>">Modifica utente</a></li>
                            <li><a class="dropdown-item" href="<?php echo ABSPATH . '/logout.php' ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
