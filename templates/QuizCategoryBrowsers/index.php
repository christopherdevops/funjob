<?php
    $this->assign('title', __('Ricerca Gioco per categorie'));
    $this->assign('header', ' ');

    $this->Html->css(['quizzes/index', 'quizzes/popular'], ['block' => 'css_head']);

    $this->Breadcrumbs
        ->add(__('Giochi'), ['_name' => 'quiz:index'])
        ->add(__('Per argomento'), ['_name' => 'quiz:categories:archive']);
?>

<?php $this->append('css_head--inline') ?>
    .breadcrumb-categories { background-color:white;}
    i.fa.fa-folder-o, i.fa.fa-folder-open-o, .browse-directory-current {color:#00adee;font-weight:bold;}

    .browse-directory-child {color:#4cc5f3;}
    .browse-directory-child .fa-folder-o {color:#4cc5f3 !important}


    .well-search {background-color:#dadada !important}
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
            <a style="margin-left:<?= $margin ?>px;<?= $isRoot ? 'color:orange !important' : '' ?>"  href="<?= $this->Url->build(['_name' => 'quiz:categories:archive']) ?>" class="browse-directory-current">
                <span class="font-size-md3">
                    <i class="fa fa-2x fa-folder-open-o" style="<?= $isRoot ? 'color:orange !important' : '' ?>"></i>
                    <?= __('Tutte le categorie') ?>
                </span>
            </a>
        </div>


        <?php $CategoryCurrent = end($Category) ?>
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
                <a style="margin-left:<?= $margin ?>px"  href="<?= $this->Url->build(['_name' => 'quiz:categories:archive-slug', 'id' => $CategoryPath->id, 'title' => $CategoryPath->slug]) ?>" class="browse-directory-current">
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
            <a class="browse-directory-child" style="margin-left:<?= $margin ?>px" href="<?= $this->Url->build(['_name' => 'quiz:categories:archive-slug', 'id' => $category->id, 'title' => $category->slug]) ?>">
                <span class="font-size-md3">
                    <i class="fa fa-2x fa-folder-o"></i>
                    <?php echo $category->name ?>
                </span>
            </a>
        </div>
        <?php endforeach ?>
    </div>
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
            echo $this->Form->control('type', [
                'label'   => __('Tipologia quiz'),
                'type'    => 'select',
                'default' => '',
                'options' => [
                    // quizzes di tipologia default/funjob fatti solo dai bigbrains
                    'bigbrain'  => __('Giochi FunJob certificati (1 o più livelli)'),

                    // quizzes di tipologia funjob
                    'funjob'    => __('Giochi FunJob certificati (solo multi-livello)'),

                    // quizzes di tipologia default
                    'default'   => __('Giochi Utenti'),

                    // tutti i quizzes
                    ''          => __('Tutti i quiz'),
                ]
            ]);

            echo $this->Form->control('term', [
                'label'       => __('Cerca parola chiave'),
                'placeholder' => __('Digita parola chiave')
            ]);

            echo $this->Form->control('sort_by', [
                'label'   => __('Ordinamento in base a'),
                //'empty'   => __('-- Seleziona ordinamento'),
                'type'    => 'select',
                'default' => 'created',
                'options' => [
                    'rank'    => __('I più votati dagli utenti'),
                    'created' => __('Per ordine di inserimento'),
                ]
            ]);

            echo $this->Form->button(
                __('{icon} Filtra', ['icon' => '<i class="fa fa-filter"></i>']),
                ['class' => 'btn btn-sm btn-primary']
            );

            echo $this->Form->button(
                '<i class="fa fa-arrow-up"></i> '. __('Chiudi'),
                ['class' => 'btn btn-sm btn-secondary js-filter-btn', 'type' => 'button', 'style' => 'margin-left:5px']
            );

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
                        'Apri {button} per filtrare i Giochi delle sottocategorie di {name}', [
                        'name'   => '<strong>"' .$CategoryCurrent->name. '"</strong>',
                        'button' => '<strong>"' .__('Ricerca Avanzata'). '"</strong>'
                    ])
                ?>
            </p>
            <button class="js-filter-btn btn btn-primary btn-block">
                <div class="text-center">
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

    <?php echo $this->fetch('filters') ?>

    <div class="row gutter-10" id="results">
        <?php foreach ($results as $quiz) : ?>
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
            <section class="quiz-entity <?= $quiz->type == 'funjob' ? 'quiz-entity--funjob' : 'quiz-entity--default' ?> ">

                <div class="quiz-entity-image">
                    <a class="display-block text-center text-truncate" href="<?= $this->Url->build($quiz->url) ?>">
                        <?php if (strpos($quiz->cover_src_original, 'uploads/')) : ?>
                            <img class="img-responsive" src="<?= $quiz->imageSize($quiz->cover_src_original, '500x300') ?>" alt="">
                        <?php else: ?>
                            <img class="img-responsive" src="<?= $quiz->cover_src_original ?>" alt="">
                        <?php endif ?>
                    </a>
                </div>

                <div class="quiz-entity-content" style="background-color:<?= $quiz->color ?>">
                    <header>
                        <div class="quiz-entity-avatar">
                            <a href="<?= $this->Url->build($quiz->author->url) ?>">
                                <img class="img-circle" data-toggle="popover" data-content="<?= $quiz->author->username ?> <span class='text-muted font-size-sm'>Click per visualizzare profilo</span>" src="<?= $quiz->author->imageSize($quiz->author->avatarSrc, '28x28') ?>" alt="">
                            </a>
                        </div>
                        <footer class="quiz-entity-footer">
                            <div class="pull-right">

                                <?php // $this->request->getQuery('sort_by', 'created') == 'rank' ?>
                                <?php if ($this->request->getQuery('sort_by', 'created') == 'rank') : ?>
                                <a data-toggle="popover" data-content="<?= __('La media della valutazione degli utenti') ?>" data-hover="click hover" onclick="return false;" href="#">
                                    <i class="fa fa-smile-o"></i>
                                    <?php if (!empty($quiz->_avg)) : ?>
                                        <span class="font-size-sm">
                                            <?= number_format($quiz->_avg, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="font-size-sm">0.0</span>
                                    <?php endif ?>
                                </a>
                                <?php endif ?>

                                |

                                <?php if ($quiz->author->is_bigbrain) : ?>
                                    <i data-toggle="popover" data-content="<?= __('Quiz certificati FunJob: a più livelli') ?>" class="quiz-entity-type-icon fontello-brain"></i>
                                    <span class="font-size-sm"><?= __('BigBrain') ?></span>
                                <?php else: ?>
                                    <i data-toggle="popover" data-content="<?= __('Quiz normali: un solo livello') ?>" class="quiz-entity-type-icon fa fa-user"></i>
                                    <span class="font-size-sm"><?= __('Utente') ?></span>
                                <?php endif ?>

                            </div>

                            <div class="clearfix"></div>
                        </footer>

                        <div class="quiz-entity-title">
                            <a class="display-block font-size-md3 text-center text-truncate" href="<?= $this->Url->build($quiz->url) ?>">
                                <?= h($quiz->title) ?>
                            </a>
                        </div>
                    </header>
                </div>
            </section>
        </div>
        <?php endforeach ?>

    </div>
<?php $this->end() ?>

<?php // Header: risultati ricerca ?>
<?php $this->start('quizzes:counter') ?>
    <?php if (!$isRoot) : ?>
    <div class="panel panel-<?= ($hasResults > 0 ? 'info' : 'danger') ?>">
        <div class="panel-heading">
            <h3 class="panel-title text-bold font-size-md1 text-center">
                <?php
                    if ($hasResults > 0) {
                        echo __(
                            '{count} GIOCHI trovati nella la categoria {name}', [
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
                                    <?= __('Nella categoria selezionata non sono presenti giochi: ti consigliamo di tornare alla categoria precedente') ?>
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

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="text-bold panel-title font-size-md3">
                    <i style="color:white" class="fa fa-folder-o"></i>
                    <?= __('Ricerca Gioco per categorie') ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php echo $this->fetch('categories') ?>
            </div>
        </div>

        <?php if (!$isRoot) : ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="text-bold panel-title font-size-md3">
                    <i class="fa fa-gamepad"></i>
                    <?=
                        __(
                            '{count} giochi trovati per {name}', [
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
        <?php endif ?>

    </div>
</div>

