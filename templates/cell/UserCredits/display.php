<span title="PIX = crediti">PIX</span>
<span style="color:#00FF00;">
    <?php if (is_numeric($credits)) : ?>
        <?= $this->Text->truncate($credits, 5, ['ellipsis' => '..', 'exact' => true]) ?>
    <?php else: ?>
        N/D
    <?php endif ?>
</span>



<script type="text/template" id="funjob-pix-tutorial-template">

    <?php if ($this->request->session()->check('Auth.User')) : ?>
    <br><br>
    <div class="alert alert-sm alert-warning" style="box-shadow:2px 2px 2px 2px whitesmoke">
        <p class="text-bold text-center">
            <?php echo __('Hai guadagnato:') ?>
            <i style="color:#ffc300;font-size:2em" class="fontello-credits"></i>
            <?= $credits ?> PIX
        </p>
    </div>
    <?php endif ?>

    <p class="text-color-gray--dark font-size-md2">
        <i style="color:#ffc300;font-size:2em" class="fontello-credits"></i>
        <?= __('I PIX sono la moneta virtuale che accumulerai giocando con FunJob.') ?>
    </p>
    <p class="text-color-gray--dark font-size-md2">
        <?= __('FunJob ridistribuisce fino al 50% degl\'introiti pubblicitari dei giochi all\'utente che li ha generati giocandoli.') ?>
    </p>
    <hr>


    <p class="text-bold text-color-gray--dark font-size-md2"><?= __('Come puoi guadagnare PIX?') ?></p>
    <ul class="text-muted">
        <li class="font-size-md1">
            <span class="text-bold"><?= __('Partecipando ai giochi') ?>:</span>
            <?= __('se superi il gioco ti verrà accreditato 1 PIX per ogni annuncio pubblicitario visualizzato') ?>.
        </li>
        <li class="font-size-md1">
            <span class="text-bold"><?= __('Creando nuovi giochi') ?>:</span>
            <?= __('ogni utente che giocherà ti farà guadagnare PIX') ?>.
        </li>
    </ul>

    <p class="text-bold text-color-gray--dark font-size-md2"><?= __('Logiche di assegnazione PIX') ?></p>
    <ul class="text-muted">
        <li class="font-size-md1">
            <span class="text-bold"><?= __('Giocatore') ?>:</span>
            <?= __('Se superi il gioco ti verrà accreditato il 70% dei PIX maturati dalle pubblicità visualizzate (il restante 30% verrà corrisposto all\'autore del gioco)') ?>.
        </li>
        <li class="font-size-md1">
            <span class="text-bold"><?= __('Autore del gioco') ?>:</span>
            <br>
            * <?= __('Se l\'utente supera il tuo gioco, ti verrà riconosciuto il 30% del suo guadagno in PIX.') ?> <br>
            * <?= __('Se l\'utente non supera il gioco, ti verrà concesso da 1 a 3 PIX in base a quanti annunci ha visualizzato l\'utente (con un minimo di due).') ?>
        </li>
    </ul>

    <p class="text-bold text-color-gray--dark font-size-md2"><?= __('Come posso spendere i miei PIX?') ?></p>
    <p class="text-muted font-size-md1">
        <?php
            $storeUrl = $this->Url->build(['_name' => 'store:index']);

            echo __(
                'Puoi spendere i tuoi PIX nel nostro {linkStart}negozio{linkEnd}, scegliendo tra i tanti prodotti disponibili.',
                [
                    'linkStart' => '<a class="text-color-primary" href=" '.$storeUrl.' "> <i class="fa fa-shopping-cart"></i> ',
                    'linkEnd'   => '</a>'
                ]
            )
        ?>
    </p>

    <p class="text-bold text-color-gray--dark font-size-md2"><?= __('Come incrementare il guadagno di PIX?') ?></p>
    <p class="font-size-md1">
        <?= __('Attraverso la condivisione nei social network e in generale tra la tua cerchia di conoscenze, potrai fare in modo che tanti utenti partecipino al gioco che hai creato. Così facendo guadagnerai PIX e avrai una rendita di conoscienza.') ?>
    </p>


    <div class="well well-sm">
        <p class="font-size-md1"><?= __('Con FunJob potrai sempre decidere di giocare senza messaggi pubblicitari e rinunciando ai relativi PIX.') ?></p>
        <p class="font-size-md1"><?= __('Il valore economico dei PIX e il costo dei prodotti del Negozio variano in relazione alle politiche e alle strategie commerciali di FunJob.') ?></p></li>
        <p class="font-size-md1"><?= __('Le condizioni descritte si applicano solo in presenza di annunci pubblicitari.') ?></p>
    </div>


    <a class="btn btn-xs btn-block btn-primary" href="<?= $this->Url->build(['plugin' => null, 'prefix' => null, 'controller' => 'Pages', 'action' => 'display', 'terms_and_conditions']) ?>">
        <span>
            <?= __('Approfondisci') ?>
        </span>
    </a>
</script>
<script>
    $(function() {
        $(".funjob-pix-tutorial").on("click", function(evt) {
            evt.preventDefault();
            var modal = bootbox.dialog({
                className : "funjob-modal",
                message   : $("#funjob-pix-tutorial-template").html()
            })
            // FIX: mostra fine contenuto piuttosto che inizio
            .off("shown.bs.modal");
        });
    });
</script>
