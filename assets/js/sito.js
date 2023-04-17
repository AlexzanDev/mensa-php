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

    // Mostra il dropdown del menu al click
    $("#menuDropdown").on("click", function() {
        $("#dropdown").toggle();
        // Chiudi il dropdown se si clicca fuori
        $(document).on("click", function(event) {
            if (!$(event.target).closest("#menuDropdown").length) {
                $("#dropdown").hide();
            }
        });
    });

    // Mostra il menu su mobile al click del bottone
    $(".navbar-toggler").on("click", function() {
        $("#navbarNavDropdown").toggle();
    });

    // Carica il datepicker
    $.datepicker.regional['it'] = {
        closeText: 'Chiudi',
        currentText: 'Oggi',
        monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
        monthNamesShort: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
        dayNames: ['Domenica', 'Luned&#236', 'Marted&#236', 'Mercoled&#236', 'Gioved&#236', 'Venerd&#236', 'Sabato'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Me', 'Gio', 'Ve', 'Sa'],
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
    };
    $.datepicker.setDefaults($.datepicker.regional['it']);
    $("#dataNascita").datepicker();
});