<?php
    $this->assign('header', ' ');

    $this->Html->css(['quizzes/index', 'quizzes/popular'], ['block' => 'css_head']);
    $this->Html->css([
        '/bower_components/select2/dist/css/select2.min.css',
        'features/select2-bootstrap.min.css'
    ], ['block' => 'css_foot']);
    $this->Html->script([
        '/bower_components/select2/dist/js/select2.min.js'
    ], ['block' => 'js_foot']);

    if ($this->request->getQuery('do') == 'search') {
        $title = __('Ricerca personalizzata');
    } elseif ($this->request->getQuery('sort_by') == 'created') {
        $title = __('Ultimi inseriti');
    } elseif ($this->request->getQuery('sort_by') == 'rank') {
        $title = __('Più votati');
    } else {
        $title = __('Ultimi inseriti');
    }

    $this->assign('title', $title);


    $this->Breadcrumbs
        ->add(__('Quizzes'), ['_name' => 'quiz:index'])
        ->add($title, $this->request->getAttribute('here'));
?>

<?php if (!$this->request->is('mobile')) : ?>
    <div class="row visible-md visible-lg">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <img src="img/header-play.jpg" alt="" class="img-responsive">
        </div>
    </div>
    <div class="margin-top--md"></div>
<?php endif ?>

<div class="well">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
                echo $this->element('ui/alert-cookie', [
                    'cookie'  => 'alert__quiz_filters_show',
                    'title'   => '', // __('Filtra i quiz in base al tuo interesse'),
                    'message' => (
                        '<i class="fa fa-info-circle"></i>  ' .
                        __('Filtra i quiz in base al tuo interesse, categoria, tipologia di autori, punteggio assegnato dagli utenti, cronologia etc.')
                    )
                ])
            ?>
        </div>
    </div>

    <div class="row quiz-filter-row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <button class="app-js-filter-visibility btn btn-sm btn-block btn-primary">

                <div class="pull-left font-size-md2">
                    <i class="fa fa-2x fa-search"></i>
                    <span class="text-bold text-uppercase">
                        <?php echo __('Ricerca avanzata') ?>
                    </span>
                </div>
                <span class="app-js-filter-visibility-status font-size-md pull-right">
                    <?= __('Apri {icon}', ['icon' => '<i class="fa fa-angle-double-down"></i>']) ?>
                </span>
            </button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <a class="clearfix btn btn-sm btn-block btn-primary" href="<?= $this->Url->build(['_name' => 'quiz:categories:archive']) ?>">
                <div class="font-size-md2 text-center">
                    <i class="fa fa-2x fa-folder-o"></i>
                    <span class="text-bold text-uppercase">
                        <?php echo __('Ricerca per Categoria') ?>
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div style="display:none;margin-top:10px" id="funjob-quiz-archive-filters">
        <?php $compress = false; // (bool) $this->request->getCookie('alert__quiz_filters_show') === false ?>
        <div class="<?= $compress ? 'col-xs-12 col-sm-6 col-md-6 col-lg-6' : 'col-xs-12 col-sm-12 col-md-12 col-lg-12' ?>">
            <?php
                echo $this->Form->setValueSources(['query'])->create(null, ['method' => 'POST', 'class' => 'well well-sm']);
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

                // echo '<label>Materia</label>';
                // $this->Form->unlockField('categories._ids');
                // $selector = 'quiz-category-jstree-' .String::uuid();
                // echo $this->cell(
                //     'QuizCategoriesJsTree',
                //     [$selector],
                //     []
                // );

                // echo $this->Form->control('categories._ids', [
                //     'type'    => 'hidden',
                //     'id'      => $selector. '-selected',
                //     'default' => $this->request->getQuery('category', ''), // auto-fill categoria da URL
                // ]);

                // echo $this->Form->control('category', [
                //     'id'      => 'category-tree',
                //     'label'   => __('Categoria quiz '),
                //     'empty'   => __('Tutte le categorie'),
                //     'type'    => 'select',
                //     'options' => $categories,
                //     'escape'  => false,
                // ]);

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
                        'rank'    => __('I più votati'),
                        'created' => __('Ultimi giochi inseriti'),
                    ]
                ]);

                echo $this->Form->control('do', ['type' => 'hidden', 'value' => 'search']);
                echo $this->Form->button(
                    __('{icon} Filtra', ['icon' => '<i class="fa fa-filter"></i>']),
                    ['class' => 'btn btn-sm btn-primary btn-block']
                );

                echo $this->Form->end(['security' => 'hidden']);
            ?>
            <script>
                $(function() {
                    $('#category-tree').select2({
                        dropdownAutoWidth : true,
                        width             : "100%",
                        theme             : "bootstrap"
                    });
                })
            </script>
        </div>
    </div>
</div>
<hr style="margin-top:20px;margin-bottom:20px">


<div class="row">
    <div class="hiddex-xs hiddex-sm col-md-6 col-lg-6">
        <h1 class="no-margin text-bold font-size-lg text-color-primary">
            <i style="font-size:20px;color:#00adee;margin-right:2px;" class=""></i>
            <?= $this->fetch('title') ?>
        </h1>
    </div>

    <?php if (!$this->request->getQuery('do')) : ?>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="pull-right">

            <!--
            <a href="<?= $this->Url->build(['_name' => 'quiz:categories:archive']) ?>" class="btn btn-funjob">
                <i class="fa fa-search"></i>
                <?= __('Per categoria') ?>
            </a>
            -->

            <a href="<?= $this->Url->build(['_name' => 'quiz:index', '?' => ['sort_by' => 'created']]) ?>" class="btn btn-funjob <?= $this->request->getQuery('sort_by', 'created') == 'created' ? 'active' : '' ?>">
                <?= __('Ultimi giochi inseriti') ?>
            </a>
            <a href="<?= $this->Url->build(['_name' => 'quiz:index', '?' => ['sort_by' => 'rank']]) ?>" class="btn btn-funjob <?= $this->request->getQuery('sort_by') == 'rank' ? 'active' : '' ?>">
                <?= __('Giochi più votati') ?>
            </a>

        </div>
    </div>
    <?php endif ?>
</div>
<div class="margin-top--lg"></div>

<div class="row gutter-10">
    <?php foreach ($quizzes as $quiz) : ?>
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

    <?php if ($quizzes->isEmpty()) : $this->assign('heading', ' ') ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p class="text-center text-muted font-size-md">
                <?= __('Nessun gioco trovato con i tuoi criteri di ricerca') ?>
            </p>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="text-center">
                <a href="<?= $this->Url->build(['?' => []]) ?>" class="btn btn-sm btn-default">
                    <i class="fa fa-remove"></i>
                    <?php echo __('Elimina criteri di ricerca personalizzati') ?>
                </a>
            </div>
        </div>
    </div>
    <?php endif ?>

</div>

<script id="funjob-js-template-open" type="text/template">
    <?= __('Apri {icon}', ['icon' => '<i class="fa fa-angle-double-down"></i>']) ?>
</script>
<script id="funjob-js-template-close" type="text/template">
    <?= __('Chiudi {icon}', ['icon' => '<i class="fa fa-angle-double-up"></i>']) ?>
</script>
<script>
    $(function() {

        $(".app-js-filter-visibility").on("click", function(evt) {
            var $element = $("#funjob-quiz-archive-filters");
            if (!$element) { return; }

            $element.slideToggle(function(evt) {
                var $this   = $(this);
                var $status = $(".app-js-filter-visibility-status");

                if ($this.is(':visible')) {
                    $status.html( $("#funjob-js-template-close").html() );
                } else {
                    $status.html( $("#funjob-js-template-open").html() );
                }
            });
        });

        $("*[data-toggle=popover]").popover({
            container : "body",
            trigger   : "hover click",
            html      : true
        });

        /*
        $(".quiz-entity-descr").on("click", function(evt) {
            evt.preventDefault();
            var $req = $.ajax({
                url: this.dataset.url
            });
            $req.done(function(buffer) {
                bootbox.dialog({
                    message : buffer,
                    size    : "large"
                })
                //return buffer;
            });
        });
        */
    });
</script>
<?php echo $this->element('pagination') ?>
