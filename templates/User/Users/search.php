<?php
    $this->extend('/User/Users/search.base');
    $this->assign('title', __('Ricerca utenti'));
    $this->assign('context', 'user');

    $this->Breadcrumbs->add(__('Utenti'), '#');
    $this->Breadcrumbs->add(__('Ricerca'), '#');
?>

<?php $this->append('css_head--inline') ?>
    #filters, #results {padding-top:30px !important }
<?php $this->end() ?>

<?php // BLOCCO: Ricerca senza risultati ?>
<?php $this->start('alert:search-no-results') ?>
    <p class="font-size-md2 text-center text-warning">
        <?php echo __('Nessun utente trovato con i parametri da te specificati') ?>
    </p>
    <p class="font-size-md text-muted text-center">
        <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
        <?= __('Prova con una ricerca meno mirata') ?>
    </p>
<?php $this->end() ?>

<?php // BLOCCO: avviso sulle ricerche sui gruppi ?>
<?php $this->start('info:search-group') ?>
    <div class="margin-top--md visible-xs visible-sm"></div>
    <div class="alert alert-sm alert-info" style="overflow:hidden">
        <h4 class="font-size-md2 text-center">
            <?= __('Ricerche in base a gruppo') ?>
        </h4>
        <p class="font-size-md">
            <?=
                __x(
                    'Introduzione a gruppi utenti su pagina di ricerca utenti',
                    'Puoi cercare gli altri utenti anche in base all\'università frequentata o agli hobbies nella sezione gruppi'
                )
            ?>
            <hr style="margin-top:10px;margin-bottom:10px">
            <a class="btn btn-xs btn-default btn-block" href="<?= $this->Url->build(['_name' => 'groups:archive']) ?>">
                <i class="text-color-primary fa fa-university"></i>
                <?= __('Mostra gruppi di utenti') ?>
            </a>
        </p>
    </div>
<?php $this->end() ?>

<?php // Blocco: ?>
<?php $this->start('city-autocomplete:selectedTags') ?>
    <div class="cities-autocomplete-tags" style="margin-bottom:5px;">
        <?php foreach ($this->request->getQuery('city', []) as $cityId => $cityLabel) : ?>
        <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm" data-id="<?= $cityId ?>">
            <?= $cityLabel ?>
            <i class="fa fa-times"></i>
            <input type="hidden" name="city[<?= $cityId ?>]" value="<?= $cityLabel ?>" />
        </button>
        <?php endforeach ?>
    </div>
    <?php $this->append('js_foot') ?>
    <script id="tpl-cities-autocomplete-cityTag" type="text/x-handlebars-template">
        <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm" data-id="{{value}}">
            {{accent_city}}
            <input type="hidden" name="city[{{value}}]" value="{{accent_city}}" />
            <i class="fa fa-times"></i>
        </button>
    </script>
    <?php $this->end() ?>
<?php $this->end() ?>

<?php // Blocco: mostra il risultato della ricerca ?>
<?php $this->start('users-results') ?>
    <p class="text-color-primary font-size-md pull-right">
        <?php $counter = $this->Paginator->counter('{{count}}') ?>
        <?= __n('1 utente soddisfa la tua richiesta', '{0} utenti soddisfano la tua richiesta', $counter, $counter) ?>
    </p>
    <div class="clearfix"></div>
    <?php if (!empty($users)) : ?>
    <?php foreach ($users as $user): ?>
        <div class="row row-eq-height">

                <div class="col-md-2">
                    <?php // margin:0 auto serve per non far espandere la foto (se si usa row-eq-height)  ?>
                    <?= $this->User->avatar($user->avatarSrcDesktop, ['style' => 'margin:auto']) ?>
                </div>

                <div class="col-md-4">
                    <div style="">
                        <?= $this->Html->link($user->username, $user->url, ['class' => 'display-block']) ?>
                        <small class="text-muted text-truncate"><?php echo $user->fullname ?></small>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <?php
                        // Questa colonna comparirà solo se si utilizza il filtro (Professione)
                    ?>
                    <ul style="list-style:none">
                        <?php // DA ELIMIANARE (non più utilizzato) ?>
                        <?php foreach ((array) $user->job_offers as $i => $offer) :  if (!is_numeric($i)) continue ?>
                        <li><?php echo $offer->name ?></li>
                        <?php endforeach ?>

                        <?php if (!empty($user->_matchingData['AccountInfos']['live_city'])) : ?>
                        <li>
                            <i class="fa fa-map-o"></i>
                            <?php echo $user->_matchingData['AccountInfos']['live_city'] ?>
                        </li>
                        <?php endif ?>

                        <?php // VIENE USATE _matchingData ?>
                        <?php foreach ((array) $user->user_skills as $i => $skill) :  if (!is_numeric($i)) continue ?>
                        <li class="font-size-sm">
                            <?php echo $skill->name ?>
                            (<?= __x('grado di conoscienza skill', 'Esperienza: {0}%', $skill->perc) ?>)
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>

        </div>
        <hr>
    <?php endforeach ?>

    <?php
        // Imposta requestData come parametri GET per paginator
        $passedArgs = $this->request->getData() + $this->passedArgs;
        $this->Paginator->options([
            'url' => $passedArgs
        ]);

        echo $this->element('pagination');
    ?>
    <?php endif ?>

<?php $this->end() ?>

<?php // BLOCCO: Ricerca con risultati ?>
<?php $this->start('search-results') ?>
    <hr class="visible-xs visible-sm">
    <?php if ($isSearch) : ?>
    <?= $this->fetch('users-results') ?>
    <?php endif ?>
<?php $this->end() ?>

<?php $this->start('form:ages') ?>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <?php
                echo $this->Form->control('age_from', [
                    'default' => '',
                    'type'    => 'number',
                    'label'   => false, //__('Età (da)'),
                    'prepend' => __('da'),
                    'append'  => __('anni')
                ]);
            ?>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <?php
                echo $this->Form->control('age_to', [
                    'default' => '',
                    'type'    => 'number',
                    'label'   => false, //__('Età (fino a)'),
                    'prepend' => __('fino a'),
                    'append'  => __('anni')
                ]);
            ?>
        </div>
    </div>
<?php $this->end() ?>

<?php // Blocco: filtri per ricerca utenti ?>
<?php $this->start('users-filters') ?>
    <?php
        echo $this->Form->create($form, ['type' => 'get']);
        $this->Form->setValueSources(['query', 'context']);
    ?>
    <fieldset>
        <?php
            echo $this->Form->control('fullname', [
                'label'       => __('Nome e cognome'),
                'placeholder' => 'Mario Rossi'
            ]);

            echo $this->Form->control('username', [
                'label' => __('Nome utente')
            ]);

            echo $this->Form->control('sex', [
                'label' => __('Sesso'),
                'options' => [
                    ''       => __('Tutti'),
                    'male'   => __('Maschio'),
                    'female' => __('Femmina')
                ]
            ]);

            echo $this->Form->label('age_from',
                __(
                    'Età {noteStart}puoi compilare anche solo uno dei campi di età{noteEnd}',
                    ['noteStart' => '<small class="text-muted">', 'noteEnd' => '</small>']
                ),
                ['escape' => false]
            );

            echo $this->fetch('form:ages');

            // FILTRI TITOLI DI STUDIO
            /*
            echo $this->Form->control('qualification', [
                'type'    => 'select',
                'options' => [
                    ''  => '-- Tutti',
                    '1' => 'Laureato',
                    '2' => 'Diplomato',
                    '3' => 'Non diplomato'
                ],
                'default' => '',
                'label'   => __('Titolo di studio'),
                'help'    => __('<span class="label label-warning">Attenzione</span> Tieni in considerazione che ci sono moltissimi talenti che non hanno un titolo di studio (auto didatti)')
            ]);
            echo $this->Form->control('facolta', [
                'type'    => 'select',
                'options' => [
                    ''  => '-- Tutti',
                    'Università' => [
                        '1' => 'Informatica',
                        '2' => 'Ing. informatica',
                        '3' => 'Medicina',
                        '4' => 'Architettura',
                        '5' => 'Infermieristica',
                        '6' => 'Economia e commercio'
                    ],
                    'Liceo' => [
                        '22' => 'Artistico',
                        '23' => 'Classico',
                    ]
                ],
                'default' => '',
                'label'   => __('Facoltà'),
                'help'    => __('<span class="label label-warning">Attenzione</span> Tieni in considerazione che ci sono moltissimi talenti che non hanno un titolo di studio (auto didatti)')
            ]);
            */

            // FILTRI: PROFESSIONALI

            // echo $this->Form->control('role', [
            //     'type'    => 'select',
            //     'options' => array_merge(['' => '-- Tutti'], $jobs->toArray()),
            //     'default'     => '',
            //     'label'       => __('Professione'),
            // ]);

            echo $this->Form->control('skills', [
                'default'     => '',
                'placeholder' => 'php,cakephp',
                'label'       => __('Competenze tecniche (separate da virgola)'),
                'help'        => __(
                    'Ti consigliamo di cercare termini tecnici che rispecchiano i requisiti della persona che stai cercando' .
                    'es: sviluppatore web, php'
                )
            ]);

            echo $this->Form->control('hobbies', [
                'default'     => '',
                'label'       => __('Hobbies & interessi')
            ]);

            // Utilizzato per aggiornare città (non modificare POSIZIONE elemento hidden)
            $autocomplete_id = 'city-autocomplete--' . uniqid();
            echo '<div id="'. $autocomplete_id. '-helper" class="city-autocomplete-component">';
            echo '<label for="'.$autocomplete_id. '">'. __('Vive a') . '</label>';

            echo $this->element('cities-autocomplete', [
                'id'          => $autocomplete_id,
                'templateTag' => '#tpl-cities-autocomplete-cityTag',
                'tagMax'      => 5
            ]);

            echo $this->fetch('city-autocomplete:selectedTags');



            echo $this->Form->error('city_id');
            echo $this->Form->control('_city', [
                'label'        => false,
                'id'           => $autocomplete_id,
                'placeholder'  => __('Digita il nome e attendi i suggerimenti (3 caratteri richiesti) '),
                'type'         => 'text',
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'default'      => '',
                'class'        => 'typeahead--cities',
                'help'         => '<span class="font-size-sm">'. __('Cerca città e seleziona dai risultati') .'</span>'
            ]);
            echo '</div>';
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
<?php $this->end() ?>

<div class="page-header">
    <h1><?= __('Ricerca utenti') ?></h1>
</div>
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="<?= !$isSearch ? 'active': '' ?>">
            <a href="#filters" aria-controls="home" role="tab" data-toggle="tab">
                <i class="fa fa-search"></i>
                <?= __('Ricerca') ?>
            </a>
        </li>

        <?php if ($isSearch) : ?>
        <li role="presentation" class="active">
            <a href="#results" aria-controls="tab" role="tab" data-toggle="tab">
                <i class="fa fa-users"></i>
                <?= __('Risultati') ?>
            </a>
        </li>
        <?php endif ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

        <div role="tabpanel" class="tab-pane <?= !$isSearch ? 'active': '' ?>" id="filters">
            <div class="col-xs-12 col-sm-6 col-md-9 col-lg-9">
                <?= $this->fetch('users-filters') ?>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-3 col-lg-3">
                <?= $this->fetch('info:search-group') ?>
            </div>
        </div>

        <?php if ($isSearch) : ?>
        <div role="tabpanel" class="tab-pane <?= $isSearch ? 'active': '' ?>" id="results">
            <?php
                if ($users->isEmpty()) {
                    echo 'alert:search-no-results';
                    echo $this->fetch('alert:search-no-results');
                } else {
                    echo $this->fetch('search-results');
                }
            ?>
        </div>
        <?php endif ?>

    </div>
</div>
