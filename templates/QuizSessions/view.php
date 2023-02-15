<?php
    $this->assign('title', $quizSession->quiz->title);
    $this->assign('header', ' ');

    $this->Html->css(['quiz-sessions/view'], ['block' => 'css_head']);


    $this->Html->meta('og:image:width', 500, ['block' => true]);
    $this->Html->meta('og:image:height', 300, ['block' => true]);
    $this->Html->meta('og:image', $this->Url->build($quizSession->quiz->imageSize($quizSession->quiz->coverSrcOriginal, '500x300'), true), ['block' => true]);

    $this->Html->meta('og:title', $quizSession->quiz->title, ['block' => true]);
    $this->Html->meta('og:descr', $quizSession->quiz->descr_small, ['block' => true]);
    $this->Html->meta('og:url', $this->Url->build($quizSession->url, true), ['block' => true]);

    $this->Breadcrumbs
        ->add($quizSession->user->username, $quizSession->user->url)
        ->add(__('Giochi completati'), $quizSession->user->url)
        ->add($quizSession->quiz->title, $quizSession->quiz->url)
        ->add(__('Risultato'));

    $this->QuizSession->config('entity', $quizSession);
?>

<?php // CONDIVIDI QUIZ ?>
<?php $this->start('quiz:share') ?>
    <?php
        $this->Html->css([
            '/bower_components/jssocials/dist/jssocials.css',
            '/bower_components/jssocials/dist/jssocials-theme-flat.css'
        ], ['block' => 'css_head']);
        $this->Html->script('/bower_components/jssocials/dist/jssocials.min.js', ['block' => 'js_foot']);
    ?>

    <?php $this->append('js_foot') ?>
    <script>
        $(function() {
            $("#quiz-session-share").jsSocials({
                showLabel : false,
                showCount : false,
                shareIn   : "popup",
                text      : <?= json_encode(__('{username} ha ottenuto il titolo "{score_title}" su “{title}” ottenendo un  punteggio di {score}/{score_max}', [
                    'username'  => $quizSession->user->username,
                    'title'     => $quizSession->quiz->title,
                    'title'     => json_encode($this->QuizSession->getFinalScoreTitle()['text']),
                    'score'     => $this->QuizSession->getFinalScore(),
                    'score_max' => $this->QuizSession->getFinalScoreMax()
                ])) ?>,
                url       : "<?= $this->Url->build($quizSession->url, true) ?>",
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

    <p class="font-size-lg text-center text-bold">
        <?php echo __('Convidivi questo punteggio') ?>
    </p>
    <div id="quiz-session-share" class="share-buttons text-center"></div>
<?php $this->end() ?>


<div class="page-header">
    <h1 class="text-bold text-color-gray" style="font-size:22px"><?= h($quizSession->quiz->title) ?></h1>
</div>

<div class="row gutter-10">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="quiz-session-quiz">
            <a data-toggle="popover" data-content="<?= __('Gioca quiz') ?>" href="<?= $this->Url->build($quizSession->quiz->url) ?>" class="quiz-session-quiz-image">
                <img style="border-color:<?= $quizSession->quiz->color ?> !important" class="img-responsive" data-src="holder.js/500x300?text=<?= $quizSession->quiz->title ?>&bg=<?= $quizSession->quiz->color ?>" src="<?= $quizSession->quiz->imageSize($quizSession->quiz->coverSrcOriginal, '500x300') ?>" alt="">
            </a>
        </div>

        <a href="<?= $this->Url->build($quizSession->url) ?>" class="btn btn-block btn-default visible-xs-block visible-sm-block">
            <i class="font-size-md fontello-quiz-play" style="color:<?= $quizSession->quiz->color ?>"></i>
            <?php echo __('Gioca quiz') ?>
        </a>
        <div class="visible-xs-block visible-sm-block margin-top--medium"></div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="row gutter-10">
            <div class="col-md-12">
                <div class="text-bold font-size-lg text-center quiz-session-info">

                    <a data-toggle="popover" data-content="<?= __('Vedi profilo') ?>" data-trigger="hover" class="quiz-session-quiz-player" href="<?= $this->Url->build($quizSession->user->url) ?>" style="border-color:<?= $quizSession->quiz->color ?> !important">
                        <img class="img-circle" src="<?= $quizSession->user->imageSize($quizSession->user->avatarSrc, '80x80') ?>" alt="">
                    </a>

                    <?= __('Risultati di @{username}', ['username' => $quizSession->user->username]) ?>
                </div>
            </div>

        </div>

        <div class="row gutter-10">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p class="font-size-lg text-center font-bold">
                    <i class="text-color-primary fa fa-bar-chart"></i>
                    <span class="text-bold text-color-gray--dark"><?php echo __('Punteggio: ') ?></span>
                    <span class="text-color-gray--dark">
                        <?= $this->QuizSession->getFinalScore() ?> / <?= $this->QuizSession->getFinalScoreMax() ?>
                    </span>
                </p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p class="font-size-lg text-center text-bold">
                    <?php $title = $this->QuizSession->getFinalScoreTitle() ?>
                    <span class="text-color-primary">
                        <?= $title['icon'] ?>
                    </span>
                    <span class="text-color-gray--dark">
                        <?php echo $title['text']; ?>
                    </span>
                </p>
            </div>
        </div>


        <div class="row gutter-10">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <hr>
                <?php echo $this->fetch('quiz:share') ?>
            </div>
        </div>

    </div>
</div>

<div class="margin-top--big"></div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div role="tabpanel" id="quiz-session-levels">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs <?= $this->QuizSession->hasMultiLevel() ? '' : 'hidden' ?>" role="tablist">
                <?php $levels = $this->QuizSession->getLevels(); ?>

                <?php for ($i=1; $i <= $levels; $i++) : $level = $this->QuizSession->getLevelsPassed($i) ?>
                <li role="presentation" class="<?= $i == 1 ? 'active' : '' ?> <?= $level->isNew() ? 'disabled' : '' ?>">
                    <a href="#level<?= $level->level ?>" aria-controls="level<?= $level->level ?>" role="tab" data-toggle="tab">
                        <?php if ($level->isNew()) : ?>
                            <i class="fa fa-check text-muted"></i>
                        <?php else: ?>
                            <i class="fa fa-check text-success"></i>
                        <?php endif ?>

                        <?= __('Livello {level}', ['level' => $level->level]) ?>
                    </a>
                </li>
                <?php endfor ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <?php for ($i=1; $i <= $levels; $i++) : $level = $this->QuizSession->getLevelsPassed($i) ?>
                <div role="tabpanel" class="tab-pane <?= $i == 1 ? 'active' : '' ?>" id="level<?= $level->level ?>">

                    <?php if ($level->isNew()) : ?>

                        <?php if ($this->request->getSession()->read('Auth.User.id') == $quizSession->user_id) : ?>
                            <p class="font-size-lg text-center text-muted">
                                <?php echo __('Non hai ancora sbloccato questo livello') ?>
                            </p>
                        <?php else: ?>
                            <p class="font-size-lg text-center text-muted">
                                <?php echo __('Questo utente non ha ancora sbloccato questo livello') ?>
                            </p>
                        <?php endif ?>

                    <?php else: ?>
                        <div class="well well-sm quiz-session-level-score">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <table style="width:100%;">
                                        <tr>
                                        <?php for ($x=1; $x <= 13; $x++) : $empty = $x > $level->score; ?>
                                            <td>
                                                <i class="fa fa-2x <?= $x > 10 ? 'fa-star--extra' : '' ?> fa-star<?= $empty ? '-o' : '' ?>"></i>
                                            </td>
                                        <?php endfor ?>
                                        </tr>
                                        <tr class="hidden-xs">
                                            <?php for ($x=1; $x <= 13; $x++) : $empty = $x > $level->score ?>
                                            <td>
                                                <?php if (!$empty) : ?>
                                                    <?php if ($x > 10) : ?>
                                                        <span class="text-bold">+1</span>
                                                    <?php else: ?>
                                                        <span>+1</span>
                                                    <?php endif ?>
                                                <?php endif ?>
                                            </td>
                                            <?php endfor ?>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                                    <div class="quiz-session-leve-score text-center">
                                        <?php //echo __('Punteggio ottenuto: {count} / 13', ['count' => $level->score]) ?>
                                        <span class="font-size-lg text-muted"><?= $level->score ?></span>
                                        /
                                        <span class="font-size-lg">13</span>
                                    </div>
                                </div>

                            </div>

                            <?php if ($this->request->getSession()->read('Auth.User.id') == $quizSession->user_id) : ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-block btn-info" href="<?= $this->Url->build($quizSession->quiz->url) ?>">
                                        <span class="hidden-xs text-bold">
                                            <?= __('Puoi migliorare il tuo punteggio: gioca quiz nuovamente') ?>
                                        </span>
                                        <span class="visible-xs text-bold">
                                            <?= __('Gioca quiz nuovamente') ?>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <?php endif ?>

                        </div>

                        <hr>

                        <ul class="list-group">
                            <?php foreach ($level->replies as $reply) : ?>
                            <?php if ($reply->try_seed != $level->best_try_seed) { continue; } ?>

                            <li class="list-group-item">
                                <?php if ($reply->answer->is_correct) : ?>
                                    <i class="text-success fa fa-star"></i>
                                <?php else: ?>
                                    <i class="text-danger fa fa-star-o"></i>
                                <?php endif ?>

                                <span class="font-size-md3">
                                    <?php if ($reply->question->is_published && !$reply->question->is_banned) : ?>
                                        <?= h($reply->question->question) ?>
                                    <?php else: // nel caso l'utente o l'amministratore nascondono la domanda ?>
                                        <span class="text-warning">
                                            <i class="fa fa-warning"></i>
                                            <?= __('Domanda non più disponibile') ?>
                                        </span>
                                    <?php endif ?>
                                </span>

                            </li>
                            <?php endforeach ?>
                        </ul>

                    <?php endif ?>

                </div>
                <?php endfor ?>

            </div>
        </div>

    </div>
</div>

