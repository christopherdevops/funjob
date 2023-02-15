<?php $this->append('css_head--inline') ?>
.fa-check {
    color:gray;
}
.fa-check--checked { color:green }
<?php $this->end() ?>

<?php
    if ($this->request->is('ajax')) {
        echo '<style>' .$this->fetch('css_head--inline'). '</style>';
    }
?>

<div class="page-header text-center">
    <?php if (!$this->request->is('ajax')) : ?>
    <h1 class="font-size-lg text-muted"><?= __('Sessione di gioco') ?></h1>
    <?php endif ?>

    <div class="font-size-md2">
        <div class="text-truncate">
            <?=
                $this->Html->link(
                    $QuizSessionLevel->quiz_session->quiz->title,
                    ['_name' => 'quiz:view', 'id' => $QuizSessionLevel->quiz_session->quiz->id, 'title' => $QuizSessionLevel->quiz_session->quiz->slug]
                );
            ?>
        </div>

        <?php if ($QuizSessionLevel->quiz_session->quiz->type == 'funjob') : ?>
        <div class="pull-right">
            <span class="text-muted font-size-sm">
                <?= __('Livello {level_current} di {level_total}', ['level_current' => $QuizSessionLevel->level, 'level_total' =>  3]) ?>
            </span>
        </div>
        <?php endif ?>
    </div>
</div>

<div class="container-fluid well well-sm">
    <div class="row-fluid">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h1 class="font-size-lg text-muted"><?php echo __('Risposte corrette') ?></h1>
            <h2 class="font-size-lg"><?php echo $QuizSessionLevel->points ?> / 10</h2>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h1 class="no-margin font-size-lg text-muted"><?php echo __('Punteggio') ?></h1>

            <div class="visible-md visible-lg">
            <?php for ($i=1; $i <= 13; $i++) : $checked = ($i <= $QuizSessionLevel->score) ?>
            <i class="font-size-lg fa fa-check <?= $checked ? 'fa-check--checked' : '' ?>"></i>
            <?php endfor ?>
            </div>

            <h2 class="no-margin pull-left"><?php echo $QuizSessionLevel->score ?> / 13</h2>
        </div>
    </div>
</div>

<ul class="list-group">
    <?php foreach ($QuizSessionLevelReplies as $reply) : ?>
    <li class="list-group-item" style="overflow:auto">
        <div class="pull-right">
            <?php if ($reply->answer->is_correct) : ?>
                <i style="color:green" class="fa fa-check"></i>
                <small class="text-muted"><?php echo __('+1 punto') ?></small>
            <?php else: ?>
                <i style="color:red" class="fa fa-cancel-o"></i>
            <?php endif ?>
        </div>

        <div class="text-truncate font-size-md text-<?= $reply->answer->is_correct ? 'success' : 'warning' ?>">
            <?= $reply->question->question ?>
        </div>

        <?php
        /*
        <h4 class="pull-left" style="<?= $reply->answer->is_correct ? '' : 'border-bottom:2px dotted red' ?>">
            <?= $this->QuizAnswer->answer($reply->answer->answer) ?>
        </h4>
        */
       ?>

        <?php if ($reply->question->source_url) : ?>
        <a target="_blank" href="<?= $reply->question->source_url ?>">Approfondimento <i class="fa fa-question-circle"></i></a>
        <?php endif ?>
    </li>
    <?php endforeach ?>
</ul>
