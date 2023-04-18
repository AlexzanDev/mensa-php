$(document).ready(function() {
    console.log("Script caricato correttamente!");

    // Visualizza la password quando richiesto
    $("body").on("click", "#password-eye", function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        // Mostra la password nell'input corretto
        if($(this).attr("id") == "password-eye") {
            // Cerca l'input nello stesso div
            $(this).parent().find("input").attr("type", function(index, attr) {
                return attr == "password" ? "text" : "password";
            });
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

    // Gestisci ingredienti da nascondere se sono già stati aggiunti (modifica ricetta)
    var ingredienti = [];
    $('.ingrediente-elemento').each(function() {
        ingredienti.push($(this).find('input[name="ingredienti[]"]').val());
    });
    for(var i = 0; i < ingredienti.length; i++) {
        $('#ingredienti option[value="' + ingredienti[i] + '"]').prop('disabled', true);
    }

    // Gestione aggiunta ingredienti nella creazione ricetta
    $('#addIngredienteBtn').click(function() {
        var ingrediente = $('#ingredienti').val();
        var quantita = $('#quantita').val();
        if(ingrediente != '' && quantita != '') {
            // Mostra il nome e l'unità di misura dell'ingrediente selezionato
            var ingredienteNome = $('#ingredienti option:selected').text();
            ingredienteNome = ingredienteNome.split(' (');
            ingredienteNome = ingredienteNome[0];
            var ingredienteUnita = $('#ingredienti option:selected').text();
            ingredienteUnita = ingredienteUnita.split(' (');
            ingredienteUnita = ingredienteUnita[1];
            ingredienteUnita = ingredienteUnita.split(')');
            ingredienteUnita = ingredienteUnita[0];
            var nuovoIngrediente = '<div class="d-flex ingrediente-elemento">' + 
                                    '<input type="hidden" name="ingredienti[]" value="' + ingrediente + '">'
                                    + '<input type="hidden" name="quantita-' + ingrediente + '" value="' + quantita + '">'
                                    + '<p class="edit-form-text mt-2">' + ingredienteNome + ': ' + quantita + ingredienteUnita + '</p>'
                                    + '<button type="button" id="rimuoviIngredienteBtn" class="btn btn-danger mt-2"><i class="fas fa-times"></i></button></div>';
            $('.ingredienti-group').append(nuovoIngrediente);
            $('#ingredienti').val('');
            $('#quantita').val('');
            // Disabilita l'ingrediente selezionato
            $('#ingredienti option[value="' + ingrediente + '"]').prop('disabled', true);
        } else {
            alert('Inserisci un ingrediente e la quantità!');
        }
    });
    // Rimuove l'ingrediente selezionato
    $(document).on('click', '#rimuoviIngredienteBtn', function() {
        $(this).parent().remove();
        // Abilita l'ingrediente selezionato
        var ingrediente = $(this).parent().find('input[name="ingredienti[]"]').val();
        $('#ingredienti option[value="' + ingrediente + '"]').prop('disabled', false);
    });
});