<?php $this->append('css_head--inline') ?>
    .widget-user {
        margin-bottom:5px;
        display:inline-block;
        width:100%;
        display:inline-block;
    }

    .widget-user-info {
        position:relative;top:0;left:0;

        overflow:hidden;
        width:auto;
        text-align:center;
    }

    .widget-user-info-icon {
        position:absolute;top:-1px;right:22%;
        color:#00adee;
        opacity:0.88;
    }

    .widget-user-info-username {
        display:inline-block;
        width:100%;
    }

    #funjob-home-bigbrain-buttons .btn {
        margin-top:2px !important;
    }

    /* Smartphones (portrait and landscape) ----------- */
    @media only screen
    and (min-width : 320px)
    and (max-width : 480px) {
        .widget-user-info-icon {
            right:24%;
        }
    }
<?php $this->end() ?>

<div class="panel panel-sm panel-info">
    <div class="panel-heading">
        <div class="panel-title font-size-md2">
            <div class="text-truncate">
                <i class="fontello-brain"></i>
                BigBrain
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row gutter-10">

            <?php foreach ($bigbrains as $User) : ?>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <a href="<?= $this->Url->build($User->url) ?>" class="widget-user-block">
                    <div class="widget-user home-bigbrain-latests-popover">
                        <div class="widget-user-info">
                            <?=
                                $this->User->avatar($User->avatarSrcDesktop,  ['class' => 'widget-avatar-bigbrain-picture img-circle'])
                            ?>

                            <a href="<?= $this->Url->build($User->url) ?>" class="widget-user-info-username text-muted font-size-sm text-truncate">
                                <?= $User->username ?>
                            </a>
                        </div>
                    </div>
                </a>

            </div>
            <?php endforeach ?>

        </div>

        <div class="row gutter-10">
            <div id="funjob-home-bigbrain-buttons" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <a href="<?= $this->Url->build(['controller' => 'big_brains', 'action' => 'index']) ?>" class="btn btn-xs btn-default btn-block">
                    <i class="fa fa-archive text-color-primary"></i>
                    <?php echo __('Mostra tutti') ?>
                </a>
            </div>
            <div id="funjob-home-bigbrain-buttons" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <a href="<?= $this->Url->build(['_name' => 'bigbrains:contact']) ?>" class="btn btn-xs btn-default btn-block">
                    <i class="fontello-brain text-color-primary"></i>
                    <?php echo __('Collabora') ?>
                </a>
            </div>
        </div>
    </div>

</div>
