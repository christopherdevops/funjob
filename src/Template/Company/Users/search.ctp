<?php
    $this->extend('/User/Users/search.base');
    $this->assign('title', __('Ricerca aziende'));
    $this->assign('context', 'company');

    $this->Breadcrumbs->add(__('Aziende'), '#');
    $this->Breadcrumbs->add(__('Ricerca'), '#');
?>

<?php // Blocco: filtri per ricerca utenti ?>
<?php $this->start('users-filters') ?>
    <?php
        echo $this->Form->create($form, ['type' => 'get']);
        $this->Form->setValueSources(['query', 'context']);
    ?>
    <fieldset>
        <?php
            echo $this->Form->input('skills', [
                'default'     => '',
                'placeholder' => 'php,cakephp',
                'label'       => __('Competenze tecniche (separate da virgola)'),
                'help'        => __('Ti consigliamo di cercare termini tecnici che rispecchiano i requisiti della persona che stai cercando')
            ]);

            // Utilizzato per aggiornare città (non modificare POSIZIONE elemento hidden)
            echo $this->Form->hidden('city_id', ['id' => 'js-city-id']);
            echo $this->Form->input('city', [
                'label'        => __('Vive a'),
                'placeholder'  => __('Trascrivi il nome della città'),
                'type'         => 'text',
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'class'        => 'typeahead--cities',
                'help'         => '<span class="font-size-sm">Cerca città e seleziona dai risultati</span>'
            ]);
            echo $this->Form->error('city_id');
        ?>

        <?php
            echo $this->Form->button(
                '<i class="fa fa-search"></i> ' . __('Nuova ricerca'),
                ['escape' => false, 'class' => 'btn btn-block btn-sm btn-primary']
            );

            // Override pagina a 1 (se si submitta con ?page c'è il rischio che non ci siano tante pagine di risultati)
            echo $this->Form->hidden('page', ['value' => '1']);
            // Necessario per verificare che ci siano parametri di ricerca in URL
            echo $this->Form->hidden('_do', ['value' => 'search']);

            echo $this->Form->end(['data-type' => 'hidden']);
        ?>
    </fieldset>

    <script src="/bower_components/blockUI/jquery.blockUI.js"></script>
    <?= $this->element('cities-autocomplete') ?>
<?php $this->end() ?>
