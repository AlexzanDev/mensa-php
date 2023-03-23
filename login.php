<?php

// Importa il file di caricamento
require_once 'load.php';
// Carica l'head
mensaHead('Login');
?>

<div class="vh-100 login-container d-flex justify-content-center align-items-center">
    <div class="login-wrapper">
        <h1 class="login-titolo">Effettua l'accesso</h1>
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
                <button type="submit" class="btn btn-primary w-100 login-button">Login</button>
            </div>
        </form>
    </div>
</div>

<?php

// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
