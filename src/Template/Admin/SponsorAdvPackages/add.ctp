<?php
    $this->assign('title', __('Crea pacchetto pubblicitario'));
    $this->Breadcrumbs
        ->add(__('Pacchetti pubblicitari'), ['action' => 'index'])
        ->add(__('Nuovo'));
?>


<?php
    echo $this->Form->create($SponsorAdvPackage);
    echo $this->Form->control('type', [
        'label'   => __('Tipologia pubbliciaria'),
        'options' => [
            'banner'      => __('Pubblicità su pagine'),
            'banner-quiz' => __('Pubblicità su gioco')
        ]
    ]);

    echo $this->Form->control('impressions', [
        'label' => __('Impressioni'),
        'help'  => __('Numero di volte che la pubblicità verrà visualizzata all\'utente')
    ]);

    echo $this->Form->control('price', [
        'label'   => __('Prezzo (escluso commissioni)'),
        'prepend' => 'euro',
        'help'    => __('Per le MAXIMUM MEMORIES scrivi nelle commissioni lo stesso prezzo (50% guadagno funjob)')
    ]);

    echo $this->Form->control('tax_funjob', [
        'label'   => __('Commissione Funjob'),
        'prepend' => __('euro'),
        'default' => 0
    ]);
    echo $this->Form->control('tax_paypal', [
        'label'   => __('Commissione Paypal'),
        'prepend' => 'euro',
        'default' => 5,
        'help'    => __('Aggiunge commissione Paypal (fisso) sia per acquisti Paypal che con contrassegno')
    ]);
    echo $this->Form->control('tax_iva', [
        'label'   => __('IVA'),
        'default' => 22,
        'prepend' => '%',
        'help'    => 'Se non prevista, imposta questo campo a 0'
    ]);

    echo $this->Form->button(__('Crea'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>
