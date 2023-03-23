<?php

// Importa il file di caricamento
require_once 'load.php';
// Carica l'head
mensaHead('Ripristina la password');
?>

<div class="vh-100 login-container d-flex justify-content-center align-items-center">
    <div class="login-wrapper">
        <h1 class="login-titolo">Ripristina la password</h1>
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
                    <input type="password" name="nuovaPasswordVerifica" id="passwordVerifica" class="form-control login-form-input" placeholder="Conferma password" required>
                    <i id="verifica-password-eye" class="fa-solid fa-eye login-password-eye"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <button type="submit" class="btn btn-primary w-100 login-button">Reimposta la password</button>
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
