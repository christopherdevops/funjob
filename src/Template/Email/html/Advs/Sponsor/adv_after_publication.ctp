<p><?= __('Ciao {customer},', ['customer' => '@'. $SponsorAdv->user->username]) ?></p>
<p><?= __('Complimenti, il tuo annuncio è visibile su FunJob.it', []) ?></p>

<hr>
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
</dl>

<figure>
    <img src="<?= $this->Url->build($SponsorAdv->image_src, true) ?>" alt="404">
    <figcaption>
        <?= __('Immagine caricata') ?>
    </figcaption>
</figure>
