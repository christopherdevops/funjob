<?php
    $this->assign('title', __('Amici'));
?>

<style>
    .friend--preferite i.star {
        color:orange;
        border:green;
    }

    .user-vcard {min-height:86px;height:min-content;}
    .user-vcard .well {
        overflow:hidden !important;
        background-color:white !important;
    }
    .user-vcard hr {margin:5px;}
    .user-vcard .avatar {margin-right:5px;}

    .user-vcard-heading {min-height:40px;}
    .user-vcard-footer {}
</style>

<div class="page-header">
    <h4 class="text-color-primary">Amici</h4>
</div>

<?php if ($friends->isEmpty()) : ?>
    <p class="font-size-md text-muted text-center">
        <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
        <?= __('Nessun amico') ?>
    </p>
<?php endif ?>

<div class="row">
    <?php foreach ($friends as $friend): $user = $friend->user ?>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="user-vcard">
            <div class="well well-sm">
                <div class="avatar pull-left">
                    <?= $this->User->avatar($user->avatarSrcDesktop, ['class' => 'img-circle']) ?>
                </div>
                <div class="user-vcard-heading">
                    <strong class="font-size-md">
                        <a href="<?= $this->Url->build(['_name' => 'user:profile:home', 'id' => $friend->user->id, 'username' => $friend->user->slug]) ?>">

                            <?php if ($friend->is_preferite) : ?>
                                <i class="fa fa-star"></i>
                            <?php endif ?>

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
                        <a href="<?= $this->Url->build(['_name' => 'message:compose:username', $friend->user->username]) ?>">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>


<?php if (!empty($users)) : ?>
    <?= $this->element('Paginator') ?>
<?php endif ?>
