<?php
    $this->assign('title', __('{0}', $quiz->title));
    $this->assign('header', ' ');

    $this->Html->script(['/bower_components/readmore-js/readmore.js'], ['block' => 'js_foot']);
    $this->Html->script(['/bower_components/jssocials/dist/jssocials.min.js'], ['block' => 'js_foot']);
    $this->Html->css([
        'features/bootstrap-steps-wizard',
        'quizzes/view',
        '/bower_components/jssocials/dist/jssocials.css',
        '/bower_components/jssocials/dist/jssocials-theme-flat.css'
    ], ['block' => 'css_head']);

    $this->Breadcrumbs
        ->add(__('Quiz'), ['_name' => 'quiz:index'])
        ->add($quiz->title, $this->request->getAttribute('here'));


    $this->Html->meta('og:image:width', 500, ['block' => true]);
    $this->Html->meta('og:image:height', 300, ['block' => true]);
    $this->Html->meta('og:image', $this->Url->build($quiz->imageSize($quiz->cover_src_original, '500x300'), ['fullBase' => true]), ['block' => true]);

    $this->Html->meta('og:title', $quiz->title, ['block' => true]);
    $this->Html->meta('og:descr', $quiz->descr_small, ['block' => true]);
    $this->Html->meta('og:url', $this->Url->build($quiz->url, ['fullBase' => true]), ['block' => true]);
?>

<?php $this->start('quiz:report_form') ?>
    <?php
        echo $this->Form->create($UserReportForm, ['url' => ['controller' => 'QuizUserReportings', 'action' => 'add']]);
        echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $quiz->id]);
        echo $this->Form->control('reason', [
            'label'       => false,
            'placeholder' => __('Motivazione per cui vuoi segnalare questo quiz')
        ]);
        echo $this->Form->button(__('Segnala'), ['class' => 'btn btn-block btn-danger']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<?php $this->start('play:form') ?>
    <?php
        echo $this->Form->create('QuizSessionStart');

        /*
        $this->Form->templates([
            'inputSubmit'     => '
                <button{{attrs}}>
                    <span style="font-weight:bold;text-shadow:1px 1px 2px black;text-decoration:uppercase !important">{{text}}</span>
                    <small style="font-weight:bold;text-shadow:1px 1px 2px black;">{{subtext}}</small>
                </button>',
            'submitContainer' => '{{content}}' // '<div class="submit">{{content}}</div>'
        ]);
        */

        echo $this->Form->hidden('quiz_id', ['value' => $this->request->pass[0]]);
        echo $this->Form->hidden('level', ['value' => 1]);

        // TODO: solo se quiz funjob
        if ($quiz->type == 'funjob') {
            $passeds = [];
            $next    = 1;
            $levels  = [];

            if (!empty($quiz->quiz_sessions[0])) {
                $passeds = $quiz->quiz_sessions[0]->levels_passed;
                $next    = end($quiz->quiz_sessions[0]->levels_passed)->level + 1;
            }

            // Imposta livelli giocati (aggiunge punteggio nel select)
            for ($i=0; $i <= 3; $i++) {
                $ii   = $i + 1;
                $data = ['text' => __('Livello {0}', $ii), 'value' => $ii];

                if (!isset($passeds[$i])) {
                    if ($ii == $next) {
                        $data['text']     = __('Livello {0}', $ii);
                        $data['selected'] = 'selected';
                    } else {
                        $data['disabled'] = 'disabled';
                    }
                } else {
                    $data['text'] = __(
                        '✅ Livello {0} {1} {2}',
                        $ii,
                        str_repeat('★', $passeds[$i]->score) . str_repeat('☆', 13 - $passeds[$i]->score),
                        $passeds[$i]->score != 13 ? __('(Ripeti)') : ''
                    );
                }

                $options[$i] = $data;
            }

            echo $this->Form->control('level', [
                'label'   => __('{icon} Livello di difficoltà', ['icon' => '<i style="color:#00adee" class="fa fa-signal"></i>']),
                'type'    => 'select',
                'help'    => __('Giocando potrai sbloccare i livelli successivi'),
                'options' => [
                    // __('Facile')    => array_splice($options, 0, 3),
                    // __('Medio')     => array_splice($options, 0, 3),
                    // __('Difficile') => array_splice($options, 0, 3),
                    $options[0],
                    $options[1],
                    $options[2]
                ],
                'default' => '1',
                'escape'  => false
            ]);
            echo $this->Form->error('level');
        }

        // echo $this->Form->submit('false', [
        //     'type'  => 'submit',
        //     'name'  => 'adv',

        //     'templateVars' => [
        //         'text'    => __('GIOCA'),
        //         'subtext' => __('senza pubblicità')
        //     ],

        //     'class' => 'btn btn-block btn-primary btn-lg',
        // ]);
        // echo $this->Form->submit('true', [
        //     'type'  => 'submit',
        //     'name'  => 'adv',

        //     'templateVars' => [
        //         'text'    => __('GIOCA'),
        //         'subtext' => __('con pubblicità (guadagni crediti)')
        //     ],

        //     'class' => 'btn btn-block btn-primary btn-lg',
        // ]);

        echo $this->Form->control('_adv', [
            'label'   => __('{icon} Guadagna crediti (PIX) da spendere in premi', [
                'icon' => '<i class="fontello-credits" style="color:#00adee"></i>'
            ]),
            'help'    => __('Saranno mostrati annunci pubblicitari {link_start}{icon}{link_end}', [
                'link_start' => '<a href="#" onclick="$(\'.funjob-pix-tutorial\').trigger(\'click\');return false">',
                'link_end'   => '</a>',
                'icon'       => '<i class="fa fa-info-circle"></i>'
            ]),
            'type'    => 'checkbox',
            'default' => true,
            'escape'  => false
        ]);

        echo $this->Form->button(
            '<span class="font-size-md3">' .
                __('Inizia Gioco {icon}', ['icon' => '<i class="fontello-quiz-play"></i>']).
            '</span>',
            ['class' => 'btn btn-primary btn-sm btn-block']
        );
        echo $this->Form->end();
     ?>
<?php $this->end() ?>

<div class="row">

    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

        <div class="app-quiz-sidebar">

            <div class="funjob-quiz-panel-author no-padding panel panel-sm panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h3 class="panel-title font-size-md2">
                            <?php echo __('Informazioni') ?>
                        </h3>
                    </div>
                </div>
                <div class="panel-body">

                    <a class="display-block funjob-quiz-author-link" target="_blank" href="<?= $this->Url->build($quiz->author->url) ?>">
                        <?php echo $this->User->avatar($quiz->author->avatarSrcMobile, []) ?>

                        <?php if ($quiz->author->is_bigbrain) : ?>
                            <i class="fontello-brain"></i>
                        <?php endif ?>

                        <?php echo $quiz->author->username ?>
                    </a>

                    <hr style="border-color:#00adee;margin-top:5px;margin-bottom:5px;">

                    <span style="font-size:14px" class="text-color-gray--dark">
                        <?= __('Gradimento Utenti') ?>
                    </span>
                    <br>
                    <?php if (in_array( (int) $quiz->_avg, range(0,4))) : ?>
                        <i style="line-height:20px;font-size:20px" class="text-bold text-danger fa fa-meh-o"></i>
                    <?php elseif (in_array( (int) $quiz->_avg, range(5,8))) : ?>
                        <i style="line-height:20px;font-size:20px" class="text-bold text-info fa fa-smile-o"></i>
                    <?php else : ?>
                        <i style="line-height:20px;font-size:20px" class="text-bold text-success fa fa-smile-o"></i>
                    <?php endif ?>

                    <?php if ($quiz->_avg == null) : ?>
                        <span class="text-muted">
                            <?= __('Nessun voto') ?>
                        </span>
                    <?php else: ?>
                        <?php echo $quiz->_avg ?>
                    <?php endif ?>
                </div>
            </div>


            <?php if (!empty($quiz->video_embed)) : ?>
            <div class="hidden-xs funjob-quiz-panel-video no-padding panel panel-sm panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title font-size-md2">
                        <?php echo __('Video presentazione') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <style type="text/css">
                        .funjob-quiz-panel-video .panel-body iframe,
                        .funjob-quiz-panel-video .panel-body iframe
                        {
                            height:min-content !important;
                            width:100% !important;
                        }
                    </style>
                    <?php
                        $html = strip_tags($quiz->video_embed, '<iframe><video>');
                        echo $html;
                    ?>
                </div>
            </div>
            <?php endif ?>

            <div class="hidden-xs funjob-quiz-panel-share no-padding panel panel-sm panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title font-size-md2">
                        <?php echo __('Promuovi Gioco') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="share-buttons"></div>
                    <style type="text/css">.jssocials-share-link { border-radius: 50%; }</style>
                    <script>
                        $(function() {
                            $(".share-buttons").jsSocials({
                                showLabel : false,
                                showCount : false,
                                shareIn   : "popup",
                                text      : <?= json_encode(__('Sfida i tuoi amici su: ‹{title}›', ['title' => $quiz->title])) ?>,
                                url       : "<?= $this->Url->build($quiz->url, ['fullBase' => true]) ?>",
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
                </div>
            </div>

            <?php if ($this->request->getSession()->check('Auth.User')) : ?>
            <div class="hidden-xs">
                <button data-modal-message="#modal-quiz-reporting" class="js-open-modal btn-danger btn-block btn btn-sm funjob-quiz-reporting-btn">
                    <i class="fa fa-exclamation-triangle"></i>
                    <?= __('Segnala gioco') ?>
                </button>
            </div>
            <?php endif ?>

            <?php if (
                $this->request->getSession()->read('Auth.User.id') == $quiz->user_id ||
                $this->request->getSession()->read('Auth.User.type') == 'admin'
            ) : ?>
            <div class="hidden-xs">
                <a class="btn btn-block btn-default" href="<?= $this->Url->build(['controller' => 'Quizzes', 'action' => 'edit', $quiz->id]) ?>">
                    <i class="fa fa-cogs"></i>
                    <?= __('Modifica gioco') ?>
                </a>
            </div>
            <?php endif ?>

        </div>
    </div>

    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="funjob-quiz-detail">

            <div class="funjob-quiz-cover-box" style="border-color:<?= $quiz->color ?>;overflow:hidden;">
                <img class="img-responsive" style="width:100%;height:min-content" src="<?= $quiz->imageSize($quiz->coverSrcOriginal, '900x400') ?>" data-src="holder.js/500x300?auto=yes&bg=<?= $quiz->color ?>&fg=#ffffff&text=funjob.it" alt="">
            </div>
            <div class="well well-sm funjob-quiz-cover-info" style="background-color: <?= $quiz->color ?>">
                <h1 class="funjob-quiz-title font-size-lg--x text-center no-margin">
                    <?php echo h($quiz->title) ?>
                </h1>

                <div class="funjob-quiz-descr" style="word-break:break-word;overflow:hidden">
                    <div class="pull-right">
                        <?php if ($quiz->href) : ?>
                        <a class="funjob-quiz-href" target="_blank" href="<?= $quiz->href ?>">
                            <i class="fa fa-link"></i>
                            <?php echo __('Pagina di riferimento') ?>
                        </a>
                        <br>
                        <?php endif ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="margin-top--lg"></div>
                    <?= $this->Text->autoParagraph(h($quiz->descr)) ?>
                </div>
                <?php $this->Html->scriptStart(['block' => 'js_foot']) ?>
                    $(function() {
                        $(".funjob-quiz-descr").readmore({
                            collapsedHeight: 140,
                            embedCSS : true,
                            blockCSS : 'display:inline-block;overflow:hidden !important',
                            lessLink : '<a class="readless" href="#"><?= __('Riduci ...') ?> <i class="fa fa-arrow-up"></i></a>',
                            moreLink : '<a class="readmore" href="#"><?= __('Continua a leggere ...') ?> <i class="fa fa-arrow-down"></i></a>'
                        });
                    });
                <?php $this->Html->scriptEnd() ?>

                <footer class="visible-xs">
                    <hr>


                    <a style="height:40px;" class="btn btn-default btn-xs" target="_blank" href="<?= $this->Url->build($quiz->author->url) ?>">
                        <?php echo $this->User->avatar($quiz->author->avatarSrcMobile, ['height' => 20, 'width' => 20]) ?>
                        <br>
                        <?php echo $quiz->author->username ?>
                    </a>

                    <?php if (!empty($quiz->video_embed)) : ?>
                    <button data-modal-message="#modal-video-embed" class="js-open-modal btn btn-default btn-xs">
                        <i class="fa fa-youtube-play"></i>
                        <br>
                        <?php echo __('Video') ?>
                    </button>
                    <?php endif ?>

                    <button data-modal-message="#modal-quiz-share" class="js-open-modal btn btn-default btn-xs">
                        <i class="fa fa-share-alt"></i>
                        <br>
                        <?php echo __('Condividi') ?>
                    </button>

                    <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                    <div class="pull-right">
                        <button data-modal-message="#modal-quiz-reporting" class="js-open-modal btn btn-default btn-xs">
                            <i class="text-danger fa fa-exclamation-triangle"></i>
                            <br>
                            <span class="text-danger">
                                <?= __('Segnala') ?>
                            </span>
                        </button>
                    </div>
                    <?php endif ?>

                </footer>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div id="play" class="well well-sm">
                        <?= $this->fetch('play:form') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php $this->append('js_foot') ?>
<script id="modal-quiz-share" type="text/template">
    <div class="share-buttons--mobile"></div>
    <style type="text/css">.jssocials-share-link { border-radius: 50%; }</style>
    <script>
        $(function() {
            $(".share-buttons--mobile").jsSocials({
                showLabel : false,
                showCount : false,
                shareIn   : "popup",
                text      : <?= json_encode(__('Sfida i tuoi amici su: ‹{title}›', ['title' => $quiz->title])) ?>,
                url       : "<?= $this->Url->build($quiz->url, ['fullBase' => true]) ?>",
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
</script>
<script id="modal-video-embed" type="text/template">
    <div class="video-embed-iframe" style="min-height:80%;width:100%;">
        <?= $quiz->video_embed ?>
    </div>
</script>
<script id="modal-quiz-reporting" type="text/template">
    <?php echo $this->fetch('quiz:report_form') ?>
</script>

<script>
    $(function() {
        "use strict";

        $(".js-open-modal").on("click", function(evt) {
            var templateSelector = this.dataset.modalMessage;
            var $template;

            if (!templateSelector) {
                return false;
            }

            $template = $(templateSelector);
            if (!$template) {
                return false;
            }

            bootbox.dialog({
                className : "funjob-modal",
                size      : "large",
                message   : function() { return $template.html(); }
            });
        });
    })
</script>
<?php $this->end() ?>
