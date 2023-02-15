<?php
    $this->assign('title', __d('backend', 'Gruppi creati'));

    $this->Breadcrumbs
        ->add(__d('backend', 'Gruppi'), ['prefix' => false, 'action' => 'index']);
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

            echo $this->Form->button(__d('backend', 'Filtra'), ['class' => 'btn btn-sm btn-primary']);
            echo $this->Form->end();
        ?>
    </div>
<?php $this->end() ?>

<?php $this->start('toolbars') ?>
    <div class="pull-right">
        <div class="btn-toolbar" role="toolbar" aria-label="...">
            <button data-toggle="collapse" href="#search" class="btn btn-default btn-group" role="group" aria-label="<?= __('Cerca') ?>">
                <i class="fa fa-search"></i>
                <?php echo __('Cerca') ?>
            </button>

            <a href="<?= $this->Url->build(['action' => 'clear_cache']) ?>" class="btn btn-danger btn-group" role="group" aria-label="<?= __('Cerca') ?>">
                <i class="fa fa-trash"></i>
                <?php echo __('Cancella cache home') ?>
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
                <p class="text-bold text-info text-center">
                    <?= __d('backend', 'I primi 7 gruppi visualizzati sono visti in Home') ?>
                </p>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th><?= __d('backend', 'Gruppo') ?></th>
                            <th><?= __d('backend', 'Utenti') ?></th>
                            <th><?= __d('backend', 'Descrizione') ?></th>
                            <th><?= __d('backend', 'Fondatore') ?></th>
                            <th><?= __d('backend', 'Amministra') ?></th>
                        </tr>

                        <?php if (!$entities->isEmpty()) : ?>
                            <?php foreach ($entities as $group) : ?>
                            <tr>
                                <td>
                                    <a class="display-block text-truncate" href="<?= $this->Url->build(['_name' => 'groups:view', 'id' => $group->id, 'slug' => $group->slug]) ?>">
                                        <?= h($group->name) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= $this->Url->build(['_name' => 'groups:members', 'id' => $group->id, 'slug' => $group->slug]) ?>">
                                        <i class="fa fa-users"></i>
                                        <?= h($group->members_count) ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        <?= $group->descr ?>
                                    </div>
                                </td>

                                <td>
                                    <?php if (!empty($group->owner)) : ?>
                                        <a href="<?= $this->Url->build($group->owner->url) ?>"><?= $group->owner->username ?></a>
                                    <?php endif ?>
                                </td>

                                <td>
                                    <a href="<?= $this->Url->build(['prefix' => 'admin', 'action' => 'edit', 0 => $group->id]) ?>">
                                        <i class="fa fa-pencil"></i>
                                        <?= __d('backend', 'Modifica') ?>
                                    </a>

                                    <?php
                                        echo $this->Form->postLink(
                                            '<i class="text-danger fa fa-trash"></i> ' . __d('backend', 'Elimina'),
                                            ['prefix' => 'admin', 'action' => 'delete', 0 => $group->id],
                                            ['escape' => false, 'confirm' => __('Sei sicuro di voler cancellare questo gruppo?')]
                                        );
                                    ?>

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
