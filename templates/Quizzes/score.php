<?php
    $this->assign('title', __('{0}', $quiz->title));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Quiz'), ['_name' => 'quiz:index'])
        ->add($quiz->title, $quiz->url)
        ->add(__('Risultato'));

    $this->Html->script([
        '/bower_components/blockUI/jquery.blockUI.js',
        '/bower_components/jssocials/dist/jssocials.min.js'
    ], ['block' => 'js_foot']);

    $this->Html->css([
        'quizzes/score',
        '/bower_components/jssocials/dist/jssocials.css',
        '/bower_components/jssocials/dist/jssocials-theme-flat.css'
    ], ['block' => 'css_head']);

    $this->Html->script(['/bower_components/jssocials/dist/jssocials.min.js'], ['block' => 'js_foot']);


    $this->Html->meta('og:image:width', 500, ['block' => true]);
    $this->Html->meta('og:image:height', 300, ['block' => true]);
    $this->Html->meta('og:image', $this->Url->build($quiz->imageSize($quiz->cover_src_original, '500x300'), true), ['block' => true]);

    $this->Html->meta('og:title', $quiz->title, ['block' => true]);
    $this->Html->meta('og:descr', $quiz->descr_small, ['block' => true]);
    $this->Html->meta('og:url', $this->Url->build($quiz->url, true), ['block' => true]);
?>

<?php // CONDIVIDI QUIZ ?>
<?php $this->start('quiz:share') ?>
    <div id="quiz" class="share-buttons"></div>
    <?php $this->append('js_foot') ?>
    <script>
        $(function() {
            $(".share-buttons").jsSocials({
                showLabel : false,
                showCount : false,
                shareIn   : "popup",
                text      : <?= json_encode(__('Sfida i tuoi amici su: ‹{title}›', ['title' => $quiz->title])) ?>,
                url       : "<?= $this->Url->build($quiz->url, true) ?>",
                shares    : [
                   "facebook",
                   "googleplus",
                   "twitter",
                   "email",
                   "whatsapp"
                ]
            });
        });
    </script>
    <?php $this->end() ?>
<?php $this->end() ?>

<?php // CONDIVIDI PUNTEGGIO GIOCATA ?>
<?php $this->start('quiz-session:share') ?>
    <div class="clearfix">
        <h5 class="text-bold font-size-m2">
            <?= __('Condividi risultato') ?>
            <?php echo $this->Ui->helpPopover(['text' => __('Dimostra a tutti il tuo talento condividendo il tuo risultato')]) ?>
        </h5>

        <div class="funjob-quiz-score-share"></div>
    </div>

    <?php $this->append('js_foot') ?>
    <script>
        $(function() {
            $(".funjob-quiz-score-share").jsSocials({
                showLabel : false,
                showCount : false,
                shareIn   : "popup",
                text      : <?= json_encode(__('Ho ottenuto un punteggio di {score} su ‹{title}›', ['title' => $quiz->title, 'score' => 13])) ?>,
                url       : "<?= $this->Url->build($quiz->url, true) ?>",
                shares    : [
                   "facebook",
                   "googleplus",
                   "twitter",
                   "email",
                   "whatsapp"
                ]
            });
        });
    </script>
    <?php $this->end() ?>
<?php $this->end() ?>

<?php // PUNTEGGIO QUIZ OTTENUTO ?>
<?php $this->start('scores') ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p class="font-size-md2">
                <?= __x('contatore risposte esatte', '{0} su {1} domande', $score, 10) ?>
            </p>
        </div>
    </div>
<?php $this->end() ?>

<?php // PIX GUADAGNATI PROVENIENTI DA ADV ?>
<?php $this->start('pix:earned') ?>
    <?php if (true || $pix_earned > 0) : ?>
    <div class="funjob-quiz-pix-earned">
        <p class="funjob-quiz-pix-earned-title font-size-md3 text-center">
            <?php
                echo __(
                    'Hai guadagnato {total} PIX', [
                        'total' => $pix_earned
                ])
            ?>
        </p>

        <div class="coins">
            <?php $delay = 0; for ($i=0; $i <= 9; $i++) : $delay += 0.80; ?>
            <div style="<?= $i > 0 && $i <= $pix_earned ? '' : 'visibility:hidden' ?>;animation-delay: <?= $i > 0 ? $delay : 0; ?>s;animation-duration:1s !important" class="coin animate animated flip">
                <i class="fontello-brain"></i>
            </div>
            <?php endfor ?>
        </div>
    </div>
    <?php endif ?>
<?php $this->end() ?>

<?php // DOMANDE/RISPOSTE DELLA SESSIONE DI QUIZ ?>
<?php $this->start('replies') ?>
    <ul class="list-group">
        <?php
            foreach ($replies as $questionData) :
                $Question              = $questionData['Question'];
                $QuestionAnswerCorrect = $this->QuizAnswer->getCorrect($Question->quiz_answers);
        ?>

        <li class="list-group-item" style="overflow:auto;padding:5px">
            <div class="row gutter-10">
                <?php if ($questionData['is_correct']) : ?>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        <div class="text-center">
                            <span style="color:green;" class="fa fa-2x fa-star"></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        <div class="text-center">
                            <span style="color:red;" class="fa fa-2x fa-star-o"></span>
                        </div>
                    </div>
                <?php endif ?>

                <div class="col-xs-10 col-sm-10 col-md-8 col-md-8">
                    <quote><?php echo $Question->question ?></quote>
                </div>

                <div style="text-align:right" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">

                    <?php if ($questionData['is_correct']) : ?>
                    <a class="btn btn-xs btn-info" href="#" onclick="javascript:return false;" data-placement="left" data-trigger="click" data-toggle="popover" data-content="<?= $this->QuizAnswer->answer($QuestionAnswerCorrect->answer)  ?>">
                        <i class="fa fa-search-plus"></i>
                        <br>
                        <?php echo __('Risposta') ?>
                    </a>
                    <?php endif ?>

                    <?php if (!empty($Question->source_url)): ?>
                    <a class="btn btn-xs btn-info" target="_blank" href="<?= $Question->source_url ?>">
                        <i class="fa fa-link"></i>
                        <br>
                        <?php echo __('Fonte') ?>
                    </a>
                    <?php elseif (!empty($Question->source_book_photo)) : ?>
                    <a class="btn btn-xs btn-info" href="#" id="modal-source<?= $Question->id?>-btn">
                        <i class="fa fa-link"></i>
                        <br>
                        <?php echo __('Fonte') ?>
                    </a>
                    <script type="text/template" id="modal-source<?= $Question->id ?>-template">
                        <div class="page-header">
                            <h3 class="font-size-md text-center"><?= $Question->source_book_title ?></h3>
                            <small class="display-block font-size-sm text-center">
                                <?= __('Pagina n° {page}', ['page' => (int) $Question->source_book_page]) ?>
                            </small>
                        </div>
                        <div class="margin-top--lg"></div>
                        <div class="thumbnail">
                            <a href="<?= $Question->source_book_photo__dir . '/'. $Question->source_book_photo ?>" target="_blank">
                                <img class="text-center img-responsive" src="<?= $Question->source_book_photo__dir . '/'. $Question->source_book_photo ?>" alt="">
                            </a>
                            <div class="caption">
                                <p class="text-center font-size-md">
                                    <?= __('Clicca sulla foto per aprire su un\'altra scheda la pagina') ?>
                                </p>
                            </div>
                        </div>
                    </script>
                    <script>
                        $("#modal-source<?= $Question->id ?>-btn").on("click", function(evt) {
                            evt.preventDefault();
                            bootbox.dialog({
                                title     : <?= json_encode(__('Fonte')) ?>,
                                className : "funjob-modal",
                                message   : function() {
                                    return $("#modal-source<?= $Question->id ?>-template").html();
                                }
                            })
                        });
                    </script>
                    <?php endif ?>

                    <a class="btn btn-xs btn-danger js-report-question" data-template="quiz-question-reporting--<?= $Question->id ?>" href="#">
                        <i class="fa fa-thumbs-o-down"></i>
                        <br>
                        <?php echo __('Segnala') ?>
                    </a>
                    <script type="text/template" id="quiz-question-reporting--<?= $Question->id ?>">

                        <?php
                            echo $this->Form->create(null, ['url' => ['controller' => 'QuizQuestionReportings', 'action' => 'add']]);
                            echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $quiz->id]);
                            echo $this->Form->control('user_id', [
                                'type'  => 'hidden',
                                'value' => $this->request->session()->read('Auth.User.id')
                            ]);
                            echo $this->Form->control('question_id', ['type' => 'hidden', 'value' => $Question->id]);
                            echo $this->Form->control('reason', [
                                'label' => __('Motivazione della segnalazione'),
                                'type' => 'text'
                            ]);
                            echo $this->Form->button(__('Segnala'), ['class' => 'btn btn-danger btn-block btn-sm']);
                            echo $this->Form->end();
                        ?>
                    </script>

                </div>
            </div>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->end() ?>

<?php // RISULTATO QUIZ IN BASE AL PUNTEGGIO ?>
<?php $this->start('QuizSession:status') ?>
    <div class="alert alert-md alert-<?= $is_passed ? 'success' : 'danger' ?> quiz-result-preview" style="overflow:hidden">
        <div data-style="<?= !$is_passed ? 'visibility:hidden' : '' ?>">
            <?php echo $this->fetch('pix:earned') ?>
        </div>
        <?php if (true && $is_passed) : ?>
            <?php
                // // PIX gudagnati
                // if ($this->request->session()->read($quizSessionPath . '._adv') == true) {
                //     echo $this->fetch('pix:earned');
                // }
            ?>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 class="text-bold font-size-md2"><?= __('Punteggio ottenuto') ?></h3>

                    <div class="pull-left">
                        <?php for ($i=1; $i <= 13; $i++) : ?>
                            <i class="fa fa-star<?= $i > $score_extra ? '-o' : '' ?> <?= $i > 10 ? 'star--extra' : '' ?>"></i>
                        <?php endfor ?>
                        <a class="funjob-quiz-score-star-extra-help" href="#" data-trigger="hover" data-toggle="popover" data-content="<?= __('+1 punto bonus per ogni aiuto non utilizzato (se rispondi correttamente a tutte le domande)') ?>">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </div>
                    <div class="pull-right">
                        <span class="font-size-md"><?= $score_extra ?>/13</span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 class="text-bold font-size-md2">
                        <?php
                            echo __('Il tuo miglior punteggio (mostrato nel profilo)');
                        ?>
                    </h3>

                    <div class="clearfix">
                        <div class="pull-left">

                            <?php for ($i=1; $i <= 13; $i++) : ?>
                                <i class="fa fa-star<?= $i > $quizSession->levels[0]->score ? '-o' : '' ?> <?= $i > 10 ? 'star--extra' : '' ?>"></i>
                            <?php endfor ?>
                            <a class="funjob-quiz-score-star-extra-help" href="#" data-trigger="hover" data-toggle="popover" data-content="<?= __('+1 punto bonus per ogni aiuto non utilizzato (se rispondi correttamente a tutte le domande)') ?>">
                                <i class="fa fa-question-circle"></i>
                            </a>
                        </div>
                        <div class="pull-right">
                            <span class="font-size-md"><?= $quizSession->levels[0]->score ?>/13</span>
                        </div>
                    </div>

                    <?php
                        echo $this->Form->create($quizSession, ['id' => 'quiz-visibility-form', 'url' => ['prefix' => 'user', 'controller' => 'QuizSessions', 'action' => 'edit']]);
                        echo $this->Form->control('id', ['type' => 'hidden']);
                        echo $this->Form->control('is_hidden', [
                            'type'   => 'checkbox',
                            'escape' => false,
                            'label'  => __(
                                '{icon} Nascondi risultati dal profilo',
                                ['icon' => '<i style="opacity:0.88" class="fa fa-eye-slash"></i>']
                            )
                        ]);
                        echo $this->Form->end();
                    ?>
                    <script type="text/javascript">
                        $("#quiz-visibility-form :checkbox").on("change", function(evt) {
                            $.blockUI({
                                timeout: 50000,
                                message: <?=
                                    json_encode(
                                        __(
                                            '{icon} {br} Attendere ...', [
                                            'br' => '<br/>',
                                            'icon' => '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>'
                                        ]
                                    )
                                ) ?>
                            });
                            $("#quiz-visibility-form").trigger("submit");
                        });
                    </script>

                    <hr style="margin:5px 0">
                    <?php echo $this->fetch('quiz-session:share') ?>
                    <hr style="margin:5px 0">
                </div>
            </div>

            <div class="funjob-quiz-score-buttons pull-right">
                <?php
                    echo $this->Html->link(
                        __('{icon} Trova altri giochi', ['icon' => '<i class="fa fa-archive"></i>']),
                        $this->Url->build(['_name' => 'quiz:index']),
                        ['escape' => false, 'class' => 'btn btn-sm btn-info text-color-primary', 'style' => 'margin-right:5px']
                    );

                    if ($score_extra < 13) {
                        echo $this->Html->link(
                            __('{icon} Riprovo! Posso fare di meglio', ['icon' => '<i class="fa fa-play"></i>']),
                            $this->Url->build(['_name' => 'quiz:view', 'id' => $quiz->id, 'title' => $quiz->slug, '?' => ['level' => $quizSession->levels[0]->level]]),
                            ['escape' => false, 'class' => 'btn btn-sm btn-info text-color-primary', 'style' => 'margin-right:5px']
                        );
                    } else {
                        echo $this->Html->link(
                            __('{icon} Rigioca', ['icon' => '<i class="fa fa-play"></i>']),
                            $this->Url->build(['_name' => 'quiz:view', 'id' => $quiz->id, 'title' => $quiz->slug, '?' => ['level' => $quizSession->levels[0]->level]]),
                            ['escape' => false, 'class' => 'btn btn-sm btn-info text-color-primary', 'style' => 'margin-right:5px']
                        );
                    }

                    if ($quiz->type != 'default' && $quizSession->levels[0]->level < 3) {
                        echo $this->Html->link(
                            __('Continua {icon}', ['icon' => '<i class="fa fa-arrow-right"></i>']),
                            $this->Url->build(['_name' => 'quiz:view', 'id' => $quiz->id, 'title' => $quiz->slug]),
                            ['escape' => false, 'class' => 'btn btn-sm btn-default btn-primary']
                        );
                    }
                ?>
            </div>
        <?php else: ?>
        <p class="font-size-lg text-center">
            <?= $this->Ui->icon(['class' => 'fa fa-frown-o fa-3x']) ?>
            <br class="visible-xs">
            <span class="text-bold">
                <?= __x('Risultato quiz', 'SPIACENTE! Non hai superato la prova') ?>
            </span>
        </p>
        <p class="font-size-md text-center">
            <?=
                __x('Risultato quiz', 'Hai ottenuto un punteggio di "{score}/10" (il minimo richiesto è {min_score}/10)', [
                    'score'     => $score,
                    'min_score' => $min_score
                ])
            ?>
        </p>
        <a href="<?= $this->Url->build($quiz->url) ?>" class="btn btn-default">
            <?= __('Prova di nuovo') ?>
        </a>
        <?php endif ?>
    </div>
<?php $this->end() ?>

<?php // CREDITI MATURATI GIOCANDO ?>
<?php $this->start('credit:earned') ?>
<?php $this->end() ?>

<?php // VOTAZIONE QUIZ ?>
<?php $this->start('quiz:ranking') ?>
    <?php
        $url = ['_name' => 'quiz:ranking:add'];
        if (!$QuizUserRankings->isNew()) {
            $url = ['_name' => 'quiz:ranking:edit', $QuizUserRankings->id];
        }

        echo $this->Form->create($QuizUserRankings, ['id' => 'quiz-ranking-form', 'url' => $url]);
        echo $this->Form->control('id', ['type' => 'hidden']);
        echo $this->Form->control('user_id', ['type' => 'hidden', 'value' => $this->request->session()->read('Auth.User.id')]);
        echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $quiz->id]);
        echo $this->Form->control('rank', [
            'label'   => false,
            'type'    => 'select',
            'empty'   => __('Come valuti questo quiz?'),
            'options' => [
                1  => __('1: Sconsigliato'),
                2  => __('2: Inesatto'),
                3  => __('3: Poco interessante'),
                4  => __('4: Poco coinvolgente'),
                5  => __('5: Potrebbe essere migliorato'),
                6  => __('6: Sufficiente'),
                7  => __('7: Diventente'),
                8  => __('8: Molto divertente'),
                9  => __('9: Consigliato'),
                10 => __('10: Tra i migliori')
            ]
        ]);
        $label = $QuizUserRankings->isNew() ? __('Vota') : __('Aggiorna voto');
        echo $this->Form->submit($label, ['class' => 'btn btn-sm btn-primary btn-block']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if ($is_passed) : ?>
        <div class="funjob-quiz-score-header text-center">
            <img class="quiz-result-preview-title-icon" src="img/einstein.png" style="height:60px;width:60px" alt="">

            <div class="quiz-result-preview-title quiz-result-preview-title--passed">
                <?= __x('Risultato quiz', 'Complimenti') ?>
            </div>
            <div class="quiz-result-preview-eyelet text-muted text-center">
                <?php
                    switch($score) {
                        case '7':
                        case '8':
                        case '9':
                            echo __('Molto bene, ti sei fatto valere');
                        break;

                        case '10':
                        case '11':
                        case '12':
                            echo __('Conosci molto bene l\'argomento');
                        break;

                        case '13':
                            echo __('Impossibile fare di meglio!');
                        break;
                    }
                ?>
            </div>
        </div>
        <?php else: ?>
            <div class="funjob-quiz-score-header text-center">
                <img class="quiz-result-preview-title-icon" src="img/einstein.png" style="height:60px;width:60px" alt="">

                <div class="quiz-result-preview-title quiz-result-preview-title--failed">
                    <?= __x('Risultato quiz', 'Spiacente! Riprova') ?>
                </div>
                <div class="quiz-result-preview-eyelet text-muted text-center">
                    <?php
                        switch($score) {
                            case '1': case '2': case '3':
                                echo __('Ops! Dovresti ripassare l\'argomento');
                            break;

                            case '4': case '5': case '6':
                                echo __('Sai di cosa si parla ma puoi fare di meglio ;-)');
                            break;
                        }
                    ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
<hr class="funjob-quiz-score-separator">

<div class="row app-quiz-result">
    <div class="col-md-12">
        <div class="row">

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="app-quiz-result-image" style="border-color:<?= $quiz->color ?>">
                    <div class="app-quiz-result-text" style="background-color: <?= $quiz->color ?> !important;overflow:hidden">
                        <h1 class="quiz-entity-title" class="text-center text-truncate">
                            “<?= $quiz->title ?>”
                        </h1>
                    </div>
                    <div style="background-color: rgba(255,255,255,0.33) !important;overflow:hidden">
                        <img style="margin:0 auto" class="img-responsive" src="<?= $quiz->cover_500x300 ?>" style="margin:0;padding:0" />
                    </div>
                    <div class="app-quiz-result-footer" style="background-color: <?= $quiz->color ?> !important;">
                        <div class="container-fluid" style="border-radius:4px;background-color:rgb(239,239,239);border-radius:4px;">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                    <div class="hidden-xs funjob-quiz-footer-title funjob-quiz-footer-title--share">
                                        <?php echo __('Condividi gioco') ?>
                                        <?php
                                            echo $this->Ui->helpPopover([
                                                'text' => __('Lo sapevi che puoi guadagnare se altri utenti giocano il quiz che hai creato?')
                                            ])
                                        ?>
                                    </div>
                                    <?php echo $this->fetch('quiz:share') ?>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <div class="hidden-xs funjob-quiz-footer-title">
                                        <?php echo __('Vota gioco') ?>
                                        <?php
                                            echo $this->Ui->helpPopover([
                                                'text' => __('Votando il gioco contribuirai a far emerge il talento')
                                            ])
                                        ?>
                                    </div>
                                    <button id="" class="js-quiz-rating btn btn-default btn-xs btn-block">
                                        <i class="fa text-success fa-thumbs-o-up fa-2x"></i>
                                        <i class="fa text-danger fa-flip-horizontal fa-thumbs-o-down fa-2x"></i>
                                    </button>
                                    <script id="modal-quiz-ranking" type="text/template">
                                        <?php echo $this->fetch('quiz:ranking') ?>
                                    </script>
                                    <script type="text/javascript">
                                        $(".js-quiz-rating").on("click", function(evt) {
                                            bootbox.dialog({
                                                className : "funjob-modal",
                                                title     : <?= json_encode(__('Dai un punteggio a questo gioco')) ?>,
                                                message   : document.querySelector("#modal-quiz-ranking").innerHTML
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php echo $this->fetch('QuizSession:status') ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <hr class="clearfix">
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo $this->fetch('scores') ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo $this->fetch('replies') ?>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('*[data-toggle=popover]').popover({
            container: "body"
        });

        $(".js-report-question").on("click", function(evt) {
            evt.preventDefault();
            var $template = $( '#' + $(this).data('template') );
            if (!$template) { return false; }

            bootbox.dialog({
                className : "funjob-modal",
                message   : function() {
                    return $template.html();
                }
            })
        });
    })
</script>
