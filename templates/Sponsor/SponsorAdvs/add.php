<?php
    $this->assign('title', __('Nuovo annuncio Funjob'));

    $this->Breadcrumbs
        ->add(__('Pubblicità'), ['action' => 'index'])
        ->add(__('Nuovo annuncio'), $this->request->getAttribute('here'));

?>

<div class="alert alert-sm alert-info">
    <p class="font-size-md3">
        <?= __('Contattaci per avere un piano pubblicitario personalizzato che comprenda banner e giochi prodotti da Funjob per far conoscere la tua azienda.') ?>
    </p>
    <div style="margin-top:10px">
        <a href="<?= $this->Url->build(['prefix' => false, 'controller' => 'contacts', 'action' => 'index', '?' => ['type' => 'sponsor']]) ?>" class="btn btn-info btn-xs">
            <?php echo __('Contattaci') ?>
        </a>
    </div>
</div>

<?php echo $this->Form->create($Adv, ['type' => 'file']) ?>
<fieldset class="well well-sm">
    <h3 class="font-size-md3 text-center"><?php echo __('Annuncio Pubblicitario') ?></h3>
    <?php
        // echo $this->Form->control('type', [
        //     'label'   => __('Tipologia annuncio'),
        //     'options' => [
        //         'banner'      => __('Annuncio su pagina'),
        //         'banner-quiz' => __('Annuncio su gioco: mostrato tra le varie domande del quiz')
        //     ]
        // ]);
        echo $this->Form->control('package_id', [
            'label'   => __('Pacchetto di visualizzazioni'),
            'options' => $packages,
            'help'    => __('I prezzi includono imposte e commissioni')
        ]);

        echo $this->Form->control('title', [
            'label' => __('Titolo'),
            'help'  => __('Massimo 100 caratteri'),
            //'help'  => __('Verrà mostrato per gli annunci mobile')
        ]);
        echo $this->Form->control('descr', [
            'label' => __('Descrizione'),
            'help'  => __('Massimo 150 caratteri'),
            //'help'  => __('Verrà mostrato per gli annunci mobile')
        ]);
        echo $this->Form->control('href', [
            'label'       => __('URL di destinazione'),
            'placeholder' => 'http://www.tuosito.it',
            'default'     => 'http://',
            'help'        => __('L\'utente verrà redirezionato a questo indirizzo dopo il click sull\'annuncio')
        ]);

        echo $this->Form->control('banner__img', [
            'type'  => 'file',
            'label' => __('Immagine pubblicitaria'),
            'help'  => (
                'Su sito: Dimensione: max 200 KB (altezza minima: 200px,  larghezza minima: 200px)'. '<br>'.
                'Su quiz: Dimensione: max 200 KB (altezza minima 400px, larghezza minima: 400px)'
            )
        ]);
    ?>
</fieldset>

<fieldset class="well well-sm">
    <h3 class="font-size-md3 text-center"><?= __('Target pubblicitario') ?></h3>

    <div class="alert alert-warning">
        <i class="fa fa-crosshairs fa-2x"></i>
        <?= __('Alcuni filtri fanno riferimento a informazioni non obbligatorie (es: sesso, età) per cui gli annunci non saranno visualizzati dagli utenti che non le hanno impostate. Inoltre, le altre aziende non potranno vedere i tuoi annunci se imposti questi filtri solamente per gli utenti.') ?>
    </div>
    <?php
        echo $this->Form->control('filter_for_sex', [
            'label'  => __('Sesso'),
            'options' => [
                'all'    => __('Tutti i sessi'),
                'male'   => __('Maschio'),
                'female' => __('Femmina')
            ],
            'help' => (
                '<span class="text-warning text-bold">'.
                '<i class="fa fa-warning"></i> '.
                __('La scelta del sesso è facoltativa ed è valida solo per utenti privati (il tuo annuncio non verrà visualizzato dalle aziende)') .
                '</span>'
            )
        ]);

        echo $this->Form->control('filter_for_age__from', [
            'label' => __('Età: da'),
            'type'  => 'numeric',
            'min'   => 1,
            'max'   => 100,
            'help' => (
                '<span class="text-warning text-bold">'.
                '<i class="fa fa-warning"></i> '.
                __('Il campo "data di nascita" è facoltativo ed è valido solo per utenti privati (il tuo annuncio non verrà visualizzato dalle aziende)') .
                '</span>'
            )
        ]);

        echo $this->Form->control('filter_for_age__to', [
            'label' => __('Età: a'),
            'type'  => 'numeric',
            'min'   => 1,
            'max'   => 100,
            'help' => (
                '<span class="text-warning text-bold">'.
                '<i class="fa fa-warning"></i>  '.
                __('Il campo "data di nascita" è facoltativo ed valido solo per utenti privati (il tuo annuncio non verrà visualizzato dalle aziende)') .
                '</span>'
            )
        ]);

        echo $this->Form->control('filter_for_country', [
            'label' => __('Lingua'),
            'options' => [
                'all' => __('Tutte'),
                'it' => __('Italiani'),
                'en' => __('Inglesi'),
                'fr' => __('Francese'),
                'ru' => __('Russo')
            ],
            'help' => (
                __('Il campo lingua viene prelevato dalla lingua selezionata dall\'utente')
            )
        ]);
    ?>
</fieldset>

<fieldset class="well well-sm">
    <h3 class="font-size-md3 text-center"><?= __('Dati di fatturazione') ?></h3>
    <?php
        echo $this->Form->control('billing_name', [
            'label'       => __('Nominativo'),
            'placeholder' => 'http://www.tuosito.it',
            'default'     => $AccountInfo['name']
        ]);

        echo $this->Form->control('billing_cf_vat', [
            'label'  => __('Codice fiscale (p.iva per aziende)')
        ]);

        echo $this->Form->control('billing_phone', [
            'label'       => __('Telefono'),
            'default'     => $AccountInfo['phone']
        ]);
        echo $this->Form->control('billing_email', [
            'label'       => __('E-mail'),
            'default'     => $AccountInfo['email']
        ]);


        echo $this->Form->control('billing_address', [
            'label'       => __('Indirizzo'),
            'default'     => $AccountInfo['address']
        ]);
        echo $this->Form->control('billing_city', [
            'label'       => __('Città'),
            'default'     => $AccountInfo['city']
        ]);
        echo $this->Form->control('billing_district', [
            'label'       => __('Provincia'),
            'default'     => $AccountInfo['district']
        ]);
        echo $this->Form->control('billing_cap', [
            'label'       => __('CAP'),
            'default'     => $AccountInfo['cap']
        ]);
    ?>
</fieldset>


<hr>
<fieldset>
    <?php
        echo $this->Form->control('accept_term_and_conditions', [
            'type'  => 'checkbox',
            'label' => __('Ho letto e accetto i Termini e le Condizioni'),
            'help'  => $this->Html->link(
                '<i class="fa fa-link"></i> ' . __('Leggi i Termini e le Condizioni'),
                ['prefix' => false, 'plugin' => false, 'controller' => 'Pages', 'action' => 'display', 0 => 'terms_and_conditions'],
                ['target' => '_blank', 'escape' => false]
            )
        ])
    ?>
</fieldset>

<?php
    echo $this->Form->button(__('Crea'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>
