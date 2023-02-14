<?php
    $this->assign('title', __d('backend', 'Giochi realizzati dall\'utente'));

    $this->Breadcrumbs
        ->add(__d('backend', 'Utenti'), ['action' => 'index'])
        ->add($UserCurrent->username, ['action' => 'view', $UserCurrent->id]);
?>

<?php $this->start('statuses') ?>
    <ul class="list-group">
        <li class="list-group-item disabled"><?= __d('backend', 'Giochi creati') ?></li>

        <?php foreach ($statuses as $status => $total) : ?>
            <li class="list-group-item">
                <?php if ($status == 'draft') : ?>
                    <?= __x('backend', 'In preparazione') ?>
                <?php elseif ($status == 'disabled') : ?>
                    <?= __x('backend', 'Disabilitato (ban)') ?>
                <?php elseif ($status == 'published') : ?>
                    <?= __x('backend', 'Pubblicati') ?>
                <?php endif ?>

                <span class="badge">
                    <?= $total ?>
                </span>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->end() ?>

<?php $this->start('quiz:filters') ?>
    <?php
        echo $this->Form->create(null, [
            'valueSources' => ['query', 'context']
        ]);
        echo $this->Form->control('term', [
            'label'       => false,
            'placeholder' => __('Nome Gioco')
        ]);
        echo $this->Form->control('type', [
            'label'       => false,
            'empty'       => __('Ogni tipologia'),
            'options'     => [
                'default' => __('Gioco normale'),
                'funjob'  => __('Gioco certificato FunJob')
            ]
        ]);
        echo $this->Form->button(__d('backend', 'Filtra'), ['class' => 'btn btn-sm btn-block btn-default']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <?php echo $this->fetch('statuses') ?>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $this->fetch('quiz:filters') ?>
                <hr>
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
                            <th><?= __d('backend', 'Domande') ?></th>

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
                                          <i class="fa fa-circle-thin fa-stack-2x text-success"></i>
                                        </span>

                                        <br>
                                        <?php echo __d('backend', 'Giocabile') ?>
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
                                        <?php echo h($category['name']) ?>
                                    <?php endforeach ?>
                                </td>
                                <td><?= $quiz->published_questions ?></td>

                                <td>
                                    <a class="btn btn-xs btn-default" href="<?= $this->Url->build(['_name' => 'quiz:edit', $quiz->id]) ?>">
                                        <?= __d('backend', 'Modifica') ?>
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
