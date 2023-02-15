<?php
    $this->assign('title', __d('backend', 'Giochi realizzati'));

    $this->Breadcrumbs
        ->add(__d('backend', 'Quizzes'), ['action' => 'index']);
?>

<?php $this->start('quiz:filters') ?>
    <div class="well well-sm collapse" id="search" style="height:0">
        <?php
            echo $this->Form->create(null, [
                'valueSources' => ['query', 'context']
            ]);

            echo $this->Form->control('term', [
                'label'       => false,
                'placeholder' => __('Nome Gioco')
            ]);

            echo $this->Form->control('status', [
                'label'   => __d('backend', 'Stato Gioco'),
                'empty'   => __('Tutti'),
                'options' => [
                    'draft'       => __('Allestimento (domande non raggiunte, o non ancora pubblicato)'),
                    'published'   => __('Giocabili (domande raggiunte e in archivio)'),
                    'disabled'    => __('Non giocabili (disabilitate da admin)')
                ]
            ]);

            echo $this->Form->control('type', [
                'label'       => false,
                'empty'       => __('Ogni tipologia'),
                'options'     => [
                    'default' => __('Quiz normale'),
                    'funjob'  => __('Quiz certificato FunJob')
                ]
            ]);
            echo $this->Form->button(__d('backend', 'Filtra'), ['class' => 'btn btn-sm btn-primary']);
            echo $this->Form->end();
        ?>
    </div>
<?php $this->end() ?>

<?php $this->start('toolbars') ?>
    <div class="pull-right">
        <div class="btn-toolbar" role="toolbar" aria-label="...">
            <a href="<?= $this->Url->build(['controller' => 'QuizQuestions', 'action' => 'export']) ?>" class="btn btn-default btn-group" role="group" aria-label="<?= __('Esporta') ?>">
                <i class="fa fa-cloud-download"></i>
                <?php echo __d('backend', 'Esporta domande/risposte') ?>
            </a>

            <button data-toggle="collapse" href="#search" class="btn btn-default btn-group" role="group" aria-label="<?= __('Cerca') ?>">
                <i class="fa fa-search"></i>
                <?php echo __('Cerca') ?>
            </button>


            <a href="<?= $this->Url->build(['controller' => 'Homepages', 'action' => 'popularQuizzes']) ?>" class="btn btn-default btn-group" role="group" aria-label="<?= __('Cerca') ?>">
                <i class="fa fa-fire"></i>
                <?php echo __('Quiz popolari su home') ?>
            </a>

        </div>
    </div>

    <div class="clearfix"></div>
    <hr>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $this->fetch('toolbars') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $this->fetch('quiz:filters') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th class="text-center"><?= __d('backend', 'Status') ?></th>
                            <th class="text-center"><?= __d('backend', 'Tipologia') ?></th>
                            <th><?= __d('backend', 'Nome') ?></th>
                            <th><?= __d('backend', 'Categorie') ?></th>
                            <th><?= __d('backend', 'Autore') ?></th>
                            <th><?= __d('backend', 'Domande') ?></th>
                            <th><?= __d('backend', 'Punteggio') ?></th>

                            <th><?= __d('backend', 'Amministra') ?></th>
                        </tr>

                        <?php if (!$quizzes->isEmpty()) : ?>
                            <?php foreach ($quizzes as $quiz) : ?>
                            <tr>
                                <td class="text-center" style="vertical-align:middle;">
                                    <?php if ($quiz->status == 'draft') : ?>
                                        <span class="fa-stack fa-md">
                                          <i class="text-muted fa fa-gamepad fa-stack-1x"></i>
                                          <i class="fa fa-times fa-stack-1x text-info"></i>
                                        </span>
                                        <br>
                                        <?php echo __d('backend', 'allestimento') ?>
                                    <?php elseif ($quiz->status == 'published') : ?>
                                        <span class="fa-stack fa-md">
                                          <i class="text-success fa fa-gamepad fa-stack-1x"></i>
                                          <i class="fa fa-circle-thin fa-stack-2x <?= $quiz->is_disabled ? 'text-danger' : 'text-success' ?>"></i>
                                        </span>

                                        <br>
                                        <?php if ($quiz->is_disabled) : ?>
                                            <span class="text-danger"><?php echo __d('backend', 'Bannato') ?></span>
                                        <?php else: ?>
                                            <?php echo __d('backend', 'Giocabile') ?>
                                        <?php endif ?>
                                    <?php elseif ($quiz->status == 'unpublished' || $quiz->status == 'hidden'): ?>
                                        <span class="fa-stack fa-md">
                                          <i class="text-muted fa fa-gamepad fa-stack-1x"></i>
                                          <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                        </span>
                                        <br>
                                        <?php echo __d('backend', 'Non giocabile') ?>
                                    <?php endif ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($quiz->type == 'default') : ?>
                                        <i style="font-size:20px;padding:0;margin:0" class="fa fa-user"></i>
                                    <?php else: ?>
                                        <i style="font-size:20px;padding:0;margin:0" class="fontello-brain"></i>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <a href="<?= $this->Url->build($quiz->url) ?>">
                                        <?= h($quiz->title) ?>
                                        <?php //echo str_repeat('*', (100 - strlen($quiz->title))) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php foreach($quiz->categories as $category) : ?>
                                        <?php echo h($category['name']) ?>,
                                    <?php endforeach ?>
                                </td>
                                <td><a href="<?= $this->Url->build($quiz->author->url) ?>"><?= $quiz->author->username ?></a></td>
                                <td><?= $quiz->published_questions ?></td>
                                <td>
                                    <?php if (empty($quiz->_avg)) : ?>
                                        <span class="font-size-sm text-muted">N/D</span>
                                    <?php else: ?>
                                        <span class="text-bold text-right">
                                            <?= $quiz->_avg ?>
                                        </span>
                                    <?php endif ?>
                                </td>

                                <td>
                                    <a href="<?= $this->Url->build(['_name' => 'quiz:edit', $quiz->id]) ?>">
                                        <i class="fa fa-pencil"></i>
                                        <?= __d('backend', 'Modifica') ?>
                                    </a>
                                    <br>


                                    <a href="<?= $this->Url->build(['controller' => 'Homepages', 'action' => 'popularQuizzesAppend', $quiz->id]) ?>">
                                        <i class="fa fa-home"></i>
                                        <?= __d('backend', 'Home') ?>
                                    </a>
                                </td>

                            </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-muted text-center">
                                    <?= __d('backend', 'Nessun quiz creato') ?>
                                </td>
                            </tr>
                        <?php endif ?>

                    </table>
                </div>
                <?php echo $this->element('pagination') ?>

            </div>
        </div>

    </div>
</div>
