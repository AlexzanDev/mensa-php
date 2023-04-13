<?php
// Importa il file di caricamento
require_once '../load.php';
// Carica l'head e l'header
mensaHead('Mensa');
require_once ABSPATH . '/layout/components/header.php';
?>

<div class="body">
  <div class="container">

    <div class="row justify-content-center">
      <div class="col-9">
        <table class="table table-hover">
          <div class="row my-3" style="display:flex;align-items: center;">
            <div class="col-2">
              <h1>Ricette</h1>
            </div>
            <div class="col-2 fs-6">
              <button class="btn btn-outline-dark fs-6">Aggiungi nuovo</button>
            </div>
            <div class="col-8 d-grid gap-2 d-md-flex justify-content-md-end" style="display: flex;">
              <input type="text" class="form-control btn-sm" style="width:30%;" placeholder="Inserisci testo">
              <button class="btn btn-primary">Cerca</button>
            </div>
          </div>
        </table>
      </div>
    </div>
    <!-- Riga con titolo, bottone e input text -->


    <!-- Tabella centrata -->
    <div class="row justify-content-center">
      <div class="col-9">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th scope="col" class="text-center" style="width: 20%;">Nome</th>
              <th scope="col" class="text-center" style="width: 26%;">Tempo di preparazione</th>
              <th scope="col" class="text-center" style="width: 27%;">Tempo di cottura</th>
              <th scope="col" class="text-center" style="width: 27%;">Sommario</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $query = "SELECT nome,tempo_preparazione,tempo_cottura,sommario FROM ricette";
            $dichiarazione = $database->prepare($query);

            if ($dichiarazione->execute()) {
              $risultato = $dichiarazione->get_result();
              while ($riga = $risultato->fetch_array(MYSQLI_NUM)) {
              ?>

                <tr>
                  <td class="text-center" style="color: #0161d1;"><?php echo $riga[0] ?></td>
                  <td class="text-center"><?php echo $riga[1] ?></td>
                  <td class="text-center"><?php echo $riga[2] ?></td>
                  <td class="text-center"><?php echo $riga[3] ?></td>
                </tr>
              <?php
              
              }
            }
            else{
            echo"ciao";
            }

            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<?php
// Carica il footer
require_once ABSPATH . '/layout/components/footer.php';
?>
