<?php
    $this->assign('title', __('Ciao {name}', ['name' => $UserAuth->username]));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Pannello di controllo'), ['_name' => 'me:dashboard'])
        ->add($UserAuth->username);
?>

<?php $this->append('css_head--inline') ?>
    #account-shortcut li a {
        display:block;
            width:100%;
        padding-left: 30px;
    }

    #account-shortcut li a:nth-child(1) {
        padding-left:0;
    }

    /* da spostare in design2.css */
    .list-group-sm li {padding:4px !important;}
    .list-group-xs li {padding:2px !important;}
    .list-group-md li {padding:6px !important;}


    /* BUTTONS */
    .user-dashboard-block .btn {
        padding:4px !important;
        border-radius:10px !important;
    }
    .user-dashboard-icon--job {color:gray !important;}

    .user-dashboard-block:hover {
        color:black !important;
        text-shadow:1px 1px 1px whitesmoke !important;
    }
    .user-dashboard-block .font-size-md {
        font-size:10px !important;
    }

    .user-dashboard-block .btn-default {color:#00adee !important;}

    .user-dashboard-icon  {
        display:block !important;
        margin:0 auto;
    }

    /* HEADERS */
    .funjob-dashboard-well {
        border-radius: 9px !important;
    }
    .funjob-dashboard-well .page-header {
        margin:0 0 10px 0 !important;
        padding:0 0 9px 0;
        border-width:5px;
    }

    .user-dashboard-block {
        text-align:center;
        margin:0 auto;
        color:#00adee;
        display:block;
        padding:4px !important;
        border-radius:10px !important;
    }

    .user-dashboard-block.dropdown {
        padding:0 !important;
    }


    /* WELL CONTEXTs */
    .funjob-dashboard-well--job .page-header {border-color:gray !important;}

    .funjob-dashboard-well--job .user-dashboard-block .btn-default,
    .funjob-dashboard-well--job .user-dashboard-block{color:gray !important;}
<?php $this->end() ?>

<?php $this->start('personal') ?>
    <ul id="account-shortcut" class="list-group list-group-sm">
        <li class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'me:profile']) ?>">
                <div style="overflow:hidden">
                    <?php
                    /*
                    <img class="img-circle img-responsive pull-left" src="<?= $UserAuth->imageSize($UserAuth->avatarSrc, '28x28') ?>" />
                    */
                    ?>

                    <i class="fa fa-fw fa-user"></i>
                    <?= __('Profilo pubblico') ?>
                </div>
            </a>
        </li>

        <li class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'me:settings']) ?>">
                <i class="fa fa-fw fa-cogs"></i>
                <?= __('Impostazioni') ?>
            </a>
        </li>

        <li class="list-group-item">
            <a href="<?= $this->Url->build(['_name' => 'auth:logout']) ?>" class="text-danger">
                <i class="fa fa-fw fa-sign-out"></i>
                <?= __('Esci') ?>
            </a>
        </li>
    </ul>
<?php $this->end() ?>

<?php $this->start('block:fun') ?>
    <!-- FUN -->
    <div class="page-header">
        <p class="no-margin user-dashboard-title text-center font-size-lg font-family--alba">Fun</p>
    </div>
    <div class="row gutter-10">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php
                echo $this->element('ui/bs3-dropdown', [
                    'title'         => __('Gioca'),
                    'dropdownClass' => 'user-dashboard-block',
                    'icon'          => 'fa-gamepad',
                    'links'         => [
                        __('Ultimi inseriti') => ['_name' => 'quiz:index'],
                        __('Popolari')        => ['_name' => 'quiz:index', '?' => ['sort_by' => 'rank']],
                    ]
                ])
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'quiz:create']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                <i class="user-dashboard-icon fa fa-fw fa-check fa-3x"></i>
                <span class="font-size-md">
                    <?= __('Crea gioco') ?>
                </span>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'me:quizzes']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                <i class="user-dashboard-icon fa fa-fw fa-archive fa-3x"></i>
                <span class="font-size-md">
                    <?= __('I miei quiz') ?>
                </span>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
<?php $this->end() ?>

<?php $this->start('block:job') ?>
    <!-- JOB -->
    <div class="page-header">
        <p style="color:gray" class="no-margin user-dashboard-title text-center font-size-lg font-family--alba">Job</p>
    </div>
    <div class="row gutter-10">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php if ($User->type == 'company') : ?>
                <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'profile']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                    <i class="user-dashboard-icon user-dashboard-icon--job fa fa-fw fa-user fa-3x"></i>
                    <span class="font-size-md">
                        <?= __('Profilo aziendale') ?>
                    </span>
                </a>
            <?php else: ?>
                <?php
                    echo $this->element('ui/bs3-dropdown', [
                        'title'         => __('Curriculum'),
                        'dropdownClass' => 'user-dashboard-block',
                        'icon'          => 'user-dashboard-icon--job fa-file-text-o',
                        'links'         => [
                            __('Carica')         => ['_name' => 'me:settings', '#' => 'job'],
                            __('Autorizzazioni') => ['_name' => 'cv:authorizations:archive'],
                        ]
                    ])
                ?>
            <?php endif ?>
        </div>

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php if ($User->type == 'company') : ?>
                <a href="<?= $this->Url->build(['_name' => 'user:search']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                    <i class="user-dashboard-icon user-dashboard-icon--job fa fa-fw fa-handshake-o fa-3x"></i>
                    <span class="font-size-md">
                        <?= __('Ricerca candidati') ?>
                    </span>
                </a>
            <?php else: ?>
            <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'tab-skills']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                <i class="user-dashboard-icon user-dashboard-icon--job fa fa-fw fa-cogs fa-3x"></i>
                <span class="font-size-md">
                    <?= __('Competenze') ?>
                </span>
            </a>
            <?php endif ?>
        </div>


        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php if ($User->type == 'company') : ?>
                <a href="<?= $this->Url->build(['_name' => 'companies:categories:archive']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                    <i class="user-dashboard-icon user-dashboard-icon--job fa fa-fw fa-building-o fa-3x"></i>
                    <span class="font-size-md">
                        <?= __('Aziende') ?>
                    </span>
                </a>
            <?php else: ?>
                <?php
                    echo $this->element('ui/bs3-dropdown', [
                        'title'         => __('Lavoro'),
                        'dropdownClass' => 'user-dashboard-block',
                        'icon'          => 'user-dashboard-icon--job fa-handshake-o',
                        'links'         => [
                            __('Cerca aziende')   => ['_name' => 'companies:categories:archive'],
                            __('Cerca candidati') => ['_name' => 'user:search'],
                        ]
                    ])
                ?>
            <?php endif ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
<?php $this->end() ?>

<?php $this->start('block:social') ?>
    <div class="page-header">
        <h2 style="color:gray" class="no-margin user-dashboard-title text-center font-size-lg font-family--alba">
            <div style="letter-spacing:3px;display:inline">Funjob</div> <?= __('Community') ?>
        </h2>
    </div>
    <div class="row gutter-10">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php
                echo $this->element('ui/bs3-dropdown', [
                    'title'         => __('Amici'),
                    'dropdownClass' => 'user-dashboard-block',
                    'icon'          => 'fa-users',
                    'links'         => [
                        __('Amici')               => ['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'index'],
                        __('Richieste in attesa') => ['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'index', 0 => 'waiting'],
                    ]
                ])
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php
                echo $this->element('ui/bs3-dropdown', [
                    'title'         => __('Gruppi di utenti'),
                    'dropdownClass' => 'user-dashboard-block',
                    'icon'          => 'fa-university',
                    'links'         => [
                        __('Archivio')                   => ['_name' => 'groups:archive'],
                        __('Gruppi a cui sei inscritto') => ['_name' => 'mygroups:archive:joined'],
                        __('Gruppi da te creati')        => ['_name' => 'mygroups:archive:created']
                    ]
                ])
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'user:search']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                <i class="user-dashboard-icon fa fa-fw fa-search fa-3x"></i>
                <span class="font-size-md">
                    <?= __('Cerca utente') ?>
                </span>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
<?php $this->end() ?>

<?php $this->start('block:misc') ?>
    <div class="page-header">
        <h2 style="color:gray" class="no-margin user-dashboard-title text-center font-size-lg font-family--alba">
            <?= __('Varie') ?>
        </h2>
    </div>
    <div class="row gutter-10">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php
                echo $this->element('ui/bs3-dropdown', [
                    'title'         => __('Premi'),
                    'dropdownClass' => 'user-dashboard-block',
                    'icon'          => 'fa-gift',
                    'links'         => [
                        __('Negozio')         => ['_name' => 'store:index'],
                        __('Premi richiesti') => ['_name' => 'me:orders'],
                    ]
                ])
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php
                echo $this->element('ui/bs3-dropdown', [
                    'title'         => __('Pubblicità'),
                    'dropdownClass' => 'user-dashboard-block',
                    'icon'          => 'fa-area-chart',
                    'links'         => [
                        __('Crea annuncio')  => ['prefix' => 'sponsor', 'controller' => 'SponsorAdvs', 'action' => 'add'],
                        __('Annunci creati') => ['prefix' => 'sponsor', 'controller' => 'SponsorAdvs', 'action' => 'index'],
                    ]
                ])
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['plugin' => false, 'prefix' => false, 'controller' => 'contacts']) ?>" class="user-dashboard-block btn btn-default btn-sm">
                <i class="user-dashboard-icon fa fa-fw fa-envelope fa-3x"></i>
                <span class="font-size-md">
                    <?= __('Contatti') ?>
                </span>
            </a>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        </div>
    </div>
<?php $this->end() ?>

<?php // Auguri di buon compleanno ?>
<?php if (!empty($User->account_info->birthday) && \Cake\I18n\Time::now()->format('d/m') == $User->account_info->birthday->format('d/m')) : ?>
    <div class="row gutter-10">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-info">
                <div class="pull-right">
                    <i class="fa fa-smile-o"></i>
                </div>

                <p class="text-muted">
                    <i class="pull-left fa fa-birthday-cake fa-4x" aria-hidden="true"></i>
                    <span class="font-size-lg">
                        <?php
                            $now = \Cake\Chronos\Chronos::now();
                            $age = $now->diffInYears($User->account_info->birthday);
                        ?>
                        <?= __('Tanti auguri per il tuo {count}° compleanno.', ['count' => $age]) ?>
                    </span>
                </p>
                <span class="pull-right"><?= __('Lo staff di FunJob.it') ?></span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if ($CVrequests && $CVrequests > 0) : ?>
<div class="alert alert-sm alert-info">
    <?=
        __(
            '{count} persone vorrebbero visualizzare il tuo CV, abilitale da {link}',
            [
                'count' => $CVrequests,
                'link'  => '<strong>' . $this->Html->link(__('questa pagina'), ['_name' => 'cv:authorizations:archive']) . '</strong>'
            ]
        )
    ?>
</div>
<?php endif ?>

<?php if (!$User->is_verified_mail) : ?>
    <div class="alert alert-sm alert-warning">
        <div class="text-center text-bold">
            <i class="fa fa-envelope"></i>
            <?= __('La tua e-mail non è verificata... non potrai utilizzare il nostro negozio') ?>
        </div>
        <div class="pull-right">
            <?php
                echo $this->Form->create(null, ['url' => ['_name' => 'account:confirmation_resend']]);
                echo $this->Form->hidden('id', ['value' => $User->id]);
                echo $this->Form->submit(__('Invia il codice'), [
                    'class' => 'btn btn-xs btn-default'
                ]);
                echo $this->Form->end();
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
<?php endif ?>

<div class="row gutter-10">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <?php echo $this->fetch('personal') ?>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="row gutter-10">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="funjob-dashboard-well well well-sm well-info">
                    <?php echo $this->fetch('block:fun') ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="funjob-dashboard-well funjob-dashboard-well--job well well-sm well-default">
                    <?php echo $this->fetch('block:job') ?>
                </div>
            </div>
        </div>

        <div class="row gutter-10">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="funjob-dashboard-well funjob-dashboard-well--job  well well-sm well-default">
                    <?php echo $this->fetch('block:social') ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="funjob-dashboard-well funjob-dashboard-well--job well well-sm well-default">
                    <?php echo $this->fetch('block:misc') ?>
                </div>
            </div>
        </div>
    </div>
</div>
