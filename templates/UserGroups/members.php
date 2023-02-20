<?php
    $this->assign('title', $userGroup->name);

    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Gruppi'), ['_name' => 'groups:archive']);
    $this->Breadcrumbs->add($userGroup->name, ['_name' => 'groups:view', 'id' => $userGroup->id, 'slug' => $userGroup->slug]);
    $this->Breadcrumbs->add(__('Utenti inscritti'), $this->request->getAttribute('here'));
?>

<?php $this->append('css_head--inline') ?>
    .user-vcard {min-height:86px;height:min-content;}
    .user-vcard .well {
        overflow:hidden !important;
        background-color:white !important;
    }
    .user-vcard hr {margin:5px;}
    .user-vcard .avatar {margin-right:5px;}

    .user-vcard-heading {min-height:40px;}
    .user-vcard-footer {}
<?php $this->end() ?>

<div class="page-header">
    <h1 class="font-size-lg">
        <?= $userGroup->name ?>
        <small><?= __('Iscritti a questo gruppo') ?></small>
    </h1>
</div>

<div class="row">
    <?php foreach ($userGroup->members as $user) : ?>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="user-vcard">
            <div class="well well-sm">
                <div class="avatar pull-left">
                    <?= $this->User->avatar($user->avatarSrcDesktop, ['class' => 'img-circle']) ?>
                </div>
                <div class="user-vcard-heading">
                    <strong class="font-size-md">
                        <a href="<?= $this->Url->build(['_name' => 'user:profile:home', 'id' => $user->id, 'username' => $user->slug]) ?>">
                            <?= $user->username ?>
                        </a>
                    </strong>
                    <br>
                    <small class="font-size-sm text-muted"><?= $user->fullname ?></small>
                </div>

                <hr>
                <div class="user-vcard-footer">
                    <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11 no-padding" style="overflow:hidden">
                        <span title="<?= $user->title ?>" class="font-size-sm text-truncate display-block">
                            <?= $this->Text->truncate($user->title, 100) ?>
                        </span>
                    </div>
                    <div class="no-padding col-xs-1 col-sm-1 col-md-1 col-lg-1">
                        <a href="<?= $this->Url->build(['_name' => 'user:profile:home', 'id' => $user->id, 'username' => $user->slug]) ?>">
                            <i class="fa fa-user"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
