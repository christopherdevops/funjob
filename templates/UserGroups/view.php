<?php
    $this->assign('title', $userGroup->name);
    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Gruppi'), ['_name' => 'groups:archive']);
    $this->Breadcrumbs->add($userGroup->name);

    $this->Html->meta('keywords', $userGroup->keywords, ['block' => true]);
    $this->Html->css('usergroups/view.css', ['block' => 'css_head']);
?>

<?php // Ultimi utenti registrati ?>
<?php $this->start('members') ?>
    <ul class="list no-margin no-padding" style="list-style-type:none">
        <?php foreach ($userGroup->members as $user) : ?>
        <li>
            <a href="<?= $this->Url->build(['_name' => 'user:profile', 'id' => $user->id, 'username' => $user->slug]) ?>">
                <?= $this->User->avatar($user->avatarSrcDesktop) ?>
                <?= $user->username ?>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->end() ?>

<div class="usergroup-header page-header">
    <h1 class="text-color-primary usergroup-header-title">
        <span class="text-truncate display-block"><?= $userGroup->name ?></span>
    </h1>
    <div id="user-group-actions" class="visible-xs-block visible-sm-block visible-md-inline visible-lg-inline">
        <?php
            if (!$joined) {
                echo $this->Form->create(null, ['url' => ['_name' => 'groups:join'], 'class' => 'display-inline-block']);
                echo $this->Form->hidden('id', ['value' => $userGroup->id]);
                echo $this->Form->button(
                    __x('testo bottone + icona', '{0} Entra nel gruppo', '<i class="fa fa-check-circle-o"></i>'),
                    ['class' => 'btn btn-sm btn-success', 'escape' => false]
                );
                echo $this->Form->end();
            } elseif ($joined) {
                echo $this->Form->create(null, ['url' => ['_name' => 'groups:leave'], 'class' => 'display-inline-block']);
                echo $this->Form->hidden('id', ['value' => $userGroup->id]);
                echo $this->Form->button(
                    __x('testo bottone + icona', '{0} Esci dal gruppo', '<i class="fa fa-times-circle-o"></i>'),
                    ['class' => 'btn btn-sm btn-danger visible-sm visible-md visible-lg', 'escape' => false]
                );
                echo $this->Form->button(
                    __x('testo bottone + icona', '{0} Esci', '<i class="fa fa-times-circle-o"></i>'),
                    ['class' => 'btn btn-sm btn-danger visible-xs', 'escape' => false]
                );
                echo $this->Form->end();
            }
        ?>

        <?php
            $user_id = (int) $this->request->getSession()->read('Auth.User.id');
            $admins  = new \Cake\Collection\Collection($userGroup->administrators);
            $isAdministator = $admins->firstMatch(['user_id' => $user_id]);
        ?>
        <?php if ($isAdministator) : ?>
            <a title="<?= __('Impostazioni gruppo') ?>" href="<?= $this->Url->build(['action' => 'edit', $userGroup->id ]) ?>" class="btn btn-sm btn-success">
                <i class="fa fa-cogs"></i>
                <span class="visible-md-inline visible-lg-inline">
                    <?= __('Modifica gruppo') ?>
                </span>
            </a>
        <?php endif ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">

        <div class="thumbnail">
            <?php if ($userGroup->has('image')) : ?>
            <img class="user-group-cover img-responsive" src="<?= $userGroup->imageSize($userGroup->coverSrc, '400x400') ?>" data-src="holder.js/400x400?text=ND&auto=yes" alt="" />

            <?php if ($isAdministator) : ?>
            <caption class="figcaption font-size-sm text-center">
                <?=
                    __('Puoi caricare la foto da {url}',
                        ['url' => $this->Html->link(__('qui'), ['action' => 'edit', $userGroup->id])]
                    )
                ?>
            </caption>
            <?php endif ?>
            <?php endif ?>
        </div>

        <div class="panel panel-sm panel-info" id="usergroup-detail-members">
            <div class="panel-heading">
                <h3 class="panel-title" style="overflow:hidden">
                    <div class="text-truncate font-size-md">
                        <?= __('Iscritti ({counter})', ['counter' => $userGroup->members_count]) ?>
                    </div>
                </h3>
            </div>
            <div class="panel-body">
                <?= $this->fetch('members') ?>
            </div>
            <div class="panel-footer">
                <a href="<?= $this->Url->build(['_name' => 'groups:members', 'id' => $userGroup->id, 'slug' => $userGroup->slug]) ?>">
                    <i class="fa fa-archive"></i>
                    <?= __('Mostra tutti') ?>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

        <?php if ($userGroup->descr) : ?>
        <div id="usergroup-detail-descr" class="panel panel-info panel-sm">
            <div class="panel-heading">
                <div class="panel-title">
                    <span class="text-bold"><?= __('Tema del gruppo') ?></span>
                </div>
            </div>
            <div class="panel-body">
                <div class="text-muted text-truncate">
                    <?= $this->Text->autoParagraph($userGroup->descr) ?>
                </div>
            </div>
        </div>
        <?php endif ?>

        <div class="well well-sm well-default">
            <?php
                echo $this->Form->create(null);
                echo $this->Form->control('text', [
                    'type'        => 'textarea',
                    'disabled'    => 'disabled',
                    'label'       => __('{icon} Nuovo Post', ['icon' => '<i class="text-color-primary fa fa-file-text-o"></i>']),
                    'placeholder' => __('Pubblica nuovo post visibile a tutti i membri del gruppo'),
                    'help'        => __('Prossimanente potrai pubblicare i tuoi messaggi'),
                    'escape'      => false
                ]);
                echo $this->Form->button(__('Pubblica post'), ['disabled' => 'disabled']);
                echo $this->Form->end();
            ?>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-bold text-truncate font-size-md">
                    <i class="text-color-primary fa fa-file-text-o"></i>
                    <span class="text-color-primary">12/07/2017</span>

                    <?= __('Funzionalità in sviluppo') ?>
                </div>
            </div>
            <div class="panel-body">
                <div class="row gutter-10">
                    <div class="hidden-xs col-sm-2 col-md-2 col-lg-2">
                        <div class="thumbnail text-center">
                            <i class="text-color-primary fontello-brain" style="font-size:30px !important"></i>
                            <?= __('Funjob.it') ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">

                        <div class="visible-xs thumbnail text-center">
                            <i class="text-color-primary fontello-brain" style="font-size:30px !important"></i>
                            <span class="text-bold"><?= __('Funjob.it') ?></span>
                        </div>

                        <p class="font-size-md">
                            <?= __('Presto attraverso i gruppi potrai socializzare con altri utenti che hanno i tuoi stessi interessi') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
