<?php
    $this->assign('header', ' ');
    $this->assign('title', __('Profilo di @{0}', $User->username));

    // Tabs js
    $this->Html->script([
        'users/view/tabs/quiz-completed',
        'users/view/tabs/quiz-created'
    ], ['block' => 'js_foot']);

    // Tab: QuizCompletati
    $this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot']);
    $this->Html->css(['/bower_components/select2/dist/css/select2.min.css', 'features/select2-bootstrap.min.css'], ['block' => 'css_foot']);

    $this->Html->script(['/bower_components/blockUI/jquery.blockUI.js'], ['block' => 'js_foot', 'once' => true]);
    $this->Html->css(['users/view.css'], ['block' => 'css_head']);

    $this->Breadcrumbs->add(__('Utenti'), '#');
    $this->Breadcrumbs->add(__('Profili'), '#');
    $this->Breadcrumbs->add($User->username);

    $myself = $this->UserProfile->isMyProfile();
?>
<?php $this->append('css_head--inline') ?>
    /* USER PROFILE PAGE */
     .card {
        padding: 30px;
        background-color: rgba(214, 224, 226, 0.2);
        -webkit-border-top-left-radius:5px;
        -moz-border-top-left-radius:5px;
        border-top-left-radius:5px;
        -webkit-border-top-right-radius:5px;
        -moz-border-top-right-radius:5px;
        border-top-right-radius:5px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .card.hovercard {
        position: relative;
        padding-top: 0;
        overflow: hidden;
        text-align: center;
        background-color: #fff;
        background-color: rgba(255, 255, 255, 1);
    }
    .card.hovercard .card-background {
        height: 130px;
    }
    .card-background img {
        /*
        -webkit-filter: blur(25px);
        -moz-filter: blur(25px);
        -o-filter: blur(25px);
        -ms-filter: blur(25px);
        filter: blur(25px);
        */
        margin-left: -100px;
        margin-top: -200px;
        min-width: 130%;
    }
    .card.hovercard .useravatar {
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
    }
    .card.hovercard .useravatar img {
        width: 100px;
        height: 100px;
        max-width: 100px;
        max-height: 100px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        border: 5px solid rgba(255, 255, 255, 0.5);
    }
    .card.hovercard .card-info {
        position: absolute;
        bottom: 14px;
        left: 0;
        right: 0;
    }
    .card.hovercard .card-info .card-title {
        padding:0 5px;
        font-size: 20px;
        line-height: 1;
        color: #262626;
        background-color: rgba(255, 255, 255, 0.1);
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .card.hovercard .card-info {
        overflow: hidden;
        font-size: 12px;
        line-height: 20px;
        color: #737373;
        text-overflow: ellipsis;
    }
    .card.hovercard .bottom {
        padding: 0 20px;
        margin-bottom: 17px;
    }
    .btn-pref .btn {
        -webkit-border-radius:0 !important;
    }

    .btn.flex-align-center {
        display:flex !important;
    }


    #funjob-userprofile-vcard .list-group-item .btn i {
        padding-right:4px
    }

    .funjob-userprofile-vcard-row {
        display:flex;
        justify-content: space-between;
    }
    .funjob-userprofile-vcard-row a[data-toggle] {
        vertical-align:middle;
        display:inline-block;
    }

    /* Altezza minima tab (per loader) */
    .user-profile-tabs, .user-profile-tabs .tab-pane,
    .user-profile-tabs .tab-pane.active {overflow:hidden;}
<?php $this->end() ?>

<?php
/**
 * Blocco: biglietto da visita
 */
?>

<?php $this->start('vcard') ?>
    <div id="funjob-userprofile-vcard" class="list-group">

        <div class="list-group-item disabled">
            <div class="funjob-userprofile-vcard-row">
                <?php if ($User->has('first_name') || $User->has('last_name')) : ?>
                    <span class="font-size-md2 text-bold">
                        <?php if ($User->show_fullname) : ?>
                            <?php echo $User->fullname ?>
                        <?php else: ?>
                            <?php echo $User->username ?>
                        <?php endif ?>
                    </span>
                    <div class="font-size-sm">
                        <?php
                        if ($myself) :
                            echo $this->Ui->popover([
                                'content' => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                                'text'    => $this->Ui->icon(['class' => 'fa fa-info']),
                                'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                            ]);
                        endif
                        ?>
                    </div>
                <?php else: ?>
                    <?php if ($myself) : ?>
                    <span class="font-size-md2">
                        <?= __('Il tuo nome e cognome') ?>
                    </span>
                    <?php else: ?>
                        <span class="font-size-md2">
                            @<?= $User->username ?>
                        </span>
                    <?php endif ?>

                    <div class="font-size-md">
                        <?php
                        if ($myself) :
                            echo $this->Ui->popover([
                                'content' => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                                'icon'    => $this->Ui->icon(['class' => 'fa fa-info']),
                                'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                            ]);
                        endif
                        ?>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <?php if (!empty($User->account_info) && $User->account_info->has('born_city') && $User->account_info->show_born_city) : ?>
        <div class="list-group-item">
            <div class="funjob-userprofile-vcard-row">
                <span class="font-size-md2">
                    <strong class="text-bold text-color-gray--dark"><?php echo __('Nato a:') ?></strong>
                    <a target="_blank" href="//maps.google.com/?q=<?= $User->account_info->born_city ?>">
                        <?= $User->account_info->born_city ?>
                    </a>
                </span>
                <div class="font-size-sm">
                    <?php if ($myself) : ?>
                        <?=
                            $this->Ui->helpPopover([
                                'text'   => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                                'icon'   => 'fa fa-info',
                                'escape' => false,
                                'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                            ])
                        ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php endif ?>

        <?php if (isset($User->account_info) && $User->account_info->birthday && $User->account_info->show_birthday) : ?>
        <div class="list-group-item" style="clear:both">
            <div class="font-size-md2">
                <strong class="text-bold text-color-gray--dark"><?php echo __('Nato il:') ?></strong>
                <?php echo $User->account_info->birthday->format('d/m/Y') ?>

                <?php
                    if ($myself) :
                        echo $this->Ui->helpPopover([
                            'text'   => __(
                                '<p class\'font-size-xs\'>Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo.</p>' .
                                '<p class=\'text-muted\'>Si consiglia di mostrarlo, in quanto qualche azienda potrebbe ricercare figure di una certa fascia di età.</p>'
                            ),
                            'class'  => 'font-size-sm pull-right',
                            'icon'   => 'fa fa-info',
                            'escape' => false,
                            'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                        ]);
                endif
                ?>
            </div>

        </div>
        <?php endif ?>

        <?php if (!empty($User->account_info) && $User->account_info->has('live_city') && $User->account_info->show_live_city) : ?>
        <div class="list-group-item">
            <div class="font-size-md2">
                <strong class="text-bold text-color-gray--dark"><?php echo __('Vive a:') ?></strong>
                <?php if (!empty($User->account_info->address) && $User->account_info->show_address) : ?>
                    <a target="_blank" href="//maps.google.com/?q=<?= $User->account_info->address .', '. $User->account_info->live_city ?>">
                        <?= $User->account_info->live_city ?>

                        <?php if (!empty($User->account_info->address) && $User->account_info->show_address) : ?>
                            <br>
                            <small><?= $User->account_info->address ?></small>
                        <?php endif ?>
                    </a>
                <?php else: ?>
                    <a target="_blank" href="//maps.google.com/?q=<?= $User->account_info->live_city ?>">
                        <?= $User->account_info->live_city ?>
                    </a>
                <?php endif ?>

                <?php
                    if ($myself) :
                        echo $this->Ui->helpPopover([
                            'text'  => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo.'),
                            'class' => 'font-size-sm pull-right',
                            'icon'  => 'fa fa-info',
                            'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                        ]);
                    endif;
                ?>
            </div>
        </div>
        <?php endif ?>

        <?php if (!empty($User->account_info) && $User->account_info->phone && $User->account_info->show_phone) : ?>
        <div class="list-group-item">
            <div class="font-size-md2">
                <strong class="text-bold text-color-gray--dark"><?php echo __('Telefono:') ?></strong>
                <?php echo $User->account_info->phone ?>
            </div>
        </div>
        <?php endif ?>




        <div class="list-group-item">
            <?php $hasCV = !empty($User->account_info->cv); ?>

            <?php if ($myself): ?>
                <?php
                    $url   = '#';
                    if ($hasCV) {
                        list($cv_uuid, $cv_ext) = explode('.', $User->account_info->cv);
                        $url = $this->Url->build(['_name' => 'cv:view', 'user_id' => $User->id, 'uuid' => $cv_uuid]);
                    }
                ?>
                <div class="btn-group" style="width:100%">

                    <a href="<?= $url ?>" class="btn btn-info btn-funjob-gray btn-sm <?= !$hasCV ? 'disabled' : '' ?>">
                        <div class="text-truncate font-size-md">
                            <i class="fa fa-file-text-o"></i>
                            <?php echo __('Curriculum Vitae') ?>
                        </div>
                    </a>

                    <?php if ($myself) : ?>
                        <button class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="text-truncate font-size-md">
                                <span class="caret"></span>
                            </div>
                        </button>
                        <ul class="dropdown-menu">
                           <li>
                                <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'job']) ?>">
                                    <i class="fa fa-cloud-upload"></i>
                                    <?= __('Carica') ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'job']) ?>">
                                    <i class="fa fa-eye"></i>
                                    <?= __('Visibilità') ?>
                                    <span class="font-size-sm"><?= __('pubblico / non pubblico') ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= $this->Url->build(['_name' => 'cv:authorizations:archive']) ?>">
                                    <i class="fa fa-eye"></i>
                                    <?= __('Autorizzazioni: richieste') ?>
                                </a>
                            </li>
                        </ul>
                    <?php endif ?>
                </div>
            <?php elseif ($hasCV && !$myself): ?>
                <?php list($cv_uuid, $cv_ext) = explode('.', $User->account_info->cv); ?>
                <a href="<?= $this->Url->build(['_name' => 'cv:view', 'user_id' => $User->id, 'uuid' => $cv_uuid]) ?>" class="btn btn-info btn-default btn-block btn-sm">
                    <i class="fa fa-file-text-o"></i>
                    <span class="font-size-md">
                        <?php echo __('Curriculum Vitae') ?>
                    </span>
                </a>
            <?php endif ?>
        </div>


        <?php if (!$myself) : ?>
        <div class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'message:compose:username', $User->username]) ?>" class="btn btn-info btn-default btn-block btn-sm">
                <i class="fa fa-envelope-o"></i>
                <span class="font-size-md">
                    <?php echo __('Invia messaggio') ?>
                </span>
            </a>
        </div>
        <div class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'message:compose:username', $User->username, '?' => ['context' => 'job_offer'], 'subject' => __('Offerta di lavoro')]) ?>" class="btn btn-default btn-block btn-sm">
                <i class="fa fa-envelope-o"></i>
                <span class="font-size-md">
                    <?php echo __('Invita a colloquio') ?>
                </span>
            </a>
        </div>
        <?php endif ?>

        <?php if (!$myself && in_array('isFriend', $this->getVars()) && empty($isFriend)) : ?>
        <div class="list-group-item">
            <?php
                echo $this->Form->create($isFriend, ['url' => ['prefix' => 'User', 'controller' => 'UserFriends', 'action' => 'add']]);
                echo $this->Form->control('user_id', ['value' => $this->request->getSession()->read('Auth.User.id'), 'type' => 'hidden']);
                echo $this->Form->control('friend_id', ['value' => $User->id, 'type' => 'hidden']);
                echo $this->Form->button(
                    __('{icon} {textStart}Invia amicizia{textEnd}', [
                        'icon'      => '<i class="fa fa-user-plus"></i>',
                        'textStart' => '<span class="font-size-md">',
                        'textEnd'   => '</span>'
                    ]),
                    ['class' => 'btn btn-info btn-block btn-sm', 'escape' => false]
                );
                echo $this->Form->end();
            ?>
        </div>
        <?php elseif (!$myself && isset($isFriend) && $isFriend && $isFriend->is_accepted) : ?>
        <div class="list-group-item">
            <?php
                echo $this->Form->create($isFriend, ['url' => ['prefix' => 'User', 'controller' => 'UserFriends', 'action' => 'edit']]);
                echo $this->Form->control('id', ['type' => 'hidden']);
                echo $this->Form->control('is_accepted', ['value' => false, 'type' => 'hidden']);
                echo $this->Form->button(
                    __('{icon} {textStart}Rimuovi amicizia{textEnd}', [
                        'icon'      => '<i class="fa fa-user-times"></i>',
                        'textStart' => '<span class="font-size-md">',
                        'textEnd'   => '</span>'
                    ]),
                    ['class' => 'btn btn-danger btn-block btn-sm', 'escape' => false]
                );
                echo $this->Form->end();
            ?>
        </div>
        <?php endif ?>

    </div>
<?php $this->end() ?>

<?php
/**
 * Blocco: links utente
 */
?>
<?php $this->start('user-links') ?>
    <div class="list-group">
        <div class="list-group-item disabled">
            <i class="fa fa-paperclip"></i>
            <?php echo __('Links') ?>
        </div>

        <?php foreach (explode("\n", $User->profile_block->links) as $text) : ?>
        <?php preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/i', $text, $href) ?>

        <?php if (!empty($href[0])) : ?>
        <div class="list-group-item">
            <a href="<?= $href[0] ?>"><?= str_replace($href, '', $text) ?></a>
        </div>
        <?php endif ?>
        <?php endforeach ?>

    </div>
<?php $this->end() ?>

<?php $this->start('user-friends') ?>
    <ul class="list-group">
        <div class="list-group-item disabled">
            <i class="fa fa-users"></i>
            <?php echo __('Amici') ?>
        </div>

        <li class="list-group-item" style="padding:5px">
        <?php if (empty($User->friends)) : ?>
            <div class="text-center">
                <i class="fa fa-frown-o" aria-hidden="true"></i>
                <br>
                <?= __('Nessun amico al momento') ?>
            </div>
        <?php endif ?>
        <?php foreach ($User->friends as $friend) : ?>
            <a style="padding-top:3px;" class="display-inline-block" href="<?= $this->Url->build($friend->user->url) ?>">
                <img
                    data-toggle="popover"
                    data-trigger="<?= ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' ) ?>"
                    data-content="<?= $friend->user->username ?> <br> <small class='font-size-sm'><?= $friend->user->title ?></small>"
                    data-html="true"
                    src="<?= $friend->user->imageSize($friend->user->avatarSrc, '28x28') ?>"
                    alt=""
                />
            </a>
        <?php endforeach ?>
        </li>

        <li class="list-group-item disabled">
            <button class="btn btn-default btn-xs btn-block" onclick="$('#profile-tab-friends').trigger('click')">
                <?= __('Vedi tutti') ?>
            </button>
        </li>

    </ul>
<?php $this->end() ?>


<div class="row">
    <div class="col-lg-12 col-sm-12">

        <?php // PROFILE COVER ?>
        <?php if (strpos($User->background_cover, 'gradient') === 0) : // Gradiente ?>
            <?php
                // Converte / in - (per selettore css)
                $class = str_replace('/', '-', $User->background_cover);

                list($type, $dir, $filename) = explode('/', $User->background_cover);
                $this->Html->css('gradients/'. $dir .'/'. $filename, ['block' => 'css_head']);
            ?>
        <?php else:
            $style = 'background-image:url(/backgrounds/' .$User->background_cover. '.jpg);background-size:cover';
        ?>
        <?php endif ?>

        <div class="card hovercard <?= !empty($class) ? $class : '' ?>" style="<?= !empty($style) ? $style : '' ; ?>">
            <div class="card-background"></div>

            <div class="useravatar">
                <a style="position:relative" href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'avatar']) ?>" title="<?php echo __('Cambia avatar') ?>">

                    <?php if ($User->is_bigbrain) : ?>
                    <i style="position:absolute;font-size:22px;background-color:white;border-radius:50%;" class="fontello-brain text-color-primary"></i>
                    <?php endif ?>

                    <?php
                        echo $this->User->avatar(
                            $User->imageSize($User->avatarSrc, '80x80'),
                            ['data-src' => 'holder.js/80x80?text=' . __('Cambia')]
                        )
                    ?>
                </a>
            </div>
            <div class="card-info">
                <span class="card-title" style="text-shadow:1px 1px 3px white;">
                    @<?php echo $User->username ?>
                </span>
            </div>
        </div>

        <?php // PROFILE TABS ?>
        <div class="btn-pref btn-group btn-group-justified user-profile-tabs" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-info" class="btn btn-md btn-default active" href="#tab1" data-toggle="tab">

                    <span class="text-color-primary fa fa-male" aria-hidden="true" aria-label="<?= __('Profilo') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Profilo') ?></span>
                </button>
            </div>

            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-quiz-created" class="btn btn-md btn-default" href="#quizzes--created" data-toggle="tab">
                    <span class="text-color-primary fa fontello-quiz-new" aria-hidden="true" aria-label="<?= __('Giochi creati') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Giochi Creati') ?></span>
                </button>
            </div>

            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-quiz-completed" class="btn btn-md btn-default" href="#quizzes--completed" data-toggle="tab">
                    <span class="text-color-primary fa fontello-quiz-play" aria-hidden="true" aria-label="<?= __('Risultati Condivisi') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Risultati Condivisi') ?></span>
                </button>
            </div>

            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-friends" class="btn btn-md btn-default" href="#friends" data-toggle="tab">
                    <span class="text-color-primary fa fa-user" aria-hidden="true" aria-label="<?= __('Amici') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Amici') ?></span>
                </button>
            </div>

            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-groups" class="btn btn-md btn-default" href="#groups" data-toggle="tab">
                    <span class="text-color-primary fa fa-users" aria-hidden="true" aria-label="<?= __('Gruppi') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Gruppi') ?></span>
                </button>
            </div>

            <?php
                // FUTURE:
                /*
                <div class="btn-group" role="group">
                    <button type="button" id="profile-tab-tornaments" class="btn btn-md btn-default" href="#tournaments" data-toggle="tab">
                        <span class="text-color-primary fa fa-trophy" aria-hidden="true" aria-label="<?= __('Tornei') ?>"></span>
                        <span class="text-color-gray hidden-xs"><?= __('Tornei') ?></span>
                    </button>
                </div>
                */
            ?>

        </div>

        <?php // TABS CONTENT ?>
        <div class="user-profile-tabs">
            <div class="tab-content">

                <div class="tab-pane fade in active" id="tab1">

                    <div class="funjob-userprofile-header page-header">
                        <div class="flexbox-space-between">
                            <div class="funjob-userprofile-actor">

                                <h2 class="no-margin text-muted visible-xs-block visible-sm-block visible-md-inline visible-lg-inline" style="font-size:15px;display:inline-flex !important;margin-left:10px !important">
                                    <span class="text-color-primary">
                                        <i class="text-color-gray--dark fa fa-user"></i>
                                        <?php if (!empty($User->title)) : ?>
                                            <?= $User->title ?>
                                        <?php else: ?>
                                            <?=  __('140 caratteri per descrivere te o la tua professione') ?>
                                        <?php endif ?>

                                        <?php if ($myself) : ?>
                                        <?php
                                            echo $this->Ui->helpPopover([
                                                'text' => __('140 caratteri per descrivere te o la tua professione'),
                                                'class' => 'font-size-sm',
                                                'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                                            ])
                                        ?>
                                        <?php endif ?>
                                    </span>

                                </h2>
                            </div>
                            <div class="funjob-userprofile-edit">
                                <?php if ($this->UserProfile->isMyProfile()) : ?>
                                <a href="<?= $this->Url->build(['_name' => 'me:settings']) ?>" class="btn btn-md btn-default btn-xs-block" style="border-color:#00adee">
                                    <i class="text-color-primary fa fa-cogs"></i>
                                    <span style="color:#808080">
                                        <?= __('Modifica {0} profilo {1}', '<span class="hidden-xs">', '</span>') ?>
                                    </span>
                                </a>
                                <?php endif ?>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="app-user-profile-vcard col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <?php echo $this->fetch('vcard') ?>

                            <?php if (!empty($User->user_profile_box->links)) : ?>
                                <?= $this->fetch('user-links') ?>
                            <?php endif ?>

                            <?= $this->fetch('user-friends') ?>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                            <div role="tabpanel">

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="ver1">
                                        <?php echo $this->element('UserProfile/profile') ?>
                                    </div>
                                </div>

                            </div>

                            <?php
                            /**
                                <link rel="stylesheet" href="http://www.jqueryscript.net/demo/Mobile-Friendly-Bootstrap-Tabs-Enhancement-with-jQuery/dist/css/bootstrap-responsive-tabs.css">
                                <script type="text/javascript" src="http://www.jqueryscript.net/demo/Mobile-Friendly-Bootstrap-Tabs-Enhancement-with-jQuery/dist/js/jquery.bootstrap-responsive-tabs.min.js"></script>
                                <script type="text/javascript">
                                $(function() {
                                    $('.responsive-tabs').responsiveTabs({
                                        accordionOn: ['xs', 'sm'] // xs, sm, md, lg
                                    });
                                });
                                </script>
                            **/
                            ?>
                        </div>
                    </div>

                </div>

                <div class="tab-pane fade in" id="quizzes--created">
                    <noscript>
                        <div class="alert alert-danger">
                            <p class="font-size-lg">
                                <?= __('Questo contenuto richiede JavaScript') ?>
                            </p>
                        </div>
                    </noscript>
                </div>

                <div class="tab-pane fade in" id="quizzes--completed">
                    <noscript>
                        <div class="alert alert-danger">
                            <p class="font-size-lg">
                                <?= __('Questo contenuto richiede JavaScript') ?>
                            </p>
                        </div>
                    </noscript>
                </div>

                <div class="tab-pane fade in" id="friends">
                    <noscript>
                        <div class="alert alert-danger">
                            <p class="font-size-lg">
                                <?= __('Questo contenuto richiede JavaScript') ?>
                            </p>
                        </div>
                    </noscript>
                </div>

                <div class="tab-pane fade in" id="groups">
                    <noscript>
                        <div class="alert alert-danger">
                            <p class="font-size-lg">
                                <?= __('Questo contenuto richiede JavaScript') ?>
                            </p>
                        </div>
                    </noscript>
                </div>

                <?php
                    // FUTURE
                    /**
                    <div class="tab-pane fade in" id="tournaments">
                        <div class="page-header">
                            <p class="font-size-md text-center text-muted">
                                <?= __('Questa funzionalità sarà disponibile in futuro') ?>
                            </p>
                        </div>
                        <p class="font-size-md text-muted text-center">
                            <i class="fa fa-trophy" style="font-size:20em;opacity:0.11"></i>
                        </p>
                    </div>
                    */
               ?>
            </div>
        </div>

    </div>
</div>

<?php $this->append('js_foot') ?>
<script>
    var UI_AJAX_TAB = (function (settings) {
        var $req = undefined;
        var api  = {};
        var _api = {};

        api.load = function(ajaxSettings) {
            //if (ajaxSettings.beforeSend == undefined) {
            ajaxSettings.beforeSend = api.ajaxLoader;
            //}

            $req = $.ajax(ajaxSettings);

            $req.done(api.ajaxSuccess);
            $req.fail(api.ajaxFailure);
            $req.always(api.ajaxAlways);

            return $req;
        };

        api.ajaxSuccess = function(response) {
            console.log(this);
            this.html(response);
            Holder.run();
        };

        /* Ajax error / http error */
        api.ajaxFailure = function(jxhr, errStatus, errThrow) {
            console.log(this);
            alertify.error(errStatus);
        };

        api.ajaxAlways = function() {
            //this.unblock();
            $.unblockUI();
        };

        /* Tab loader */
        api.ajaxLoader = function() {
            var options = {};
            //this.block(options);
            $.blockUI(options);
        }

        return api;
    });

    $(document).ready(function() {

        $("*[data-toggle=popover]").popover({
            container: "body",
            placement: "auto"
        });

        // AJAX TABS
        $("#profile-tab-quiz-completed").on("click", function() {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#quizzes--completed"),
                url      : "<?= $this->Url->build(['_name' => 'user:quizzes:completed', 'user_id' => $User->id]) ?>",
                timeout  : 10000 // in ms
            });
        });

        $("#profile-tab-quiz-created").on("click", function() {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#quizzes--created"),
                url      : "<?= $this->Url->build(['_name' => 'user:quizzes:created', 'user_id' => $User->id]) ?>",
                timeout  : 10000 // in ms
            });
        });

        $("#profile-tab-friends").on("click", function() {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#friends"),
                url      : "<?= $this->Url->build(['_name' => 'user:profile:friends', 'id' => $User->id, 'username' => $User->slug]) ?>",
                timeout  : 10000 // in ms
            });
        });

        $("#profile-tab-groups").on("click", function() {
            var tab = UI_AJAX_TAB();
            tab.load({
                context  : $("#groups"),
                url      : "<?= $this->Url->build(['_name' => 'user:profile:groups', 'id' => $User->id, 'username' => $User->slug]) ?>",
                timeout  : 10000 // in ms
            });
        });

        $(".btn-pref .btn").click(function () {
            $(".btn-pref .btn").removeClass("active");
            $(this).addClass("active");
        });



        // Paginazione tramite ajax
        // Paginazione ajax
        $("body").on("click", "li.next a[rel], li.previous a[rel]", function(evt) {
            evt.preventDefault();

            var $this = $(this);
            var url   = $this.attr("href");

            if (url == undefined) {
                return false;
            }

            var $tabContent = $(".tab-pane.active");
            $tabContent.load(url, function() {
                document.querySelector(".app-content").scrollIntoView();
            });
        });

    });
</script>

<script>
    $(function() {
        if (window.location.hash == '#cv-request')
        {
            bootbox.dialog({
                title  : <?= json_encode(__('CV con restrizioni')) ?>,
                message: document.querySelector("#tpl-cv-request").innerHTML
            });
        }
    });
</script>
<?php $this->end() ?>

<script type="text/template" id="tpl-cv-request">
    <?php echo $this->element('cv-authorization-request') ?>
</script>
