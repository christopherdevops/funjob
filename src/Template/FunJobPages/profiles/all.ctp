<?php
    $this->assign('content--class', 'content--fullscreen col-xs-12 col-sm-12 col-md-12 col-lg-12');
    $this->assign('title', __('A chi è rivolto Funjob?'));
    $this->assign('header', ' ');

    $this->Html->script(['/bower_components/readmore-js/readmore.min.js'], ['block' => 'js_foot']);
    $this->Html->css([])
?>

<?php $this->append('css_head--inline') ?>
    .funjob-detail-wallpaper {
        position:relative;
    }

    .funjob-detail-wallpaper-title {
        position:absolute;top:20%;left:20%;right:20%;
        text-align:center;
        display:block;
        background-color:rgba(145,145,145, 0.66);
        text-shadow:1px 1px 2px whitesmoke;
        padding:10px;
    }


    .funjob-profile-features-list .list-group-item i[class*="icon-"],
    .funjob-profile-features-list .list-group-item i[class*="fa-"],
    .funjob-profile-features-list .list-group-item i[class*="fontello-"]
    {
        color: #00adee;
    }

    .funjob-profile-features-list .list-group-item h4 {
        margin:0;
        font-weight:normal;
    }

    .funjob-features-image {
        margin:10px;
        max-height:90px;
    }

    .funjob-features-footer--col_1 {
        padding-left:15px !important;
        padding-right:5px !important;
    }

    .funjob-features-footer--col_2 {
        padding-right:15px !important;
        padding-left:5px !important;
    }

    hr {
        border-color:rgba(0,173,238,0.47) !important;
        margin:5px 4% 5px 4% !important;
    }

    .funjob-features-title {
        color:#00adee;
        text-transform:uppercase;
        font-weight:bold;

        display:inline-flex;
        align-items:center;
    }

    .funjob-features-title i.fa,
    .funjob-features-title i[class^="fontello"] {
        font-size:30px;
        color:#00adee;
        margin-right:5px;
    }

<?php $this->end() ?>

<?php $this->start('funjob-profile-features') ?>
    <div>
        <ul class="funjob-profile-features-list list-group list-group-sm">
            <li class="list-group-item text-center disabled">
                <?= __('Caratteristiche') ?>
            </li>
            <?= $this->fetch('funjob-profile-features--items') ?>
        </ul>
    </div>

    <div class="row funjob-features-footer">
        <div class="col-xs-6 col-sm-5 col-md-6 col-lg-6 funjob-features-footer--col_1">
            <a href="<?= $this->Url->build(['_name' => 'funjob:info']) ?>" class="btn btn-xs btn-info btn-block">
                <i class="fa fa-arrow-left"></i>
                <span class="hidden-sm">Indietro</span>
            </a>
        </div>

        <div class="col-xs-6 col-sm-7 col-md-6 col-lg-6 funjob-features-footer--col_2">
            <div class="btn-group" style="width:100%">
              <button type="button" class="btn btn-xs btn-block btn-info dropdown-toggle" data-toggle="dropdown">
                <?= __('Altri profili') ?>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:user']) ?>">
                        <i class="fa fa-users text-color-primary"></i>
                        <span class="font-size-sm">
                            <?= __('Utenti') ?>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:company']) ?>">
                        <i class="fa fa-handshake-o text-color-primary"></i>
                        <span class="font-size-sm">
                            <?= __('Aziende') ?>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:sponsor']) ?>">
                        <i class="fa fa-bar-chart text-color-primary"></i>
                        <span class="font-size-sm">
                            <?= __('Sponsor') ?>
                        </span>
                    </a>
                </li>
              </ul>
            </div>
        </div>
    </div>

    <?php // Margine per mobile, stacca la parte laterale (caratteristiche) da foto ?>
    <div class="visible-xs" style="margin-bottom:40px"></div>

    <div class="hidden row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:user']) ?>" class="btn btn-block btn-sm btn-info">
                <?php echo __('Utenti') ?>
            </a>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:company']) ?>" class="btn btn-block btn-sm btn-info">
                <?php echo __('Azienda') ?>
            </a>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:sponsor']) ?>" class="btn btn-block btn-sm btn-info">
                <?php echo __('Sponsor') ?>
            </a>
        </div>
    </div>

<?php $this->end() ?>

<?php // TRADUZIONI GOOGLE (disattivato momentaneamente) ?>
<?php $this->start('translate') ?>
    <div id="google_translate_element"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'it', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        .goog-te-banner-frame {
            bottom:0;
            top:auto;
        }
    </style>
<?php $this->end() ?>


<div class="row" style="padding:10px;">
    <div class="funjob-detail-features col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <?= $this->fetch('funjob-profile-features') ?>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

        <div class="funjob-detail-heading">
            <div class="funjob-detail-wallpaper">

                <picture>
                    <!-- Extra Large Desktops -->
                    <source media="(min-width: 75em)" srcset="<?= $this->fetch('cover') ?>--lg.jpg">

                    <!-- Desktops -->
                    <source media="(min-width: 62em)" srcset="<?= $this->fetch('cover') ?>--md.jpg">

                    <!-- Tablets -->
                    <source media="(min-width: 48em)" srcset="<?= $this->fetch('cover') ?>--sm.jpg">

                    <!-- Landscape Phones -->
                    <source media="(min-width: 34em)" srcset="<?= $this->fetch('cover') ?>--xs.jpg">

                    <!-- Portrait Phones -->
                    <img src="<?= $this->fetch('cover') ?>--xs.jpg" srcset="<?= $this->fetch('cover') ?>.jpg" >
                </picture>

                <style type="text/css">
                    .funjob-detail-wallpaper-image {
                        width:auto;
                    }

                    .funjob-detail-wallpaper-image-wrapper
                    .funjob-detail-wallpaper picture {
                        border: 1px solid red;overflow: auto;display: block;
                    }
                    .funjob-detail-wallpaper picture source,
                    .funjob-detail-wallpaper picture img
                    {
                        max-width:100%;
                        width:100%;
                    }
                </style>

                <h1 class="hidden-xs funjob-detail-wallpaper-title text-uppercase">
                    <?php echo $this->fetch('title_for_layout') ?>
                </h1>
            </div>
        </div>

        <div class="container-fluid no-padding">

            <div class="row">
                <div class="col-md-12">
                    <div style="margin:25px" class="text-center">
                        <?php // echo $this->fetch('translate') ?>
                    </div>
                </div>
            </div>

            <?php echo $this->fetch('content') ?>
        </div>

    </div>
</div>
<!-- READMORE.JS -->
<style type="text/css">
    .funjob-features-descr {
        overflow:hidden !important;
    }

    *[data-readmore-toggle]:before {
        content:"";
        display:block;
        width:100%;
        position:absolute;
        top:-14px;
        height:15px;
        z-index: -1;
        background: -webkit-linear-gradient(
            rgba(245, 245, 245, 0) 0%,
            rgba(245, 245, 245, 1) 100%
        );
    }

    *[data-readmore-toggle] {
        position:relative;
        z-index:1;
        display:block;
        width:100%;
    }
</style>
<script type="text/javascript">
    $(function() {
        $(".funjob-features-descr").readmore({
            collapsedHeight : 75,
            heightMargin    : 5,
            moreLink        : '<a href="#"> <?= __('Continua a leggere ...') ?> <i class="fa fa-arrow-down"></i> </a>',
            lessLink        : '<a href="#"> <?= __('Chiudi') ?>   <i class="fa fa-arrow-up"></i> </a>'
        });
    })
</script>

<?php // JS: MENU FUNZIONALITà PROFILO CHE SEGUE LO SCROLLING ?>
<script type="text/javascript" src="bower_components/sticky-kit/jquery.sticky-kit.min.js"></script>
<script type="text/javascript">
    $(function() {
        var $sticky = $(".funjob-detail-features");
        var stickySetup = { offset_top: 115 };

        $(window).on("load resize", function(evt) {
            if ($.inArray(bootstrap_class(), ["sm", "md", "lg"]) != -1) {
                $sticky.stick_in_parent(stickySetup);
            } else {
                $sticky.trigger("sticky_kit:detach");
            }
        });

    });
</script>
<?php $this->end() ?>
