<?php
    $this->assign('title', __('I nostri collaboratori'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Collaboratori'), $this->request->here);
?>

<?php $this->append('css_head--inline') ?>
    .first-letter-primary-color {
        color:gray;
        display:inline-block;
        font-family: "Courier New", cursive, sans-serif;
        font-weight:bold;
    }
    .first-letter-primary-color:first-letter {
        color:#00adee !important;
    }

    /* AVATAR + LOGO BIGBRAIN */
    .funjob-avatar-bigbrain {
        position:relative;
        overflow:auto;
    }

    .funjob-avatar-bigbrain-picture {
        position:relative;top:0;
        margin:0 auto;
        min-height:50px !important;
    }

    .funjob-avatar-bigbrain-badge {
        position:absolute;bottom:0;left:0px;

        background-color:#00adee;
        opacity:0.89;
        border-radius:50%;
        padding:2px;
    }
    .funjob-avatar-bigbrain-icon {
        font-size:100%;
        color:white;
    }

    .funjob-bigbrain-user {padding:5px;box-shadow: 1px 1px 1px rgba(200,200,200,0.80);}
    .bigbrain-user-areas {margin-top:13px;}
    .bigbrain-user-username {margin-top:2px}

    .bigbrain-title-icon {margin-right:-5px}

    .bigbrain-wellinfo {padding:3px !important}


    @media only screen and (min-width : 320px) and (max-width : 480px) {
        blockquote.font-size-md3 {font-size:14px !important;}
    }
<?php $this->end() ?>

<?php $this->start('filters') ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <span class="text-bold text-color-gray--dark">
                <?= __('Ordina per tempo collaborazione') ?>
            </span>
            &nbsp;&nbsp;
            <?php

                echo $this->Paginator->sort(
                    'bigbrain_from',
                    __('{icon} più recenti', ['icon' => '<i class="fa fa-arrow-up"></i>']),
                    ['direction' => 'ASC', 'lock' => true, 'escape' => false]
                );
                echo '&nbsp;';
                echo $this->Paginator->sort(
                    'bigbrain_from',
                    __('{icon} meno recenti', ['icon' => '<i class="fa fa-arrow-down"></i>']),
                    ['direction' => 'DESC', 'lock' => true, 'escape' => false]
                );
            ?>
        </div>
    </div>
<?php $this->end() ?>



<div class="page-header">
    <h1 class="no-margin">
        <i class="bigbrain-title-icon fontello-brain text-color-primary"></i>
        <span class="first-letter-primary-color">Big</span><span class="first-letter-primary-color">Brain</span>
    </h1>
</div>

<div id="bigbrains-definition">
    <div class="well-info well-sm well">
        <blockquote class="font-size-md3" style="border-left-color:white !important;padding-top:0;padding-bottom:0;">
            <?= __('Solo i migliori diventano Big Brain.Per diventare un BigBrain dovrai conoscere bene un argomento e redigere giochi ad esso riferiti.') ?>
            <br>
            <?= __('Dopo averci contattato certificheremo il tuo profilo ed oltre a guadagnare dai tuoi giochi, avrai maggiore visibilità con le aziende.') ?>
        </blockquote>

        <div class="row">

            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <a href="<?= $this->Url->build(['_name' => 'bigbrains:contact']) ?>" class="btn btn-sm btn-block btn-default text-bold">
                    <i class="text-color-primary fa fa-envelope"></i>
                    <?= __('Diventa un BigBrain') ?>
                </a>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:user']) ?>" class="btn btn-sm btn-block btn-default text-bold">
                    <i class="text-color-primary fa fa-info-circle"></i>
                    <?= __('Maggiori informazioni') ?>
                </a>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="well well-sm">
            <?php echo $this->fetch('filters') ?>
        </div>

    </div>
</div>

<div id="bigbrains-users">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="bigbrain-wellinfo well well-success well-sm" style="overflow:hidden">
                <p class="font-size-md3 text-bold  text-center">
                    <i class="text-color-primary fontello-brain" style="font-size:300%"></i>
                    <?= __('Vuoi diventare un nostro collaboratore?') ?>
                </p>

                <a href="<?= $this->Url->build(['_name' => 'bigbrains:contact']) ?>" class="btn btn-sm btn-default btn-block">
                    <span class="text-color-gray--dark text-bold">
                        <i class="fa fa-envelope text-color-primary"></i>
                        <?= __('Richiedi') ?>
                    </span>
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="bigbrain-wellinfo well well-warning well-sm" style="overflow:hidden">
                <p class="font-size-md3 text-bold text-center">
                    <i class="text-color-primary fontello-brain" style="font-size:300%"></i>
                    <?= __('Cerchi visibilità? Compari in questa pagina!!') ?>
                </p>

                <a href="<?= $this->Url->build(['_name' => 'bigbrains:contact']) ?>" class="btn btn-sm btn-default btn-block">
                    <span class="text-color-gray--dark text-bold">
                        <i class="fa fa-envelope text-color-primary"></i>
                        <?= __('Richiedi') ?>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <?php foreach ($bigbrains->chunk(2) as $users): ?>
    <div class="row">
        <?php foreach ($users as $User): ?>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

            <a href="<?= $this->Url->build(['_name' => 'user:profile', 'id' => $User->id, 'username' => $User->slug]) ?>" class="display-block well well-sm funjob-bigbrain-user">

                <div class="row gutter-10">
                    <div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
                        <div class="funjob-avatar-bigbrain">
                            <img class="img-circle" style="width:50px;height:50px" src="<?= $User->imageSize($User->avatarSrc, '80x80') ?>" alt="" />
                            <span class="funjob-avatar-bigbrain-badge hidden-xs">
                                <i class="funjob-avatar-bigbrain-icon fontello-brain"></i>
                            </span>
                        </div>

                        <span class="bigbrain-user-username font-size-md display-block text-truncate">
                            @<?= $User->username ?>
                        </span>

                    </div>
                    <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                        <div class="container-fluid">
                            <h4 class="no-margin font-size-md2 text-bold">
                                <span class="text-muted">
                                    <?= $User->fullname ?>
                                </span>
                            </h4>
                            <h6 class="font-size-md">
                                <?php if (!empty($User->account_info->profession)) : ?>
                                    <i class="fa fa-briefcase text-color-gray--dark"></i>
                                    <?= $User->account_info->profession ?>
                                <?php else: ?>
                                    &nbsp;
                                <?php endif ?>

                                <?php if (!empty($User->bigbrain_area)) : ?>
                                    <div class="bigbrain-user-areas text-truncate text-italic">
                                        <i class="fa fa-tags"></i>
                                        <span class="font-size-sm"><?= $User->bigbrain_area ?></span>
                                    </div>
                                <?php else: ?>
                                    &nbsp;
                                <?php endif ?>

                            </h6>
                        </div>
                    </div>
                </div>

            </a>

        </div>
        <?php endforeach ?>
    </div>
    <?php endforeach ?>
</div>
<?php echo $this->element('pagination') ?>

<script type="text/javascript">
    $(function() {

        $("#bigbrains-users .funjob-bigbrain-user")
            .on("mouseover", function(evt) {
                $(this).addClass('well-info');
            })
            .on("mouseout", function(evt) {
                $(this).removeClass('well-info');
            })
    });
</script>
