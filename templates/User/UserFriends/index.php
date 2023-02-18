<?php
    $this->assign('title', __('Amici'));

    $this->Breadcrumbs->add($UserAuth->username, '#');
    $this->Breadcrumbs->add(__('Amici'), ['action' => 'index']);
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
    .user-vcard hr {margin:5px;border-width:2px}
    .user-vcard .avatar {margin-right:5px;}

    .user-vcard-heading {min-height:40px;}
    .user-vcard-footer {}

    .user-friend-star i {color:#00adee}

    .user-friend-replyForm {
        text-align:right;
    }

    .user-friend-replyForm form {
        display:inline-block;
        padding:0;
    }

    .user-friend-replyForm form button {
        padding:1px 1px;
    }
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
<script>
    $(function() {
        $('.user-friend-star button').hover(function() {
            var $this = $(this).find("i");
            $this.attr("class", $this.attr("data-mouseinclass"));
        }, function() {
            var $this = $(this).find("i");
            $this.attr("class", $this.attr("data-mouseoutclass"));
        });
    });
</script>
<?php $this->end() ?>

<?php echo $this->Element('UserFriends/top-bar') ?>
<hr>

<?php if ($friends->isEmpty()) : ?>
    <p class="font-size-md text-muted text-center">
        <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
        <?= __('Nessuna amico al momento ...') ?>
    </p>
<?php endif ?>

<div class="row">
    <?php foreach ($friends as $friend) : $user = $friend->user ?>
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
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 no-padding" style="overflow:hidden">
                            <span title="<?= $user->title ?>" class="font-size-sm text-truncate display-block">
                                <?= $this->Text->truncate($user->title, 100) ?>
                            </span>
                        </div>
                        <div class="no-padding col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <?php if ($friend->_direction == 1) : ?>
                                <p class="font-size-xs text-muted"><?= __('In attesa') ?></p>
                            <?php else: ?>
                                <div class="user-friend-replyForm">
                                    <?php
                                        // Rimuovi amico
                                        echo $this->Form->create($friend, ['url' => ['action' => 'edit']]);
                                        echo $this->Form->control('id', ['type' => 'hidden']);
                                        echo $this->Form->control('is_accepted', ['value' =>  false, 'type' => 'hidden']);
                                        echo $this->Form->button(
                                            '<i class="text-danger fa fa-user-times fa-fw"></i> ',
                                            ['class' => 'btn btn-sm', 'escape' => false, 'title' => __('Rifiuta amicizia')]
                                        );
                                        echo $this->Form->end();
                                    ?>

                                    <?php
                                        // Preferiti
                                        if ($friend->is_preferite) {
                                            $flagSwap['icon']      = 'fa fa-fw fa-star';
                                            $flagSwap['iconHover'] = 'fa fa-fw fa-star-o';
                                            $flagSwap['value']     = false;
                                            $flagSwap['title']     = __('Aggiungi a preferiti');
                                        } else {
                                            $flagSwap['icon']      = 'fa fa-fw fa-star-o';
                                            $flagSwap['iconHover'] = 'fa fa-fw fa-star';
                                            $flagSwap['value']     = true;
                                            $flagSwap['title']     = __('Elimina da preferiti');
                                        }

                                        echo $this->Form->create($friend, ['class' => 'user-friend-star', 'url' => ['action' => 'star']]);
                                        echo $this->Form->control('id', ['type' => 'hidden']);
                                        echo $this->Form->control('is_preferite', [
                                            // Swap flag
                                            'value' =>  $flagSwap['value'],
                                            'type' => 'hidden'
                                        ]);
                                        echo $this->Form->button(
                                            '<i data-mouseinclass="'.$flagSwap['iconHover'].'" data-mouseoutclass="'. $flagSwap['icon'].'" class="'.$flagSwap['icon'].'"></i> ',
                                            ['class' => 'btn btn-sm', 'escape' => false, 'title' => $flagSwap['title']]
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
    <?php endforeach ?>
</div>

<div class="row">
    <?php echo $this->element('pagination') ?>
</div>
