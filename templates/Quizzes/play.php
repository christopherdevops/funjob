<?php
    $this->assign('title', __('[{0}/10] {1}', $step, $quiz->title));
    $this->assign('header', ' ');

    $this->Html->css(['quizzes/play', 'features/btn-alt'], ['block' => 'css_head']);
    $this->Html->script([
        '/bower_components/hashids/dist/hashids.min.js',
    ], ['block' => 'js_head']);


    $this->Html->script([
        '/bower_components/blockUI/jquery.blockUI.js'
    ], ['block' => 'js_foot']);
?>

<script>
    window.ui = {
        shared: {
            next_url : undefined,
            timerCountdownLine: undefined
        }
    };
</script>

<!-- test -->
<div class="">

    <div id="funjob-quiz-suggestions" class="row text-center">
        <div class="col-md-12">

            <?php $suggestionSessionPath = sprintf('Quiz.%d.suggestions', $this->request->getParam('id')) ?>

            <?php if ($this->request->session()->read($suggestionSessionPath . '.perc25')) : ?>
            <button type="button" id="suggestion-filter--1" class="btn2 btn-gradient rounded bronze mini width-fixed">
                <i class="fa fa-star-o"></i>
                <span class="font-size-md">
                    <?php echo __('Togli 1') ?>
                </span>
            </button>
            <?php endif ?>

            <?php if ($this->request->session()->read($suggestionSessionPath . '.perc50')) : ?>
            <button type="button" id="suggestion-filter--2" class="btn2 btn-gradient rounded silver mini width-fixed">
                <i class="fa fa-star-half-o"></i>
                <span class="font-size-md">
                    50:50
                </span>
            </button>
            <?php endif ?>

            <?php if ($this->request->session()->read($suggestionSessionPath . '.skip')) : ?>
            <button type="button" id="suggestion-correct" class="btn2 btn-gradient rounded yellow mini width-fixed">
                <i class="fa fa-star "></i>
                <span class="font-size-md">
                    <?= __('Salta') ?>
                </span>
            </button>
            <?php endif ?>

        </div>
    </div>

    <div class="">
        <div class="app-modal-dialog--quiz modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="funquiz-quiz-question no-margin text-center animated zoomIn">
                        <?= $question->question ?>
                    </h3>
                </div>

                <div class="modal-body">
                    <?php
                        echo $this->Form->create($QuizSessionReply, [
                            'id'  => 'app-quiz-answerSelector',
                            'url' => [
                                '_name' => 'quiz:reply',
                                'title' => $quiz->slug,
                                'id'    => $quiz->id,
                                'step'  => $step,
                                'level' => $this->request->getParam('level')
                            ]
                        ]);

                        // Valore hidden "timer" (cambiato in realtime con setInterval)
                        echo $this->Form->unlockField('secs');
                        echo $this->Form->unlockField('suggestions.skip');
                        echo $this->Form->unlockField('suggestions.perc50');
                        echo $this->Form->unlockField('suggestions.perc25');

                        echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $quiz->id]);
                        echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $quiz->id]);

                        $this->Form->templates([
                            'nestingLabel'   => '
                                <label{{attrs}} class="funquiz-quiz-answer app-quiz-answers element-animation1 btn btn-md btn-primary btn-block">
                                    {{input}}
                                    {{text}}
                                </label>',
                        ]);
                    ?>

                    <div class="quiz" id="quiz" data-toggle="buttons" style="">
                        <?php
                            $answers = $question->quiz_answers;
                            $options = [];

                            foreach ($answers as $answer) {
                                // Sostituisce "__TRUE__" con "Vero"
                                if ($question->type == 'true_or_false') {
                                    $text = $this->QuizAnswer->answer($answer->answer);
                                } else {
                                    $text = $answer->answer;
                                }

                                $options[ $answer->id ] = $text;
                            }

                            echo $this->Form->radio('reply', $options, [
                                'templateVars' => [
                                    //'icon' => '<i style="text-align:left" class="fa fa-fw fa-thumbs-up"></i>'
                                ]
                            ]);
                        ?>
                    </div>
                </div>

                <div class="modal-footer text-muted">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="funjob-quiz-timer clearfix">
                                <div class="funjob-quiz-timer-icon pull-left">
                                    <i style="color:#00adee;font-size:10px;position:relative;z-index:4;" class="fa fa-spin fa-hourglass-o"></i>
                                </div>
                                <div class="funjob-quiz-timer-bar pull-right">
                                    <?php
                                        //echo $this->Element('ui/countdown--circular', ['secs' => 30]);
                                        echo $this->Element(
                                            'ui/countdown-dates--line',
                                            [
                                                'from' => $this->request->session()->read(sprintf('%s.replies.%d.started_at', $gameSessionPath, $step), true),
                                                'to'   => $this->request->session()->read(sprintf('%s.replies.%d.expire_at', $gameSessionPath, $step), true),
                                                'secs' => \Cake\Core\Configure::read('app.quizAnswer.timeout')
                                            ]
                                        )
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-spacer"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $this->element('ui/quiz-dots--square', []) ?>
                        </div>
                    </div>

                </div>

                <?php
                    // false (aiuto non utilizzato per la domanda corrente)
                    echo $this->Form->control('suggestions.skip', ['type' => 'hidden', 'value' => 0]);
                    echo $this->Form->control('suggestions.perc50', ['type' => 'hidden', 'value' => 0]);
                    echo $this->Form->control('suggestions.perc25', ['type' => 'hidden', 'value' => 0]);
                    echo $this->Form->end();
                ?>
           </div>
        </div>
    </div>
</div>

<?php
    // FORM: per conteggiare visualizzazione pubblicitaria su quiz
    echo $this->Form->create(null, [
        'id'    => 'form-next-question-after-adv',
        'class' => 'hidden',
        'url'   => ['controller' => 'SponsorAdvs', 'action' => 'viewed', '_ext' => 'json']
    ]);
    echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $quiz->id]);
    //echo $this->Form->control('question_id', ['type' => 'hidden', 'value' => $question->id]);
    echo $this->Form->end();
?>


<?php $this->append('js_foot') ?>

    <!-- Sessione quiz scaduta -->
    <script type="text/template" id="i18n-quiz-timeout">
        <img style="margin:0 auto" class="img-responsive" src="/img/quiz-timeout.jpg" alt="" />
    </script>
    <script type="text/javascript">
        window.ui.quiz = (function() {

            var publicMethods = {
                countdown: <?= (int) \Cake\Core\Configure::readOrFail('app.quizAnswer.advCountdown') ?>,
                <?php
                    $useAdv = $this->request->session()->read(sprintf('Quiz.%d._adv', $this->request->params['pass'][0]));
                    $useAdv = filter_var($useAdv, FILTER_VALIDATE_BOOLEAN);
                ?>
                useAdv: <?= json_encode($useAdv) ?>,
                /* @var _form */
                _form: $(".app-modal-dialog--quiz form"),
                /* @var _timerNexQuestion timer per prossima domanda */
                _timerNexQuestion: undefined,
            };

            /**
             * Apre nuova finestra browser su un link
             *
             * Mostra pagina web sponsor dopo click su pubblicità
             *
             * @param  {string} winLink URL
             */
            publicMethods.openAdv = function (SponsorAdvEntity) {
                if (window.opener != null && !window.opener.closed) {
                    window.opener.location = SponsorAdvEntity.track.click;
                    window.opener.focus();
                } else {
                    window.open(SponsorAdvEntity.track.click, "newWindow");
                }
            }

            /**
             * Cerca blocco pubblicitario per client
             * @return jQuery.ajax
             */
            publicMethods.getAdv = function() {
                return $.ajax({
                    url  : "<?php echo $this->Url->build(['_name' => 'adv:get', '_ext' => 'json']) ?>",
                    type : "json",
                    data : {},
                    headers: {
                        "X-CSRF-Token": "<?= $this->request->param('_csrfToken') ?>"
                    }
                });
            };

            /**
             * Mostra pubblicità dopo click quiz
             */
            publicMethods.viewAdv = function() {
                $req = publicMethods.getAdv();

                $req.done(function(response) {
                    window.adv = response.adv;

                    var windAdv = bootbox.dialog({
                        closeButton : false,
                        onEscape    : false,
                        className   : "funjob-modal quiz-image",
                        //size: "large",

                        title   : response.adv.title,
                        message : function(evt) {
                            return "<img style='margin:0 auto' class='img-responsive' src='" +response.adv.track.image+ "' />";
                        },
                        buttons: {

                            openPage: {
                                label     : <?= json_encode(__('{icon} Link Sponsor', ['icon' => '<i class="fa fa-share-square-o"></i>'])) ?>,
                                className : 'btn btn-xs btn-primary',
                                callback  : function() {
                                    window.ui.quiz.openAdv(response.adv);
                                    return false;
                                }
                            },

                            nextQuestion: {
                                label     : <?= json_encode(__('{icon} Attendi ...', ['icon' => '<i class="fa hourglass-start"></i>'])) ?>,
                                className : 'btn btn-xs btn-default',
                                callback  : function() {
                                    window.ui.quiz.disablePreSubmit();
                                    return false;
                                }
                            }
                        }
                    });

                    windAdv.init(function(){
                        var $modal   = $(this);
                        var $btnNext = $modal.find('button[data-bb-handler="nextQuestion"]');

                        $(this).on("shown.bs.modal", function() {
                            var _countBeforeNextQuestion = parseInt(window.ui.quiz.countdown);
                            $btnNext.attr("disabled", "disabled");

                            window.ui.quiz._timerNextQuestion = setInterval(function() {
                                if (_countBeforeNextQuestion > 0) {
                                    var label = <?= json_encode(__('{icon} Attendi {seconds} secondi', ['icon' => '<i class="fa hourglass-start"></i>'])) ?>;

                                    $btnNext.attr("disabled", "disabled");
                                    $btnNext.html(label.replace('{seconds}', _countBeforeNextQuestion));
                                } else {
                                    var label = <?= json_encode(__('{icon} Prosegui Gioco',  ['icon' => '<i class="fontello-credits"></i>'])) ?>;
                                    $btnNext.removeAttr("disabled");
                                    $btnNext.html(label);
                                    clearInterval(window.ui.quiz._timerNextQuestion);
                                }

                                _countBeforeNextQuestion--;

                            }, 1000);
                        });
                    });

                });

                $req.fail(function() {
                    window.ui.quiz.disablePreSubmit();
                });
            }

            publicMethods.disableTimer = function() {
                document.querySelector("#sound-timer").pause();        // disabilita suono countdown
                $(".funjob-quiz-timer-icon i").removeClass("fa-spin"); // rotazione icona clessidra
                clearInterval(window.ui.shared.timerCountdownLine);    // aggiornamento barra countdown
            };

            /**
             * Disabilita le risposte del quiz
             *
             * Viene usato quando il timer è scaduto
             */
            publicMethods.disableAnswers = function() {
                //$("label, input", window.ui.quiz._form).attr("disabled", "disabled");
                window.ui.quiz.disableTimer();

                // Disabilita submit form
                window.ui.quiz._form.on("submit", function(evt) {
                    evt.preventDefault();
                    return false;
                });

                bootbox.dialog({
                    size       : "small",
                    title      : <?= json_encode(__('Tempo scaduto')) ?>,
                    message    : document.querySelector("#i18n-quiz-timeout").innerHTML,
                    onEscape   : false,
                    closeButton: false,
                    className  : "funjob-modal",
                    buttons    : {
                        home: {
                            label     : <?= json_encode(__('{icon} Indietro', ['icon' => '<i class="fa fa-arrow-left"></i>'])) ?>,
                            className : "btn btn-sm btn-gray",
                            callback  : function() {
                                document.location = "<?= $this->Url->build($quiz->url) ?>";
                                return false;
                            }
                        },
                        restart: {
                            label     : <?= json_encode(__('Gioca di nuovo')) ?>,
                            className : "btn btn-sm btn-funjob",
                            callback  : function() {
                                document.location = "<?= $this->Url->build($quiz->url) ?>";
                                return false;
                            }
                        }
                    }
                });

                playSound("#sound-timer-ended");
            }

            /**
             * Disabilita pubblicità dopo risposta quiz
             *
             */
            publicMethods.disablePreSubmit = function() {
                var $form = window.ui.quiz._form;
                var delay = 0;

                if (window.ui.quiz.useAdv && window.adv !== undefined) {
                    playSound("#sound-sound-coin-flip").done(function() {
                        var $req = $.ajax({
                            method : "POST",
                            url    : "<?= $this->Url->build(['controller' => 'SponsorAdvs', 'action' => 'viewed', '_ext' => 'json']) ?>",
                            data   : $("#form-next-question-after-adv").serialize(),
                            headers: {
                                "X-CSRF-Token": "<?= $this->request->param('_csrfToken') ?>"
                            },
                        });
                        $req.done(function() {
                            document.location.replace(window.ui.shared.next_url);
                        });
                        $req.fail(function() {
                            document.location.replace(window.ui.shared.next_url);
                        });
                    });
                } else {
                    document.location.replace(window.ui.shared.next_url);
                }
            }

            return publicMethods;
        })();

        $(function(){

            $("body").on("funjob.quiz.timeout", function(evt) {
                window.ui.quiz.disableAnswers();
            });


            // Disabilita submit del form
            // Viene inviato tramite ajax
            $(document).on("submit", ".app-modal-dialog--quiz form", function(evt) {
                evt.preventDefault();
                return false;
            });

            // Invia risposta al server
            $(document).on("change", ".app-modal-dialog--quiz input[name='reply']", function(evt) {
                playSound("#sound-reply-btn");
                window.ui.quiz.disableTimer();

                var $gameForm = $(".app-modal-dialog--quiz form");
                var $ajax = $.ajax({
                    data   : $gameForm.serialize(),
                    method : "POST",
                    headers: {
                        "X-CSRF-Token": "<?= $this->request->param('_csrfToken') ?>"
                    },
                    url    :  $gameForm.attr("action"),
                    beforeSend: function(settings) {
                        $.blockUI({});
                    }
                });

                $ajax.always(function() { $.unblockUI(); });

                $ajax.done(function(result, status, xhr) {
                    // var contentSelector = ".app-modal-dialog--quiz";
                    // var $content        = $(contentSelector);
                    // var $newContent     = $(contentSelector, $(response));
                    // $content.html($newContent);

                    try {
                        var jsonResponse = $.parseJSON(result);
                        console.log(jsonResponse);

                        if (jsonResponse.status == "failure") {
                            window.ui.quiz.disableAnswers();
                            return false;
                        }

                        if (jsonResponse.status == "next" || jsonResponse.status == "completed") {
                            window.ui.shared.next_url = jsonResponse.redirect;

                            if  (jsonResponse !== null && window.ui.quiz.useAdv) {
                                window.ui.quiz.viewAdv();
                            } else {
                                document.location.replace(jsonResponse.redirect);
                            }

                            return true;
                        }

                    } catch(e) {
                        alert("Opss.. problema tecnico: " + e.name);
                    };
                });

                $ajax.fail(function(jqXHR, textStatus, errorThrown) {
                    bootbox.alert(textStatus);
                    console.log(errorThrown);
                });

            });

        });

        /**
         * Esegue suono mp3
         *
         * @param  str soundSelector
         * @return jQuery.Deferred
         */
        function playSound(soundSelector) {
            var $defer = jQuery.Deferred();

            var soundActivated = Cookies.get('sounds');
            if (soundActivated == "false") {
                return $defer.resolve();
            }

            var sound = document.querySelector(soundSelector);
            if (!sound) {
                return $defer.resolve();
            }

            var $sound = $(sound);
            $sound
                .trigger("load")
                .on("ended", function(evt) {
                    return $defer.resolve("played");
                })
                .on("canplaythrough", function(evt) {
                    this.play();
                    return $defer.resolve("playing");
                });

            return $defer.promise();
        }

        <?php // Aiuti (solo se disponibili) ?>
        <?php if (!empty($suggestions)) : ?>
        (function() {
            var SUGGESTIONS = (function() {
                var _methods = {};
                var methods  = {};

                var _suggestions = <?= json_encode($suggestions) ?>;

                /** decrypt suggerimenti **/
                methods.decrypt = function(str) {
                    var x = new Hashids('myubersecuresalt');
                    return x.decode(str);
                };

                var initialize = function() {
                    $("#suggestion-correct").on("click", function(evt) {
                        var id = methods.decrypt(_suggestions[1]);
                        var $selector = $("label[for='reply-" + id + "']");

                        if ($selector.length) {
                            $("#suggestions-skip").val("1");
                            $selector.trigger("click");
                        }
                    });

                    $("#suggestion-filter--1, #suggestion-filter--2").on("click", function(evt) {
                        var ids =  methods.decrypt(_suggestions[0]);
                        playSound("#sound-suggestion-bnt");

                        // Suggerimento 50% o 25%
                        if (this.id == 'suggestion-filter--1') {
                            var c = 1;
                        } else {
                            var c = 2;
                        }

                        for(var i in ids) {
                            var $reply = $("label[for='reply-" + ids[i] + "']");

                            if ($reply.is(":hidden")) {
                                continue;
                            }

                            $reply.fadeOut("fast");

                            if (this.id == 'suggestion-filter--1') {
                                $("#suggestions-perc25").val("1");
                                $("#suggestion-filter--1").remove();
                            } else {
                                $("#suggestions-perc50").val("1");
                                $("#suggestion-filter--2").remove();
                            }

                            c--;

                            if (c <= 0) {
                                break;
                            }
                        }
                    });
                };

                initialize();
                return {};
            })();
        })();
        <?php endif ?>

    </script>
<?php $this->end() ?>

<!-- Sounds: (todo) caricare suoni al rendering di pagina? -->
<audio class="game-sounds" preload="none" id="sound-sound-coin-flip">
    <source src="/sounds/game/coin-flip.mp3" type="audio/mpeg">
    <source src="/sounds/game/coin-flip.ogg" type="audio/ogg">
    <source src="/sounds/game/coin-flip.wav" type="audio/wav">
</audio>
<audio class="game-sounds" preload="none" id="sound-suggestion-bnt">
    <source src="/sounds/game/suggestion-btn.mp3" type="audio/mpeg">
    <source src="/sounds/game/suggestion-btn.ogg" type="audio/ogg">
    <source src="/sounds/game/suggestion-btn.wav" type="audio/wav">
</audio>
<audio class="game-sounds" preload="none" id="sound-reply-btn">
    <source src="/sounds/game/reply-btn.mp3" type="audio/mpeg">
    <source src="/sounds/game/reply-btn.ogg" type="audio/ogg">
    <source src="/sounds/game/reply-btn.wav" type="audio/wav">
</audio>
<audio class="game-sounds" preload="none" id="sound-timer-ended">
    <source src="/sounds/game/timer-ended.mp3" type="audio/mpeg">
    <source src="/sounds/game/timer-ended.ogg" type="audio/ogg">
    <source src="/sounds/game/timer-ended.wav" type="audio/wav">
</audio>
<audio class="game-sounds" loop preload="metadata" id="sound-timer">
    <source src="/sounds/game/timer-loop.mp3" type="audio/mpeg">
    <source src="/sounds/game/timer-loop.ogg" type="audio/ogg">
    <source src="/sounds/game/timer-loop.wav" type="audio/wav">
</audio>
<script>
    $(function() {
        playSound("#sound-timer");

        <?php // DISABILITA SUONO TIMER QUANDO SI MINIMIZZA LA FINESTRA DEL BROWSER ?>
        Visibility.change(function (e, state) {
            if (state == "hidden") {
                document.querySelector("#sound-timer").pause();
            } else {
                var secs = $(".app-countdown-line--input").val();
                if (Cookies.get("sounds") === "true" && parseInt(secs) > 0) {
                    document.querySelector("#sound-timer").play();
                }
            }
        });
    });
</script>

<?php $this->append('css_head--inline') ?>
    .app-modal-dialog--quiz label { text-align:center !important; }
    .app-modal-dialog--quiz input[type="radio"] {display:none;}
<?php $this->end() ?>



<?php
    // Determina se la vista è stata renderizzata correttamente
    // Cosi da inibire il refresh della pagina qualora l'utente volesse
    // premesse "Aggiorna" o "ctrl+f4".
    // Questo per evitare problematiche relative al timer quiz
    $this->request->session()->write(sprintf('%s.replies.%d.already_rendered', $gameSessionPath, $step), true);
?>
