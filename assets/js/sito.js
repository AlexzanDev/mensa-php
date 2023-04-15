$(document).ready(function() {
    console.log("Script caricato correttamente!");

    // Visualizza la password quando richiesto
    const passwordInput = document.getElementById("password");
    const passwordVerifica = document.getElementById("passwordVerifica");
    $("body").on("click", "#password-eye, #verifica-password-eye", function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        // Controlla chi ha richiamato la funzione
        if ($(this).attr("id") === "password-eye" || $(this).attr("id") === "verifica-password-eye") {
            if (passwordInput.type === "password" || passwordVerifica.type === "password") {
                $(passwordInput).attr("type", "text");
            } else {
                $(passwordInput).attr("type", "password");
            }
        }
    });

    // Ricerca nelle tabelle - Via: https://stackoverflow.com/questions/64546933/how-to-use-jquery-to-filter-a-bootstrap-table-by-particular-columns-only
    function ricercaTabella() {
        var ricerca = $(this).val().toLowerCase();
        $("tr:not(:first)").filter(function() {     
            $(this).toggle($(this).find("td:first").text().toLowerCase().indexOf(ricerca) > -1);
        });
    }
    $("#ricerca").on("keyup", ricercaTabella);
});