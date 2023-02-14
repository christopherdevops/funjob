<?php
    $this->assign('title', __d('backend', 'Giochi realizzati dall\'utente'));

    $this->Breadcrumbs
        ->add(__d('backend', 'Utenti'), ['action' => 'index'])
        ->add($UserCurrent->username, ['action' => 'view', $UserCurrent->id]);
?>

<?php $this->start('statuses') ?>
<?php $this->end() ?>

<?php $this->start('filters') ?>
    <?php
        echo $this->Form->create(null, [
            'valueSources' => ['query', 'context']
        ]);
        echo $this->Form->control('term', [
            'label'       => false,
            'placeholder' => __('Nome gruppo')
        ]);

        echo $this->Form->button(__d('backend', 'Filtra'), ['class' => 'btn btn-sm btn-block btn-default']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $this->fetch('filters') ?>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th><?= __d('backend', 'Nome') ?></th>
                            <th><?= __d('backend', 'Utenti') ?></th>
                            <th><?= __d('backend', 'Amministra') ?></th>
                        </tr>

                        <?php if (!$entities->isEmpty()) : ?>
                            <?php foreach ($entities as $group) : ?>
                            <tr>
                                <td>
                                    <a class="display-block text-truncate" href="<?= $this->Url->build($group->url) ?>">
                                        <?= h($group->name) ?>
                                        <?php //echo str_repeat('*', (100 - strlen($group->title))) ?>
                                    </a>
                                </td>
                                <td>
                                    <i class="fa fa-users"></i>
                                    <?= $group->members_count ?>
                                </td>
                                <td>
                                    <a class="btn btn-xs btn-default" href="<?= $this->Url->build(['_name' => 'groups:edit', 'id' => $group->id, 'slug' => $group->slug]) ?>">
                                        <?= __d('backend', 'Modifica') ?>
                                    </a>
                                </td>

                            </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-muted text-center">
                                    <?= __d('backend', 'Nessun gruppo creato') ?>
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
