<?php
    use Cake\Utility\String;

    $this->assign('title', __('Vetrina Aziende'));

    $this->Breadcrumbs
        ->add(__('Aziende'), ['_name' => 'companies:categories:archive']);
?>

<?php $this->append('css_head--inline') ?>
    .breadcrumb-categories { background-color:white;}
    i.fa.fa-folder-o, i.fa.fa-folder-open-o, .browse-directory-current {color:#00adee;font-weight:bold;}

    .browse-directory-child {color:#4cc5f3;}
    .browse-directory-child .fa-folder-o {color:#4cc5f3 !important}

    .well-search {background-color:#dadada !important}

    .js-filter-btn {
        background-color:#CECECE;
        box-shadow:1px 1px 0px 1px #00adee;
    }
<?php $this->end() ?>


<?php // Breadcrumb: categorie (non più utilizzato) ?>
<?php $this->start('categories:breadcrumb') ?>
    <div class="">

        <ol class="breadcrumb breadcrumb-categories">
            <li>
                <a href="<?= $this->Url->build(['_name' => 'quiz:categories:archive']) ?>">
                    <i class="fa fa-folder-open-o"></i>
                    <?= __('Categorie') ?>
                </a>
            </li>
            <li>
                <span class="text-muted">/</span>
            </li>

            <?php foreach ($Category as $subcategory): ?>
            <li>
                <a href="<?= $this->Url->build(['_name' => 'quiz:categories:archive-slug', 'id' => $subcategory->id, 'title' => $subcategory->slug]) ?>">
                    <?= $subcategory->name ?>
                </a>
            </li>
            <?php endforeach ?>
        </ol>
    </div>
<?php $this->end() ?>

<?php // Browser: di categorie ?>
<?php $this->start('categories') ?>

    <h3 class="no-margin text-bold text-muted font-size-md3">
        <i class="fa fa-mouse-pointer"></i>
        <?php if ($isRoot) : ?>
            <?= __('Clicca le cartelle di seguito') ?>
        <?php else: ?>
            <?= __('Clicca le cartelle e affina la ricerca') ?>
        <?php endif ?>
    </h3>
    <div class="margin-top--md"></div>
    <div class="row">
        <?php $margin = 0 ?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a style="margin-left:<?= $margin ?>px;<?= $isRoot ? 'color:orange !important' : '' ?>"  href="<?= $this->Url->build(['_name' => 'companies:categories:archive']) ?>" class="browse-directory-current">
                <span class="font-size-md3">
                    <i class="fa fa-2x fa-folder-open-o" style="<?= $isRoot ? 'color:orange !important' : '' ?>"></i>
                    <?= __('Tutte le categorie') ?>
                </span>
            </a>
        </div>


        <?php //$CategoryCurrent = end($Category) ?>
        <?php foreach ($Category as $CategoryPath) : $margin += 30 ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php if ($CategoryCurrent->id == $CategoryPath->id) : ?>
                <div style="color:orange;margin-left:<?= $margin ?>px" class="browse-directory-current">
                    <span class="font-size-md3">
                        <i style="color:orange" class="fa fa-2x fa-folder-open-o"></i>
                        <?php echo $CategoryPath->name ?>
                    </span>
                </div>
                <?php else: ?>
                <a style="margin-left:<?= $margin ?>px"  href="<?= $this->Url->build(['_name' => 'companies:categories:archive-slug', 'id' => $CategoryPath->id, 'title' => $CategoryPath->slug]) ?>" class="browse-directory-current">
                    <span class="font-size-md3">
                        <i class="fa fa-2x fa-folder-open-o"></i>
                        <?php echo $CategoryPath->name ?>
                    </span>
                </a>
                <?php endif ?>
            </div>
        <?php endforeach ?>

        <?php $margin += 30; ?>
        <?php foreach($categories as $category) : ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <a class="browse-directory-child" style="margin-left:<?= $margin ?>px" href="<?= $this->Url->build(['_name' => 'companies:categories:archive-slug', 'id' => $category->id, 'title' => $category->slug]) ?>">
                <span class="font-size-md3">
                    <i class="fa fa-2x fa-folder-o"></i>
                    <?php echo $category->name ?>
                </span>
            </a>
        </div>
        <?php endforeach ?>
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

<?php // Form per filtraggio giochi ?>
<?php $this->start('filters:form') ?>
    <div class="container-fluid">
        <?php
            echo $this->Form->setValueSources(['query'])->create(null, [
                'url'    => $this->Url->build([]),
                'method' => 'POST',
                'class'  => ''
            ]);

            echo $this->Form->control('term', [
                'label' => __('Cerca per nome')
            ]);

            // City autocomplete
            $autocomplete_id = 'cities-autocomplete--' . uniqid();
            echo '<div id="'. $autocomplete_id. '-helper" class="city-autocomplete-component">';
            echo '<label for="'.$autocomplete_id. '">'. __('Città') . '</label>';

            echo $this->element('cities-autocomplete', [
                'id'          => $autocomplete_id,
                'templateTag' => '#tpl-cities-autocomplete-cityTag',
                'tagMax'      => 5
            ]);

            echo $this->fetch('city-autocomplete:selectedTags');

            $this->Form->unlockField('city_id');
            echo $this->Form->hidden('city_id', ['class' => 'js-city-id']);
            echo $this->Form->input('_city', [
                'label'        => false,
                'id'           => $autocomplete_id,
                'placeholder'  => __('Digita il nome e attendi i suggerimenti (3 caratteri richiesti) '),
                'type'         => 'text',
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'default'      => '',
                'class'        => 'typeahead--cities',
                'help'         => '<span class="font-size-sm">'. __('Seleziona la città tra i risultati mostrati') .'</span>'
            ]);
            echo '</div>';

            echo $this->Form->button(
                __('{icon} Filtra', ['icon' => '<i class="fa fa-filter"></i>']),
                ['class' => 'btn btn-sm btn-primary']
            );

            if (!$isRoot) {
                echo $this->Form->button(
                    '<i class="fa fa-arrow-up"></i> '. __('Chiudi'),
                    ['class' => 'btn btn-sm btn-secondary js-filter-btn', 'type' => 'button', 'style' => 'margin-left:5px']
                );
            }

            echo $this->Form->control('do', ['value' => 'search', 'type' => 'hidden']);
            echo $this->Form->end(['security' => 'hidden']);
        ?>
    </div>
<?php $this->end() ?>

<?php // Maschera per filtraggio dati con slideToggle ?>
<?php $this->start('filters') ?>
    <div class="row">

        <?php if ($hasResults > 0) : ?>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p class="text-center text-muted">
                <?=
                    __(
                        'Apri {button} per filtrare le aziende delle sottocategorie di {name}', [
                        'name'   => '<strong>"' .$CategoryCurrent->name. '"</strong>',
                        'button' => '<strong>"' .__('Ricerca Avanzata'). '"</strong>'
                    ])
                ?>
            </p>
            <button class="js-filter-btn btn btn-default btn-block">
                <div class="text-bold text-center">
                    <i class="fa fa-search"></i>

                    <?= __('Ricerca Avanzata') ?>
                    <?= __('(Apri {icon})', ['icon' => '<i class="text-color-primary fa fa-arrow-down"></i>']) ?>
                </div>
            </button>
        </div>
        <script type="text/javascript">
            $(function() {
                $(".js-filter-btn").on("click", function(evt) {
                    $("#result-filter-form").slideToggle();
                });
            });
        </script>
        <?php endif ?>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="margin-top--xs"></div>
            <div id="result-filter-form" class="well well-search well-sm" style="display:none">
                <?php echo $this->fetch('filters:form') ?>
            </div>
        </div>

    </div>
    <div style="margin-top:20px;margin-bottom:20px"></div>
<?php $this->end() ?>

<?php // Sezione: giochi trovati per categoria/filtri correnti ?>
<?php $this->start('results') ?>

    <?php if (!$isRoot) : ?>
        <?php echo $this->fetch('filters') ?>
    <?php else: ?>
        <div class="well well-search well-sm">
            <h4 class="text-bold text-center font-size-md3">
                <i class="fa fa-search"></i>
                <?= __('Filtra ulteriormente questi risultati') ?>
            </h4>
            <?php echo $this->fetch('filters:form') ?>
        </div>
    <?php endif ?>

    <div class="row gutter-10">
        <?php foreach ($results as $company) : ?>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="panel panel-sm panel-info usergroup-panel">
                <div class="panel-heading">
                    <h3 class="panel-title no-margin">
                        <a class="text-truncate display-block" title="<?= $company->username ?>" href="<?= $this->Url->build(['_name' => 'companies:profile', 'id' => $company->id, 'username' => $company->slug]) ?>">
                            <?= $company->username ?>
                        </a>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row gutter-10">
                        <div class="col-xs-2 col-sm-3 col-md-3 col-lg-3">
                            <a href="<?= $this->Url->build(['_name' => 'companies:profile', 'id' => $company->id, 'username' => $company->slug]) ?>">
                                <img class="img-responsive img-circle user-company-cover" src="<?= $company->imageSize($company->avatarSrc, '80x80') ?>" data-src="holder.js/80x80?auto=yes&text=<?= $company->username ?>" alt="">
                            </a>
                        </div>
                        <div class="col-xs-10 col-sm-9 col-md-9 col-lg-9">
                            <p class="usergroup-index-title text-muted font-size-md">
                                <?= $this->Text->truncate(h($company->title), 140) ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>

<?php $this->end() ?>

<?php // Header: risultati ricerca ?>
<?php $this->start('browser:counter') ?>
    <?php if (!$isRoot) : ?>
    <div class="panel panel-<?= ($hasResults > 0 ? 'info' : 'danger') ?>">
        <div class="panel-heading">
            <h3 class="panel-title text-bold font-size-md1 text-center">
                <?php
                    if ($hasResults > 0) {
                        echo __(
                            '{count} AZIENDE trovate nella la categoria {name}', [
                            'name'  => '<strong>"' .$CategoryCurrent->name. '"</strong>',
                            'count' => $hasResults
                        ]);

                    } else {
                        echo __('I tuoi criteri di ricerca non hanno prodotto nessun risultato');
                    }
                ?>
            </h3>
        </div>
        <div class="panel-body" style="padding:2px !important">
            <?php if ($hasResults) : ?>
                <small class="display-block text-center" style="padding-top:3px;padding-bottom:3px;">
                    <?=
                        __(
                            'Stai visionando anche i risultati delle sottocategorie di {name}', [
                            'name'  => '<strong>"' .$CategoryCurrent->name. '"</strong>'
                        ])
                    ?>

                    <a style="margin-left:10px" href="<?= $this->request->here() . '#results' ?>" class="btn btn-sm btn-default">
                        <span class="text-bold text-color-gray--dark">
                            <i class="text-color-primary fa fa-arrow-down"></i>
                            <?= __('Scorri fino ai risultati') ?>
                        </span>
                    </a>
                </small>
            <?php else: ?>

                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-center">
                            <div class="margin-top--md"></div>
                            <div>

                                <?php // Se non ci sono quiz e nessuna ricerca è stata effettuata ?>
                                <?php if ($hasResults == 0 && $useFilters) : ?>
                                <p>
                                    <?= __('Nella categoria selezionata non sono presenti aziende: ti consigliamo di tornare alla categoria precedente') ?>
                                </p>
                                <?php endif ?>

                                <?php // Se è stata effettuata una ricerca: reset filtri ?>
                                <?php if ($useFilters) : ?>
                                <p>
                                    <?=
                                        __(
                                            'Elimina i filtri precedentemente selezionati e riprova con altri filtri {link}', [
                                            'link' => (
                                                '<a href="' .$this->request->here .'?'. '" class="btn btn-xs btn-danger">'.__('eliminali').'</a>'
                                            )
                                        ])
                                    ?>
                                </p>
                                <hr>

                                <?php echo $this->fetch('filters:form') ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif ?>
        </div>
    </div>
    <?php endif ?>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <?php echo $this->fetch('browser:counter') ?>

        <div class="panel panel-funjob-gray">
            <div class="panel-heading">
                <h3 class="text-bold panel-title font-size-md3">
                    <i style="color:white" class="fa fa-folder-o"></i>
                    <?= __('Ricerca Azienda per categoria') ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php echo $this->fetch('categories') ?>
            </div>
        </div>

        <div class="panel panel-funjob-gray">
            <div class="panel-heading">
                <h3 class="text-bold panel-title font-size-md3">
                    <i class="fa fa-building-o"></i>
                    <?=
                        __(
                            '{count} azienda/e trovate per {name}', [
                            'count' => $hasResults,
                            'name'  => '<strong>"' .$CategoryCurrent->name. '"</strong>'
                        ])
                    ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php echo $this->fetch('results') ?>
                <?php echo $this->element('pagination') ?>
            </div>
        </div>

    </div>
</div>
