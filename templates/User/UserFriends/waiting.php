<?php
    $this->assign('title', __('Richieste di amicizia'));

    $this->Breadcrumbs->add($UserAuth->username, '#');
    $this->Breadcrumbs->add(__('Amici'), ['action' => 'index']);
    $this->Breadcrumbs->add(__('Richieste in attesa'), '#');
?>

<?php $this->append('css_head--inline') ?>
    .user-vcard {min-height:86px;height:min-content;}
    .user-vcard .well {
        border-radius:6px !important;
        overflow:hidden !important;
        background-color:rgba(245,245,245,0.45) !important;
        border-width:1px !important;
        border-color:rgba(220,220,220,1) !important;
            box-shadow:1px 1px 1px rgba(200,200,200,0.80);
    }
    .user-vcard hr {margin:5px;}
    .user-vcard .avatar {margin-right:5px;}

    .user-vcard-heading {min-height:40px;}
    .user-vcard-footer {}

    .user-friend-star i {color:yellow}

    .user-friend-replyForm form {
        display:inline-block;
        padding:0;
    }

    .user-friend-replyForm form button {
        padding:1px;
        margin:1px;
    }
<?php $this->end() ?>

<?php echo $this->Element('UserFriends/top-bar') ?>
<hr>

<?php if ($requests->isEmpty()) : ?>
    <p class="font-size-md text-muted text-center">
        <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
        <?= __('Nessuna richiesta di amicizia al momento') ?>
    </p>
<?php endif ?>

<div class="row">
    <?php foreach ($requests as $friend): ?>
    <?php
        // Mappa utente in var $user a seconda se si la richiesta è stata creata o ricevuta
        if ($friend->_direction == 1) { $user = $friend->user_recipient; }
        else { $user = $friend->user_sent; }
    ?>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="user-vcard">
            <div class="well well-sm">
                <div class="avatar pull-left">
                    <?= $this->User->avatar($user->avatarSrcDesktop, ['class' => 'img-circle']) ?>
                </div>
                <div class="user-vcard-heading">
                    <strong class="font-size-md">
                        <a href="<?= $this->Url->build(['_name' => 'user:profile:home', 'id' => $user->id, 'username' => $user->slug]) ?>">

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
                    <div class="row gutter-10">
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" style="overflow:hidden">
                            <span title="<?= $user->title ?>" class="font-size-sm text-truncate display-block">
                                <?= $this->Text->truncate($user->title, 100) ?>
                            </span>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <div class="pull-right">
                                <?php if ($friend->_direction == 1) : ?>
                                    <p class="font-size-xs text-muted">
                                        <i class="fa fa-hourglass-half"></i>
                                        <?= __x('richiesta non ancora accettata', 'In attesa') ?>
                                    </p>
                                <?php else: ?>
                                    <div class="user-friend-replyForm">
                                        <?php
                                            echo $this->Form->create($friend, ['url' => ['action' => 'edit']]);
                                            echo $this->Form->control('id', ['type' => 'hidden']);
                                            echo $this->Form->control('is_accepted', ['value' =>  true, 'type' => 'hidden']);
                                            echo $this->Form->button(
                                                '<i class="fa fa-user-plus text-success"></i>',
                                                ['class' => 'btn btn-xs', 'escape' => false, 'title' => __('Accetta amicizia')]
                                            );
                                            echo $this->Form->end();

                                            echo $this->Form->create($friend, ['url' => ['action' => 'edit']]);
                                            echo $this->Form->control('id', ['type' => 'hidden']);
                                            echo $this->Form->control('is_accepted', ['value' =>  false, 'type' => 'hidden']);
                                            echo $this->Form->button(
                                                '<i class="fa fa-user-times text-danger"></i>',
                                                ['class' => 'btn btn-xs', 'escape' => false, 'title' => __('Rimuovi amicizia')]
                                            );
                                            echo $this->Form->end();
                                        ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<div class="row">
    <?php echo $this->element('pagination') ?>
</div>
