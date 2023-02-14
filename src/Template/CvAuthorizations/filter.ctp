<?php $this->start('empty') ?>
    <div class="container-fluid">
        <div class="alert alert-info">
            <?= __('Niente da mostrare per il momento') ?>
        </div>
    </div>
<?php $this->end() ?>

<?php $this->start('entities') ?>
    <div class="cvAuthorizations index large-9 medium-8 columns content">
        <table cellpadding="0" cellspacing="0" class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th scope="col"><?= __('Utente') ?></th>
                    <th scope="col"><?= __('Autorizzato') ?></th>
                    <th scope="col"><?= __x('Data in cui è stata richiesta autorizzazione CV', 'Richiesta in data') ?></th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($cvAuthorizations as $cvAuthorization): ?>
            <tr class="js-cv_authorization-entity">
                <td>
                    <?php if ($cvAuthorization->has('requester')) : ?>
                    <?=
                        $this->Html->link(
                            $cvAuthorization->requester->username,
                            [
                                '_name'    => 'user:profile',
                                'id'       => $cvAuthorization->requester->id,
                                'username' => $cvAuthorization->requester->slug
                            ]
                        )
                    ?>
                    <?php endif ?>
                    </td>
                <td>
                    <div class="js-cv_authorizations-status btn-group" role="group" aria-label="...">
                        <?php if ($cvAuthorization->allowed === null) : // Da mostrare solo per quelli in pending ?>
                        <button type="button" class="btn btn-sm btn-default <?= $cvAuthorization->allowed === null ? 'active' : '' ?>">
                            <i class="fa fa-question"></i>
                        </button>
                        <?php endif ?>

                        <button type="button" class="btn btn-sm btn-default <?= $cvAuthorization->allowed === true ? 'active' : '' ?>">
                            <i class="fa fa-check text-success"></i>
                        </button>
                        <div class="js-cv_authorization-form hidden">
                            <?php
                                echo $this->Form->create($cvAuthorization, [
                                    'url' => ['_name' => 'cv:authorizations:update', 0 => $cvAuthorization->id, '_ext' => 'json']
                                ]);
                                echo $this->Form->control('id', ['type' => 'hidden', 'value' => $cvAuthorization->id]);
                                echo $this->Form->control('allowed', ['type' => 'hidden', 'value' => true]);
                                echo $this->Form->end();
                            ?>
                        </div>

                        <button type="button" class="btn btn-sm btn-default <?= $cvAuthorization->allowed === false ? 'active' : '' ?>">
                            <i class="fa fa-remove text-danger"></i>
                        </button>
                        <div class="js-cv_authorization-form hidden">
                            <?php
                                echo $this->Form->create($cvAuthorization, ['url' => ['_name' => 'cv:authorizations:update', 0 => $cvAuthorization->id]]);
                                echo $this->Form->control('id', ['type' => 'hidden', 'value' => $cvAuthorization->id]);
                                echo $this->Form->control('allowed', ['type' => 'hidden', 'value' => false]);
                                echo $this->Form->end();
                            ?>
                        </div>
                    </div>
                </td>
                <td><?= h($cvAuthorization->created) ?></td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <?= $this->element('pagination') ?>
<?php $this->end() ?>


<?php if ($cvAuthorizations->isEmpty()) : ?>
    <?= $this->fetch('empty') ?>
<?php else: ?>
    <?= $this->fetch('entities') ?>
<?php endif ?>
