<?php
    use \Cake\Core\Configure;

    $myself    = $this->UserProfile->isMyProfile();
    $isArchive = $this->request->here == $this->Url->build(['_name' => 'me:quizzes:completed']);

    $this->assign('title', __('Scegli quali giochi condividere con Utenti e Aziende nel tuo profilo'));
    $this->assign('header', '');

    if (isset($UserAuth)) {
        $this->Breadcrumbs
            ->add($UserAuth->username, ['_name' => 'user:profile', 'id' => $UserAuth->id, 'username' => $UserAuth->slug])
            ->add(__('Giochi completati'), '#');
    }

    $this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot', 'once' => true]);
    $this->Html->css(['/bower_components/select2/dist/css/select2.min.css', 'features/select2-bootstrap.min.css'], ['block' => 'css_foot', 'once' => true]);

    $this->Html->script(['/bower_components/jssocials/dist/jssocials.min.js'], ['block' => 'js_foot']);

    $this->Html->css([
        'features/bootstrap-steps-wizard',
        'quizzes/completed',

        '/bower_components/jssocials/dist/jssocials.css',
        '/bower_components/jssocials/dist/jssocials-theme-flat.css'
    ], ['block' => 'css_head']);


    // Utilizzato per User/Quizzes/played.ctp
    if (!$this->request->is('ajax')) {
        $this->Html->script(['users/view/tabs/quiz-completed'], ['block' => 'js_foot']);
    }
?>
<?php $this->start('info') ?>

    <?php if ($isArchive) : // Pagina "Risultati" ?>
        <div class="alert alert-info">
            <strong class="font-size-md2"><?= __('Sai che puoi decidere quali risultati mostrare nel tuo profilo?') ?></strong>
            <p class="font-size-md2">
                <?= __('A fianco ad ogni risultato clicca sull\'icona per decidere se mostrarlo agli altri utenti e alle altre aziende.') ?>
            </p>

            <p class="font-size-md">
                <span class="fa-stack fa-xs">
                    <i class="fa fa-eye fa-stack-1x"></i>
                </span>
                <span><?= __('Visibile sul tuo profilo') ?></span>

                <span style="display:inline-block;margin-right:20px"></span>

                <span class="fa-stack fa-xs">
                    <i class="fa fa-eye fa-stack-1x"></i>
                    <i class="fa fa-ban fa-stack-2x text-danger"></i>
                </span>
                <span><?= __('Nascosto sul tuo profilo') ?></span>
            </p>

        </div>
    <?php endif ?>

<?php $this->end() ?>

<?php // Filter: ricerca risultati ?>
<?php $this->start('search') ?>
    <div id="filter-form" class="well well-sm">
        <?php
            echo $this->Form->create(null, [
                'class'        => (!$isArchive ? 'quiz-completed-form-filter' : '') ,
                'valueSources' => ['query', 'context'],
            ]);

            echo $this->Form->control('name', [
                'label'       => __('Cerca gioco per nome'),
                'placeholder' => __('Nome del quiz'),
                'prepend'     => '<i class="fa fa-search"></i>'
            ]);

            echo '<div style="margin-bottom:15px"></div>';

            $options = [];
            foreach ($categories as $key => $counter) {
                list($id, $name) =  explode('|', $key);
                $options[ $id ] = $name .' '. __('({count} risultati)', ['count' => $counter]);
            }

            echo $this->Form->control('categories', [
                'label'       => __('Cerca gioco per categoria'),
                'empty'       => 'Tutte le categorie',
                'default'     => '',
                'options'     => $options,
                'class'       => 'js-select2',
                //'multiple'    => 'multiple',
                //'help'        => __('Puoi selezionare più categorie')
            ]);

            if ($isArchive) {
                echo $this->Form->control('visibility', [
                    'label'   => __('Visibilità (su profilo)'),
                    'options' => [
                        'all'     => __('Tutti i risultati'),
                        'visible' => __('Condivisi nel profilo'),
                        'hidden'  => __('Non condivisi')
                    ]
                ]);
            }

            echo $this->Form->button(__('Filtra'), ['class' => 'btn btn-primary btn-sm js-quiz-completed-form-filterBtn']);
            echo $this->Form->end();
        ?>
    </div>

<?php $this->end() ?>

<?php $this->start('quiz:results') ?>
    <?php foreach ($sessions as $session) : $this->QuizSession->config('entity', $session) ?>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 quiz-entity-col">
        <div class="funjob-quiz-result panel panel-sm <?= $session->quiz->type == 'default' ? 'panel-default' : 'panel-info' ?>">
            <div class="panel-heading" style="overflow:hidden">
                <div class="panel-title font-size-md3 text-truncate">
                    <?=
                        $this->Html->link(
                            h($session->quiz->title),
                            $session->quiz->url
                        )
                    ?>
                </div>
            </div>
            <div class="panel-body">
                <div class="row gutter-10">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="thumbnail">
                            <?php
                                $thumb = $session->quiz->imageSize($session->quiz->coverSrcOriginal, '300x150');
                            ?>
                            <a href="<?= $this->Url->build($session->quiz->url) ?>">
                                <img width="350" height="150" src="<?= $thumb ?>" data-src="holder.js/300x150?auto=yes&text=<?= $session->quiz->title ?>&bg=<?= $session->quiz->color ?>&fg=#ffffff" alt="" />
                            </a>
                        </div>
                    </div>

                    <?php // Livelli ?>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                <?php
                                    $level_count = $this->QuizSession->getLevels();
                                ?>
                                    <div class="stepwizard">
                                        <div class="stepwizard-row">
                                            <?php for ($i=1; $i <= $level_count; $i++) : ?>
                                            <div class="stepwizard-step">

                                                <?php
                                                if (isset($session['levels_passed'][$i - 1])) :
                                                    $href         = $this->Url->build(['_name' => 'quiz:report', $session->id]);
                                                    $sessionLevel = $session['levels_passed'][$i - 1];
                                                    $isPassed     = isset($sessionLevel);
                                                ?>
                                                <a href="<?= $href ?>" class="level-popover stepwizard-btn--passed stepwizard-btn--<?= $i ?> btn btn-default btn-circle <?= $isPassed ? 'btn-passed' : '' ?>">
                                                    <div class="funjob-quiz-level" data-hover-icon="fa fa-search">
                                                        <span class="content"><?php echo $i ?></span>
                                                        <span class="content--hover">
                                                            <i class="fa fa-search"></i>
                                                        </span>
                                                    </div>
                                                </a>
                                                <?php else: ?>
                                                <button class="level-popover stepwizard-btn--disabled stepwizard-btn--<?= $i ?> btn btn-default btn-circle">
                                                    <?php echo $i ?>
                                                </button>
                                                <?php endif ?>

                                            </div>
                                            <?php endfor ?>
                                        </div>
                                    </div>

                                    <?php if ($level_count !== false) : ?>
                                    <?php // visibility:hidden per mantenere i blocchi tutti della stessa dimensione ?>
                                    <div style="<?= $level_count == 1 ? 'visibility:hidden' : '' ?>" class="row gutter-10">
                                        <div class="col-xs-4 col-md-4">
                                            <div class="text-truncate text-center">
                                                <span class="font-size-md">
                                                    <?=  __('Facile') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="text-truncate text-center">
                                                <span class="font-size-md">
                                                    <?= __('Medio') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="text-truncate text-center">
                                                <span class="font-size-md">
                                                    <?= __('Difficile') ?>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <?php endif ?>

                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 funjob-quiz-result-finalScore">

                                <div class="row gutter-10">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-truncate text-center">
                                            <span class="text-bold text-color-gray--dark">
                                                <i class="text-color-primary fa fa-bar-chart"></i>
                                                <?php
                                                    echo __('{score} corrette su {score_max}', [
                                                        'score'     => $this->QuizSession->getFinalScore(),
                                                        'score_max' => $this->QuizSession->getFinalScoreMax()
                                                    ])
                                                ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-truncate text-center text-bold">
                                            <?php
                                                $scoreTitle = $this->QuizSession->getFinalScoreTitle();
                                                echo $scoreTitle['icon'];
                                            ?>
                                            <span class="text-color-gray--dark">
                                                <?php echo $scoreTitle['text'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <?php if ($myself) : ?>
            <div class="panel-footer">
                <div class="row gutter-10">

                    <?php // Punteggio totale + sotto menù ?>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 visibility-buttons">

                        <a href="#" class="btn btn-default btn-xs btn-block js-quiz-session-visibility-btn" data-content="<?= __('Nascondi da profilo') ?>">

                            <span class="fa-stack fa-xs">
                                <i class="fa fa-eye fa-stack-1x"></i>
                                <?php if (!$session->is_hidden) : ?>
                                <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                <?php endif ?>
                            </span>

                            <span class="hidden-md hidden-lg">
                                <?php if ($session->is_hidden) : ?>
                                    <?php echo __('Mostra') ?>
                                <?php else: ?>
                                    <?php echo __('Nascondi') ?>
                                <?php endif ?>
                            </span>

                            <?php
                                echo $this->Form->create($session, [
                                    'url' => ['prefix' => 'user', 'controller' => 'QuizSessions', 'action' => 'edit'],
                                    'id'  => 'quiz-session-form-visibility-' . $session->id
                                ]);
                                echo $this->Form->control('id', ['type' => 'hidden']);

                                if ($session->is_hidden) {
                                    echo $this->Form->control('is_hidden', ['type' => 'hidden', 'value' => 0]);
                                } else {
                                    echo $this->Form->control('is_hidden', ['type' => 'hidden', 'value' => 1]);
                                }
                                echo $this->Form->end();
                            ?>
                        </a>

                        <a href="<?= $this->Url->build(['prefix' => 'user', 'controller' => 'QuizSessions', 'action' => 'delete', 0 => $session->id]) ?>" class="btn btn-danger btn-xs btn-block js-quiz-session-delete-btn" data-content="<?= __('Elimina sessione di gioco') ?>">

                            <span class="fa-stack fa-xs">
                                <i class="fa fa-trash fa-stack-1x"></i>
                            </span>
                            <span class="hidden-md hidden-lg">
                                <?php echo __('Elimina') ?>
                            </span>
                        </a>

                        <a data-toggle="popover" data-content="<?= __('Ri gioca') ?>" href="<?= $this->Url->build($session->quiz->url) ?>" class="btn-block btn btn-xs btn-default">
                            <span class="fa-stack fa-xs">
                                <i class="fa fa-gamepad fa-stack-1x text-color-primary"></i>
                            </span>
                            <span class="hidden-md hidden-lg">
                                <?php echo __('Ri-gioca') ?>
                            </span>
                        </a>

                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 action-buttons">
                        <a href="#" class="modal-share--<?= $session->quiz->id ?> btn btn-default btn-xs btn-block" data-toggle="popover" data-content="<?= __('Condividi risultato') ?>" data-trigger="hover">
                            <span class="fa-stack fa-xs">
                                <i class="fa fa-share fa-stack-1x text-color-primary"></i>
                            </span>
                            <span class="hidden-md hidden-lg">
                                <?php echo __('Condividi') ?>
                            </span>

                            <script>
                                $(function() {
                                    $(".modal-share--<?= $session->quiz->id ?>").on("click", function(evt) {
                                        evt.preventDefault();
                                        var modal = bootbox.dialog({
                                            message : function() {
                                                return $("#tpl-modal-share").html()
                                            }
                                        });

                                        modal.on('shown.bs.modal', function () {
                                            $("#share-buttons").jsSocials({
                                                showLabel : false,
                                                showCount : false,
                                                shareIn   : "popup",
                                                text      : <?= json_encode($session->quiz['title']) ?>,
                                                url       : "<?= $this->Url->build($session->url, true) ?>",
                                                shares    : [
                                                   "twitter",
                                                   "facebook",
                                                   "googleplus",
                                                   "email",
                                                   "whatsapp"
                                                ]
                                            });
                                        });
                                    });
                                });
                            </script>
                        </a>

                        <a href="<?= $this->Url->build(['_name' => 'quiz:report', $session->id]) ?>" class="btn btn-default btn-xs btn-block" data-toggle="popover" data-content="<?= __('Mostra dettaglio Punteggio') ?>" data-trigger="hover">
                            <span class="fa-stack fa-xs">
                                <i class="fa fa-search-plus fa-stack-1x text-color-primary"></i>
                            </span>
                            <span class="hidden-md hidden-lg">
                                <?php echo __('Punteggio') ?>
                            </span>
                        </a>
                    </div>

                </div>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endforeach ?>
<?php $this->end() ?>

<?php // Rendereizza CSS inline (nel template ajax non c'è il rendere del blocco css_head--inline) ?>
<?php if ($this->request->is('ajax')) : ?>
    <?= $this->fetch('css_head') ?>
    <style><?= $this->fetch('css_head--inline') ?></style>
    <?= $this->fetch('js_foot') ?>
<?php endif ?>


<div class="<?= $isArchive ? 'tab-pane active' : '' ?> app-user-profile-quiz--completed">
    <?php if ($sessions->isEmpty()) : ?>
        <p class="font-size-md text-muted text-center">
            <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
            <?php if ($myself) : ?>
            <?= __x('Messaggio su "Quiz Completati" nel profilo utente', 'Non hai ancora ancora completato nessun gioco') ?>

            <a href="<?= $this->Url->build(['_name' => 'quiz:index']) ?>" class="btn btn-success btn-block">
                <?= __('Inizia a giocare') ?>
            </a>
            <?php else: ?>
            <?= __x('Messaggio su "Quiz Completati" nel profilo utente', 'Questo utente non ha ancora completato nessun gioco') ?>
            <?php endif ?>
        </p>

    <?php else: ?>

        <div class="row gutter-10">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <?php echo $this->fetch('search') ?>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="well well-sm" style="padding:4px">

                    <a href="#" class="text-bold btn btn-sm btn-block btn-info js-funjob-result-point-legend-link">
                        <?= __('Regolamento punteggio') ?>
                    </a>
                    <span class="help-block" style="margin-left:5px">
                        <?= __('Come vengono assegnati i punteggi') ?>
                    </span>

                    <script id="tpl-funjob-points-legend" type="text/template">
                        <div class="margin-top--lg"></div>
                        <?php echo $this->QuizSession->getLegend() ?>
                    </script>
                    <script type="text/javascript">
                        $(function() {
                            $(".js-funjob-result-point-legend-link").on("click", function(evt) {
                                evt.preventDefault();

                                bootbox.dialog({
                                    className : "funjob-modal",
                                    title     : <?= json_encode(__('{icon} Legenda punteggi', ['icon' => '<i class="fa fa-file-text-o"></i>'])) ?>,
                                    message   : function() {
                                        return $("#tpl-funjob-points-legend").html()
                                    }
                                })
                            });
                        })
                    </script>

                    <?php if (!$isArchive && $myself) : ?>
                        <hr style="margin-top:13px;margin-bottom:13px">
                        <a class="text-bold btn btn-sm btn-primary btn-block" href="<?= $this->Url->build(['_name' => 'me:quizzes:completed']) ?>">
                            <strong><?= __('Mostra tutti i risultati') ?></strong>
                        </a>
                        <span class="help-block" style="margin-left:5px">
                            <?= __('Ricorda che se tu che decidere i Giochi condivisi') ?>
                        </span>
                    <?php endif ?>
                </div>
            </div>

        </div>

        <div class="row gutter-10">
            <?php echo $this->fetch('quiz:results') ?>
        </div>
        <div class="row gutter-10">
            <?php echo $this->element('pagination') ?>
        </div>
    <?php endif ?>

</div>


<?php // TEMPLATE POPOVER: Livello blocccato ?>
<script type="text/template" id="tpl-level-locked">
    <p class="text-danger">
        <i class="fa fa-lock"></i>
        <?= __('Livello bloccato') ?>
    </p>
    <p class="text-muted"><?= __x('Livello bloccato', 'Per procedere devi superare i precedenti') ?></p>
</script>
<?php // TEMPLATE POPOVER: Livello superato ?>
<script type="text/template" id="tpl-level-passed">
    <p class="text-success">
        <i class="fa fa-unlock"></i>
        <?= __('Livello superato') ?>
    </p>
    <p class="text-muted"><?= __x('popover su bottone livello', 'Fare click per maggiori dettagli') ?></p>
</script>
<?php // TEMPLATE: SHARE ?>
<script type="text/template" id="tpl-modal-share">
    <div class="container-fluid">
        <p class="font-size-lg text-center"><?= __('Condividi questo risultato con i tuoi amici') ?></p>
        <div class="text-center" id="share-buttons"></div>
    </div>
</script>

<script>
    var i18n = i18n || {};
    i18n.close = <?= json_encode(__('Chiudi')) ?>;
    i18n.confirmMessage = <?= json_encode('Sei sicuro?') ?>;

    i18n.game_session = {};
    i18n.game_session.title = <?= json_encode(__('Sessione di gioco')) ?>;
    i18n.game_session.confirmDelete = <?= json_encode(__('Sei sicuro di voler cancellare i punteggi ottenuti di questo gioco?')) ?>;
</script>
<script>
    $(function() {

        $(".js-select2").select2({
            width: "100%",
            theme: "bootstrap"
        })

        <?php if (!$this->request->is('mobile') || !$this->request->is('tabled')) : ?>
        $(".js-quiz-session-visibility-btn, .js-quiz-session-delete-btn").popover({
            placement: "top",
            trigger  : "hover"
        });

        $("*[data-toggle=popover]").popover({
            placement: "top",
        });
        <?php endif ?>

        jsSocials.setDefaults("twitter", {
            via      : "funjob",
            hashtags : "funjob,quiz,games"
        });
    })
</script>
