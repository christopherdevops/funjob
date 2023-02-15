<?php
    use Cake\Core\Configure;

    $this->Html->script(['/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js'], ['block' => 'js_foot']);
    $this->Html->css(['/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css'], ['block' => 'css_foot']);

    $is_authed = $this->request->getSession()->check('Auth.User');
?>

<?php // NON UTILIZZATO ?>
<?php $this->start('avatar:dropdown') ?>
    <div class="dropdown">
        <button style="background-color:transparent;border:0;" class="dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
            <?php
                //echo $this->User->avatar($UserAuth->avatarSrcMobile, ['class' => 'img-circle visible-xs visible-sm']);
                //echo $this->User->avatar($UserAuth->avatarSrcDesktop, ['class' => 'img-circle visible-md visible-lg']);
            ?>
           <span class="pull-left caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1" style="right: 0; left: auto;">
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="<?= $this->Url->build(['_name' => 'me:profile']) ?>">
                    <i class="fa fa-cogs"></i>
                    <?= __('Impostazioni') ?>
                </a>
            </li>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="<?= $this->Url->build(['_name' => 'me:profile']) ?>">
                    <i class="fa fa-cogs"></i>
                    <?= __('Profilo') ?>
                </a>
            </li>
        </ul>
    </div>
<?php $this->end() ?>

<nav class="app-nav-primary navbar navbar-default navbar-fixed-top Fixed">
    <?php // PRIMARY MENU ?>
    <div class="app-menu-primary container no-padding">
        <div class="row no-margin">

            <div class="col-xs-4 col-sm-5 col-md-5 no-padding">

                <a href="#menu" id="app-menu-primary" class="app-menu-primary-btn text-center flex-align-center flex-align-center--inline">
                    <span><?= __('MENU') ?></span>
                    <i class="fa fa-bars"></i>
                </a>
            </div>

            <div class="col-xs-4 col-sm-2 col-md-2">
                <a style="position:relative;" href="<?= $this->Url->build(['_name' => 'home']) ?>" class="app-menu-primary-logo-btn">
                    <img src="logo.png" class="app-menu-primary-logo animated pulse" alt="">
                </a>
            </div>

            <div class="col-xs-4 col-sm-5 col-md-5 no-padding">
                <div class="row row-height-100">
                    <div data-class="pull-right flex-align-center" style="height:100%">
                        <div class="funjob-primary-menu-right-container container-fluid">

                            <ul class="funjob-primary-menu-right-list <?= $this->request->getSession()->check('Auth.User.id') ? 'funjob-primary-menu-right-list--authed' : '' ?> list-inline pull-right">

                                <li style="padding-left:0 !important">
                                    <a id="lang-selector" href="#">
                                        <div class="visible-xs-inline">
                                            <i class="fa fa-globe"></i>
                                            <span class="font-size-xs">
                                                <?php echo $this->request->getSession()->read('Config.language') ?>
                                            </span>
                                        </div>
                                        <div class="visible-sm-inline visible-md-inline visible-lg-inline">
                                            <i class="fa fa-globe"></i>
                                            <?php echo $this->request->getSession()->read('Config.language') ?>
                                        </div>
                                    </a>
                                </li>


                                <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                                <li>
                                    <?php
                                        $uid = $this->request->getSession()->read('Auth.User.id');
                                        echo $this->cell('Inbox::unreadCount',
                                            ['user_id' => $uid],
                                            ['cache' => ['config' => 'user_inbox', 'key' => 'user_' . $uid]]
                                        );
                                    ?>
                                </li>
                                <li>
                                    <a class="display-block" href="<?php echo $this->Url->build($UserAuth->url) ?>">
                                        <?php
                                            echo $this->User->avatar($UserAuth->avatarSrcMobile, ['class' => 'img-circle visible-xs-inline visible-sm-inline']);
                                            echo $this->User->avatar($UserAuth->avatarSrcDesktop, ['class' => 'img-circle visible-md-inline visible-lg-inline']);
                                        ?>
                                    </a>
                                </li>
                                <?php else: ?>
                                <li>
                                    <a href="<?= $this->Url->build(['_name' => 'auth:login']) ?>">
                                        <i class="fa fa-key"></i>
                                        <span class="hidden-xs"><?php echo __('Accedi') ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $this->Url->build(['_name' => 'auth:register']) ?>">
                                        <i class="fa fa-lock"></i>
                                        <span class="hidden-xs"><?php echo __('Registrati') ?></span>
                                    </a>
                                </li>
                                <?php endif ?>

                            </ul>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<nav class="app-menu-nav-secondary navbar navbar-default navbar-fixed-top app-menu-secondary ">
    <?php // SECONDARY MENU: PHONES ?>
    <div class="row no-padding" style="">

        <div class="col-md-12 no-padding">
            <?php $md5 = md5($this->request->getSession()->read('Auth.User.email')); ?>

            <div class="app-menu-tab app-menu-tab--scroller">
                <ul class="app-menu-tab-list">

                    <?php if (!$this->request->cookie('menu-slide-tutorial')) : ?>
                    <li id="app-menu-tab-list-item-tutorial" class="app-menu-tab-list-item app-menu-tab-list-item--header hidden-lg hidden-md" style="padding-right:10px;">
                        <img src="img/menu-touch-slider-tutorial.png" style="max-height:40px;" alt="">
                    </li>
                    <?php endif ?>

                    <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <a href="<?= $this->Url->build(['_name' => 'me:dashboard']) ?>">
                            <i class="font-size-md fa fa-universal-access"></i>
                            <span class="font-size-sm"><?= __('Dashboard') ?></span>
                        </a>
                    </li>
                    <?php endif ?>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <a href="<?= $this->Url->build(['_name' => 'home']) ?>">
                            <i class="font-size-md fontello-home" style="font-size:14px"></i>
                            <span class="font-size-sm"><?php echo __('Home') ?></span>
                        </a>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--header app-menu-tab-list-item--fun hidden-sm hidden-xs">
                        <img style="width:20px;position:relative;top:-4px" src="img/fun-circle.png" alt="" />
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--header app-menu-tab-list-item--fun hidden-md hidden-lg">
                        <img style="height:28px;position:relative;top:-4px" src="img/fun-circle.png" alt="" />
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <a href="<?= $this->Url->build(['_name' => 'quiz:index' /*'quiz-categories:search'*/]) ?>">
                            <i class="font-size-md fontello-quiz-play"></i>
                            <span class="font-size-sm"><?php echo __('Gioca') ?></span>
                        </a>
                    </li>
                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <a href="<?= $this->Url->build(['_name' => 'quiz:create']) ?>">
                            <i class="font-size-md fontello-quiz-new"></i>
                            <span class="font-size-sm"><?php echo __('Crea') ?></span>
                        </a>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <a href="#" class="funjob-pix-tutorial">
                            <i class="font-size-md fontello-credits"></i>
                            <span class="font-size-sm">
                                <?php echo $this->cell('UserCredits', [], []) ?>
                            </span>
                        </a>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <?php
                            $uid = $this->request->getSession()->read('Auth.User.id');
                            echo $this->cell(
                                'UserFriendsRequests::display',
                                ['user_id' => $uid],
                                [] //['cache' => ['config' => 'user_friends_waiting', 'key' => 'user_' . $uid]]
                            );
                        ?>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--fun">
                        <a href="<?= $this->Url->build(['_name' => 'store:index']) ?>">
                            <i class="font-size-md fontello-market"></i>
                            <span class="font-size-sm"><?php echo __('Negozio') ?></span>
                        </a>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--header app-menu-tab-list-item--job hidden-sm hidden-xs">
                        <img style="width:20px;position:relative;top:-4px" src="img/job-circle.png" alt="" />
                    </li>
                    <li class="app-menu-tab-list-item app-menu-tab-list-item--header app-menu-tab-list-item--job hidden-md hidden-lg">
                        <img style="height:28px;position:relative;top:-4px" src="img/job-circle.png" alt="" />
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--job <?= !$is_authed ? 'app-menu-tab-list-item--disabled' : '' ?>">
                        <a href="<?= ( $is_authed ? $this->Url->build(['_name' => 'me:profile']) : $this->Url->build(['_name' => 'auth:login']) ) ?>">
                            <i class="font-size-md fa fa-graduation-cap "></i>
                            <span class="font-size-sm"><?php echo __('Profilo') ?></span>
                        </a>
                    </li>

                    <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                    <li class="app-menu-tab-list-item app-menu-tab-list-item--job">
                        <a href="<?= $this->Url->build(['_name' => 'me:quizzes:completed']) ?>">
                            <i class="font-size-md fa fa-check-square-o"></i>

                            <span class="hidden-xs hidden-sm font-size-sm"><?= __('Risultati') ?></span>
                            <span class="visible-xs visible-sm font-size-sm"><?= __('Risultati') ?></span>
                        </a>
                    </li>
                    <?php endif ?>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--job <?= !$is_authed ? 'app-menu-tab-list-item--disabled' : '' ?>">
                        <a href="<?= ( $is_authed ? $this->Url->build(['_name' => 'user:search']) : $this->Url->build(['_name' => 'auth:login']) ) ?>">
                            <i class="font-size-md fa fa-users"></i>
                            <span class="font-size-sm"><?php echo __('Utenti') ?></span>
                        </a>
                    </li>

                    <?php
                        // FUTURE:
                        /*
                        <li class="app-menu-tab-list-item app-menu-tab-list-item--job">
                            <a href="<?= $this->Url->build(['_name' => 'leaderboard:index']) ?>">
                                <i class="font-size-md fa fa-list-ol"></i>

                                <span class="hidden-xs hidden-sm font-size-sm"><?= __('Classifica')?></span>
                                <span class="visible-xs visible-sm font-size-sm"><?= __('Classifica')?></span>
                            </a>
                        </li>
                        */
                    ?>
                    <li class="app-menu-tab-list-item app-menu-tab-list-item--job">
                        <a href="<?= $this->Url->build(['_name' => 'companies:categories:archive']) ?>">
                            <i class="font-size-md fa fa-handshake-o"></i>
                            <span class="font-size-sm"><?= __('Aziende') ?></span>
                        </a>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--job">
                        <a href="<?= $this->Url->build(['_name' => 'groups:archive']) ?>">
                            <i class="font-size-md fa fa fa-university"></i>
                            <span class="font-size-sm"><?= __('Gruppi') ?></span>
                        </a>
                    </li>

                    <li class="app-menu-tab-list-item app-menu-tab-list-item--job">
                        <a href="<?= $this->Url->build(['prefix' => false, 'controller' => 'contacts']) ?>">
                            <i class="font-size-md fa fa fa-envelope"></i>
                            <span class="font-size-sm"><?= __('Contattaci') ?></span>
                        </a>
                    </li>

                </ul>
            </div>

        </div>

    </div>
</nav>


<script type="text/javascript">
    var scroller = function($scroller) {
        $scroller.mCustomScrollbar({
            theme     : "minimal-dark",
            axis      : "x",    // horizontal scrollbar

            setWidth  : false,
            setLeft   : 0,

            scrollbarPosition   : "inside",
            autoHideScrollbar   : true,
            autoExpandScrollbar : false,
            alwaysShowScrollbar : 1,

            scrollButtons:{
                enable: false,
            },

            advanced: {
                autoExpandHorizontalScroll: 2
            },

            callbacks: {
                onScroll: function() {
                    var $tutorial = $("#app-menu-tab-list-item-tutorial");

                    if ($tutorial.is(":visible")) {
                        $tutorial.remove();
                        Cookies.set('menu-slide-tutorial', 'hide', {});
                        $scroller.mCustomScrollbar("update");
                    }
                }
            }
        });
    }


    $(function() {
        $(window).on("load resize", function(evt) {
            var list_h = $(".app-menu-tab-list").outerHeight();

            if (list_h >= 57) {
                var $scroller = $(".app-menu-tab--scroller");
                if (!$scroller.hasClass("mCustomScrollbar")) {
                    console.log(" [*] scrollbar ");
                    scroller($scroller);
                }
            }
        });
    })
</script>

<script>
    $(function() {
        $("#lang-selector").on("click", function(evt) {
            evt.preventDefault();
            bootbox.dialog({
                className : "funjob-modal",
                message   : document.querySelector("#tpl-lang-selector").innerHTML
            });
        });
    });
</script>
<script type="text/template" id="tpl-lang-selector">
    <?php
        echo $this->Form->create(null, ['url' => ['prefix' => false, 'controller' => 'Users', 'action' => 'language']]);
        echo $this->Form->input('language', [
            'label'   => __('Lingue disponibili'),
            'empty'   => __('-- Seleziona'),
            'options' => Configure::read('app.languages')
        ]);
        echo $this->Form->button(__('Cambia lingua'), ['class' => 'btn btn-sm btn-primary']);
        echo $this->Form->end();
    ?>
</script>
