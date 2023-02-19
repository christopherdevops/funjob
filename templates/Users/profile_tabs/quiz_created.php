<?php
    $this->assign('title', __('Quiz creati'));
    $this->assign('header', ' ');

    echo $this->Html->css(['quizzes/index', 'quizzes/popular']);

    $this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot', 'once' => true]);
    $this->Html->css(['/bower_components/select2/dist/css/select2.min.css', 'features/select2-bootstrap.min.css'], ['block' => 'css_foot', 'once' => true]);

    $myself = $this->request->getSession()->read('Auth.User.id') == $this->request->getParam('pass.0');
?>

<?php $this->start('search') ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-sm">
                <?php
                    echo $this->Form->create(null, [
                        'class'        => 'quiz-created-form-filter',
                        'valueSources' => ['query', 'context'],
                    ]);

                    echo $this->Form->control('name', [
                        'label'       => false,
                        'placeholder' => __('Nome del quiz'),
                        'prepend'     => '<i class="fa fa-search"></i>'
                    ]);

                    $options = [];
                    foreach ($categories as $key => $counter) {
                        list($id, $name) =  explode('|', $key);
                        $options[ $id ] = $name .' '. __('({count} risultati)', ['count' => $counter]);
                    }

                    echo $this->Form->control('categories', [
                        'label'       => __('Categoria'),
                        'empty'       => 'Tutte le categorie',
                        'default'     => '',
                        'options'     => $options,
                        'class'       => 'js-select2',
                        //'multiple'    => 'multiple',
                        //'help'        => __('Puoi selezionare più categorie')
                    ]);

                    echo $this->Form->button(__('Filtra'), ['class' => 'btn btn-primary btn-sm js-quiz-completed-form-filterBtn']);
                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
    <hr>
    <script type="text/javascript">
        $(function() {
            $(".js-select2").select2({
                width: "100%",
                theme: "bootstrap"
            })
        })
    </script>
<?php $this->end() ?>

<?php echo $this->fetch('search') ?>


<?php if ($myself) : ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="pull-right">
            <a href="<?= $this->Url->build(['_name' => 'me:quizzes']) ?>" class="btn btn-sm btn-default">
                <i class="fa fa-gamepad"></i>
                <?= __('I tuoi giochi') ?>
            </a>

            <a href="<?= $this->Url->build(['_name' => 'quiz:create']) ?>" class="btn btn-sm btn-success">
                <i class="fontello-quiz-new"></i>
                <?= __('Crea nuovo') ?>
            </a>
        </div>
    </div>
</div>
<?php endif ?>

<?php if ($quizzes->isEmpty()) : ?>
<p class="font-size-md text-muted text-center">
    <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
    <?= __('Nessun gioco creato') ?>
</p>
<?php endif ?>

<div class="row gutter-10">
    <?php foreach ($quizzes as $quiz) : ?>
    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 quiz-entity-col">
        <section class="quiz-entity <?= $quiz->type == 'funjob' ? 'quiz-entity--funjob' : 'quiz-entity--default' ?> ">

            <div class="quiz-entity-image">
                <a class="display-block text-center text-truncate" href="<?= $this->Url->build($quiz->url) ?>">
                    <img class="img-responsive" src="<?= $quiz->imageSize($quiz->cover_src_original, '500x300') ?>" data-src="holder.js/500x300?auto=yes&text=<?= $quiz->title ?>&bg=<?= $quiz->color ?>&fg=#ffffff" alt="">
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

                            <?php if ($quiz->type == 'funjob') : ?>
                                <i data-toggle="popover" data-content="<?= __('Quiz certificati FunJob: a più livelli') ?>" class="quiz-entity-type-icon fontello-brain"></i>
                                <span class="font-size-sm">BigBrain</span>
                            <?php else: ?>
                                <i data-toggle="popover" data-content="<?= __('Quiz normali: un solo livello') ?>" class="quiz-entity-type-icon fa fa-user"></i>
                                <span class="font-size-sm">Utente</span>
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
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo $this->element('pagination') ?>
    </div>
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
