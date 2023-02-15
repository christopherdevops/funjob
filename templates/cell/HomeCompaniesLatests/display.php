<style type="text/css">

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

    /* Smartphones (portrait and landscape) ----------- */
    @media only screen
    and (min-width : 320px)
    and (max-width : 480px) {
        .widget-user-info-icon {
            right:24%;
        }
    }
</style>

<div class="">
    <div class="row gutter-10">

        <?php foreach ($users as $User) : ?>
        <div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
            <a href="<?= $this->Url->build($User->url) ?>" class="widget-user-block">
                <div class="widget-user home-company-latests-popover">
                    <div class="widget-user-info">
                        <?= $this->User->avatar($User->avatarSrcDesktop,  ['class' => 'widget-avatar-bigbrain-picture img-circle'])
                        ?>
                        <a href="<?= $this->Url->build(['_name' => 'user:profile', 'id' => $User->id, 'username' => $User->slug]) ?>" class="widget-user-info-username text-muted font-size-sm text-truncate">
                            <?= $User->username ?>
                        </a>
                    </div>
                </div>
            </a>

        </div>
        <?php endforeach ?>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
                echo $this->element('ui/well-help', [
                    'title'    => __('Cerchi aziende a cui potresti interessare?'),
                    'icon'     => 'text-color-primary fa-search',
                    'btnLabel' => __('Usa la ricerca aziende'),
                    'btnHref'  => $this->Url->build(['_name' => 'companies:index'])
                ]);
            ?>
        </div>
    </div>
</div>
