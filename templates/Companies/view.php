<?php
    $this->assign('header', ' ');
    $this->assign('title', __('Profilo aziendale: @{0}', $User->username));

    $this->Html->script(['/bower_components/blockUI/jquery.blockUI.js'], ['block' => 'js_foot']);
    $this->Html->css(['companies/view.css'], ['block' => 'css_head']);

    $this->Breadcrumbs->add(__('Utenti'), '#');
    $this->Breadcrumbs->add(__('Profili'), '#');
    $this->Breadcrumbs->add($User->username);

    $myself = $this->UserProfile->isMyProfile();
?>

<?php // Blocco: biglietto da visita ?>
<?php $this->start('vcard') ?>
    <div id="funjob-userprofile-vcard" class="list-group">

        <div class="list-group-item disabled">
            <div class="funjob-userprofile-vcard-row">

                <span class="font-size-md3 text-bold text-color-gray--dark">
                    <?php echo __('Dati aziendali') ?>
                </span>

                <div class="font-size-md">
                    <?php
                    if ($myself) :
                        echo $this->Ui->popover([
                            'content' => __('Potrai configurare queste informazioni dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                            'icon'    => $this->Ui->icon(['class' => 'fa fa-info']),
                            'trigger' => ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' )
                        ]);
                    endif
                    ?>
                </div>

            </div>
        </div>

        <div class="list-group-item">
            <i class="fa fa-fw fa-building-o"></i>
            <span class="text-color-gray--dark font-size-md">
                <?php if (!empty($User->name)) : ?>
                <?= $User->name ?>
                <?php else: ?>
                    <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'profile']) ?>">
                        <?php echo __('Ragione sociale (modifica)') ?>
                    </a>
                <?php endif ?>
            </span>
        </div>

        <?php if (!empty($User->address)) : ?>
        <div class="list-group-item">
            <span class="text-color-gray--dark font-size-md">
                <i class="fa fa-fw fa-address-book-o"></i>
                <?= $User->address ?>
            </span>
        </div>
        <?php endif ?>


        <?php if (!empty($User->account_info->city) && $User->account_info->show_city) : ?>
        <div class="list-group-item">
            <i class="fa fa-fw fa-map-o"></i>
            <a class="text-color-gray--dark" target="_blank" href="//maps.google.com/?q=<?= $User->account_info->city ?>">
                <span class="font-size-md">
                    <?= $User->account_info->city ?>
                </span>
            </a>
        </div>
        <?php endif ?>

        <?php if (!empty($User->account_info->url)) : ?>
        <div class="list-group-item">
            <a href="<?= $User->account_info->url ?>" target="_blank">
                <i class="fa fa-fw fa-link"></i>
                <span class="font-size-md">
                    <?php echo __('Pagina ufficiale') ?>
                </span>
            </a>
        </div>
        <?php endif ?>

    </div>
<?php $this->end() ?>

<?php // Blocco: azioni (riflessive) ?>
<?php $this->start('actions:myself') ?>
<?php $this->end() ?>

<?php // Blocco: azioni ?>
<?php $this->start('actions') ?>
    <div class="list-group">


        <div class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'message:compose:username', $User->username]) ?>" class="btn btn-info btn-default btn-block btn-sm">
                <i class="fa fa-envelope-o"></i>
                <span class="font-size-md">
                    <?php echo __('Invia messaggio') ?>
                </span>
            </a>
        </div>

        <div class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'message:compose:username', $User->username, '?' => ['context' => 'job_request'], 'subject' => __('Richiesta posizione lavorativa')]) ?>" class="btn btn-default btn-block btn-sm">
                <i class="fa fa-envelope-o"></i>
                <span class="font-size-md">
                    <?php echo __('Proponiti per colloquio') ?>
                </span>
            </a>
        </div>

        <?php if (!$myself && in_array('isFriend', $this->getVars()) && empty($isFriend)) : ?>
        <div class="list-group-item">
            <?php
                echo $this->Form->create($isFriend, ['url' => ['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'add']]);
                echo $this->Form->control('user_id', ['value' => $this->request->session()->read('Auth.User.id'), 'type' => 'hidden']);
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
                echo $this->Form->create($isFriend, ['url' => ['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'edit']]);
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

        <li class="list-group-item">
        <?php if (empty($User->friends)) : ?>
            <div class="text-center">
                <i class="fa fa-frown-o" aria-hidden="true"></i>
                <br>
                <?= __('Nessun amico al momento') ?>
            </div>
        <?php endif ?>
        <?php foreach ($User->friends as $friend) : ?>
            <a href="<?= $this->Url->build($friend->user->url) ?>">
                <img
                    data-toggle="popover"
                    data-trigger="<?= ( $this->request->is('mobile') || $this->request->is('tablet') ? 'click' : 'hover' ) ?>"
                    data-content="<?= $friend->user->username ?> <br> <small class='font-size-sm'><?= $friend->user->title ?></small>"
                    data-html="true"
                    src="<?= $friend->user->imageSize($friend->user->avatarSrc, '28x28') ?>"
                    alt="<?= $friend->user->username ?>"
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
                <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'avatar']) ?>" title="<?php echo __('Cambia avatar') ?>">
                    <?= $this->User->avatar($User->avatarSrc, ['data-src' => 'holder.js/80x80?text=' . __('Cambia') ]) ?>
                </a>
            </div>
            <div class="card-info">
                <span class="card-title" style="text-shadow:1px 1px 3px white;">
                    <?php echo $User->username ?>
                </span>

            </div>
        </div>

        <?php // PROFILE TABS ?>
        <div class="btn-pref btn-group btn-group-justified" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-info" class="btn btn-md btn-default active" href="#tab1" data-toggle="tab">

                    <span class="text-color-primary fa fa-vcard-o" aria-hidden="true" aria-label="<?= __('Profilo') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Profilo') ?></span>
                </button>
            </div>

            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-quiz-created" class="btn btn-md btn-default" href="#quizzes--created" data-toggle="tab">
                    <span class="text-color-primary fontello-quiz-play" aria-hidden="true" aria-label="<?= __('Giochi Creati') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Giochi Creati') ?></span>
                </button>
            </div>

            <?php
            /*
            <div class="btn-group" role="group">
                <button type="button" id="profile-tab-quiz-completed" class="btn btn-md btn-default" href="#quizzes--completed" data-toggle="tab">
                    <span class="text-color-primary fa fa-flag-checkered" aria-hidden="true" aria-label="<?= __('Quiz completati') ?>"></span>
                    <span class="text-color-gray hidden-xs"><?= __('Quiz completati') ?></span>
                </button>
            </div>
            */
            ?>

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

        </div>

        <?php // TABS CONTENT ?>
        <div class="">
            <div class="tab-content">

                <div class="tab-pane fade in active" id="tab1">

                    <div class="page-header funjob-userprofile-actor-header">
                        <div class="flexbox-space-between">
                            <div class="funjob-userprofile-actor">

                                <h2 class="font-size-md text-muted visible-xs-block visible-sm-block visible-md-inline visible-lg-inline funjob-userprofile-actor-title">

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
                            <?php echo $this->fetch('actions') ?>

                            <?php if (!empty($User->profile_block->links)) : ?>
                                <?= $this->fetch('user-links') ?>
                            <?php endif ?>

                            <div class="hidden-xs">
                                <?= $this->fetch('user-friends') ?>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                            <div role="tabpanel">

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="ver1">
                                        <?php echo $this->element('CompanyProfile/profile') ?>
                                    </div>
                                </div>

                            </div>
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

                <?php /*
                <div class="tab-pane fade in" id="quizzes--completed">
                    <noscript>
                        <div class="alert alert-danger">
                            <p class="font-size-lg">
                                <?= __('Questo contenuto richiede JavaScript') ?>
                            </p>
                        </div>
                    </noscript>
                </div>
                */
                ?>

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
        };

        /* Ajax error / http error */
        api.ajaxFailure = function(jxhr, errStatus, errThrow) {
            console.log(this);
            alertify.error(errStatus);
        };

        api.ajaxAlways = function() {
            $.unblockUI();
        };

        /* Tab loader */
        api.ajaxLoader = function() {
            $.blockUI();
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

    });
</script>
<?php $this->end() ?>
