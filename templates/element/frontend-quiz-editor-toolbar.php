<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;
?>

<?php $current = Router::url(); ?>
<div class="row">
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

        <?php $url = $this->Url->build(['controller' => 'Quizzes', 'action' => 'edit', $this->request->pass[0]]) ?>
        <a href="<?= $url ?>" class="btn btn-default btn-block <?= $current == $url ? 'active' : '' ?>">
            <span class="badge badge-default">1</span>
            <?php echo __('Configurazione Gioco') ?>
            <span class="help-block"><?= __('Modifica caratteristiche gioco') ?></span>
        </a>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <?php $url = $this->Url->build(['controller' => 'quiz-questions', 'action' => 'add', $this->request->pass[0]]) ?>
        <a href="<?= $url ?>" class="btn btn-default btn-block <?= $current == $url ? 'active' : '' ?>">
            <span class="badge badge-default">2</span>
            <?php echo __('Domande') ?>
            <span class="help-block"><?= __('Aggiungi domande al gioco') ?></span>
        </a>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <a href="#" class="btn btn-default btn-block js-quiz-status-modal">
            <span class="badge badge-default">3</span>
            <?php echo __('Pubblicazione') ?>
            <span class="help-block"><?= __('Permetti a gli altri di giocare il tuo gioco') ?></span>
        </a>
    </div>
</div>

<hr>

<div class="row">
    <?php $cols = $this->request->getSession()->read('Auth.User.type') == 'admin' ? 6 : 12; ?>
    <div class="col-xs-<?= $cols ?> col-sm-<?= $cols ?> col-md-<?= $cols ?> col-lg-<?= $cols ?>">
        <?php if ($quiz->type == 'default') : ?>
            <?php
                echo $this->Cell(
                    'QuizQuestionCounter::defaultCounter',
                    [$quiz]
                );
            ?>
            <div class="help-block text-center">
            <?php
                $count = Configure::readOrFail('app.quiz.default.minQuestions');
                echo __('Minimo {count} domande e nessun limite massimo', ['count' => $count])
            ?>
            </div>
        <?php else: ?>
            <?php
                echo $this->Cell(
                    'QuizQuestionCounter::funjobCounter',
                    [$quiz]
                );
            ?>
            <div class="help-block text-center">
            <?php
                $count = Configure::readOrFail('app.quiz.funjob.minQuestions');
                echo __('Minimo {count} domande (per livello) e nessun limite massimo', ['count' => $count])
            ?>
            </div>
        <?php endif ?>
    </div>

    <?php if ($this->request->getSession()->read('Auth.User.type') == 'admin') : ?>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="well">
            <h3 class="text-center text-muted">
                <i class="fa fa-legal"></i>
                <?= __('Amministratore') ?>
            </h3>

            <?php
                $this->Html->script(['/bower_components/blockUI/jquery.blockUI.js', 'admin.question.search'], ['block' => 'js_foot']);
                $this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot']);
                $this->Html->css([
                    '/bower_components/select2/dist/css/select2.min.css',
                    'features/select2-bootstrap.min.css'
                ], ['block' => 'css_foot']);
            ?>
            <script>
                config.adminPanel = {
                    csrfToken : "<?= $this->request->getParam('_csrfToken') ?>",
                    url       : "<?= $this->Url->build(['prefix' => 'Admin', 'controller' => 'quiz-questions', 'action' => 'index', '?' => ['quiz_id' => $quiz->id]]) ?>"
                };
            </script>
            <button class="js-admin-question-modal btn btn-block btn-default">
                <i class="fa fa-fw fa-search" aria-hidden="true"></i>
                <span class="visible-sm-inline visible-md-inline visible-lg-inline">
                    <?php echo __('Utilizza domande già pubblicate') ?>
                </span>
                <span class="visible-xs-inline">
                    <?php echo __('Utilizza domande altrui') ?>
                </span>
            </button>

            <?php
                echo $this->Form->create($quiz, ['id' => 'form-quiz-admin-ban', 'url' => ['prefix' => 'Admin', 'controller' => 'Quizzes', 'action' => 'disable']]);
                echo $this->Form->control('id');
                echo $this->Form->control('is_disabled', [
                    'label'  => __('Disabilita quiz (ban)'),
                    'help'   => 'Non viene visualizzato in archivio, ne mostrato nelle ricerche, ne è possibile giocarlo',
                    'escape' => false
                ]);
                echo $this->Form->end();
            ?>
            <script type="text/javascript">
                $(function() {
                    $("body").on("change", "#form-quiz-admin-ban", function(evt) {
                        var $form = $(this);
                        var $req = $.ajax({
                            method     : "POST",
                            url        : $form.attr("action"),
                            headers    : {
                                "X-Csrf-Token": "<?= $this->request->getParam('_csrfToken') ?>"
                            },
                            data       :  $form.serialize(),
                            beforeSend : function() { $.blockUI(); }
                        });

                        $req.done(function(response) {
                            if (response.message) {
                                alertify.success(response.message);
                            }
                        });
                        $req.fail(function(jxhr, textError, exception) {
                            alertify.error(textError);
                        });
                        $req.always(function() { $.unblockUI(); });
                    });
                });
            </script>

        </div>
    </div>
    <?php endif ?>
</div>


<script>
    $(function() {
        $(".js-quiz-status-modal").on("click", function(evt) {
            evt.preventDefault();
            var $req = $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Quizzes', 'action' => 'status', $quiz->id]) ?>"
            });

            $req.done(function(response) {
                bootbox.dialog({
                    title   : <?= json_encode(__('Visibilità gioco')) ?>,
                    message : response
                });
            });

            $req.fail(function(jxhr, errStatus, errThrow) {
                alert.error(errStatus);
            });
        });
    })
</script>
