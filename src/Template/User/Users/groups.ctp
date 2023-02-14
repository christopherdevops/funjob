<?php
    $this->assign('title', __d('backend', 'Gruppi che hai creato'));

    $this->Breadcrumbs
        ->add(__('Utenti'), ['action' => 'index'])
        ->add($UserAuth->username, ['action' => 'view', $UserAuth->id])
        ->add(__('Gruppi creati'));
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

<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="<?= $this->request->getParam('pass.0', 'joined') == 'joined' ? 'active' : '' ?>">
            <a href="<?= $this->Url->build(['joined']) ?>" aria-controls="joined" role="tab">
                <?= __('Di cui fai parte') ?>
            </a>
        </li>
        <li role="presentation" class="<?= $this->request->getParam('pass.0', 'joined') == 'created' ? 'active' : '' ?>">
            <a href="<?= $this->Url->build(['created']) ?>" aria-controls="created" role="tab">
                <?= __('Creati') ?>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="created">

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

                                        <?php if ($this->request->getParam('pass.0', 'joined') == 'joined') : ?>
                                        <th><?= __d('backend', 'Entrato in data') ?></th>
                                        <?php else: ?>
                                        <th><?= __d('backend', 'Creato il') ?></th>
                                        <th><?= __d('backend', 'Amministra') ?></th>
                                        <?php endif ?>

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

                                            <?php if ($this->request->getParam('pass.0', 'joined') == 'created') : ?>
                                            <td><?= $group->created ?></td>
                                            <td>
                                                <a class="btn btn-xs btn-default" href="<?= $this->Url->build(['_name' => 'groups:edit', 'id' => $group->id, 'slug' => $group->slug]) ?>">
                                                    <?= __d('backend', 'Modifica') ?>
                                                </a>

                                                <?php
                                                    echo $this->Form->postLink(
                                                        __('Elimina'),
                                                        ['_name' => 'groups:delete', 'id' => $group->id, 'slug' => $group->slug],
                                                        [
                                                            'confirm' => __('Sei sicuro di eliminare definitivamente questo gruppo?'),
                                                            'class' => 'btn btn-danger btn-xs'
                                                        ]
                                                    )
                                                ?>
                                            </td>
                                            <?php else: ?>
                                            <td><?= $group->_matchingData['UserGroupMembers']->created ?></td>
                                            <?php endif ?>

                                        </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="12" class="text-muted text-center">
                                                <?php if ($this->request->getParam('pass.0', 'joined') == 'created') : ?>
                                                    <?= __('Non hai creato nessun gruppo') ?>
                                                <?php else: ?>
                                                    <?= __('Non sei inscritto a nessun gruppo') ?>
                                                <?php endif ?>
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

        </div>
    </div>
</div>

