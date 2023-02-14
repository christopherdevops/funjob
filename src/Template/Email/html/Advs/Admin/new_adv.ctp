<h3><?= $SponsorAdv->title ?></h3>
<dl>
    <dt><?= __('Descrizione') ?></dt>
    <dd><?= $SponsorAdv->descr ?> </dd>

    <dt><?= __('Destinazione') ?></dt>
    <dd><?= $this->Url->build($SponsorAdv->image_src, true) ?></dd>

    <dt><?= __('Destinazione') ?></dt>
    <dd><?= $SponsorAdv->href ?></dd>

    <dt><?= __('Impressioni') ?></dt>
    <dd><?= $SponsorAdv->impression_lefts ?></dd>


    <dt><?= __('Casuale pagamento (bonifico bancario, paypal)') ?></dt>
    <dd><?= $SponsorAdv->billing_casual ?></dd>

    <dt><?= __('Importo totale') ?></dt>
    <dd><?= $SponsorAdv->amount ?></dd>
</dl>


<figure style="background-color:whitesmoke;border:2px solid black;padding:3px;">
    <img src="<?= $this->Url->build($SponsorAdv->image_src, true) ?>" alt="404">
    <figcaption>
        <?= __('Anteprima immagine caricata') ?>
    </figcaption>
</figure>

<h3><?= __('Per abilitare questo annuncio') ?></h3>
<?=
    __(
        'Tramite la pagina {menu} puoi abilitare questo annuncio tramite la causale {payment_casual}',
        [
            'payment_casual' => $SponsorAdv->billing_casual,
            'menu'           => '<strong>' .__('Amministratore > Pubblicità > Pubblicità create') . '</strong>'
        ]
    )
?>
