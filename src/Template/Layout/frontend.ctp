<?php
    $this->element('blocks-frontend');
    $this->Html->css([
        'design2.css',
        'features/bootstrap-gutters',
        'app-menu-primary.css',
        'app-menu-mmenu.css',

        '/bower_components/bootstrap/dist/css/bootstrap.min.css',
        '/bower_components/animate.css/animate.min.css',
        '/bower_components/font-awesome/css/font-awesome.min.css',

        '/bower_components/alertify/themes/alertify.core.css',
        '/bower_components/alertify/themes/alertify.bootstrap.css',

        'responsive-smartphone-xs.css',
        'responsive-desktop-md.css',
        'responsive-desktop-lg.css',

    ], ['block' => 'css_head']);

    $this->Html->script([
        '/bower_components/jquery/dist/jquery.min.js',
    ], ['block' => 'js_head']);
    $this->Html->script([
        '/bower_components/sticky-kit/jquery.sticky-kit.min.js',
        '/bower_components/bootbox.js/bootbox.js',
        '/bower_components/blockadblock/blockadblock.js',
        '/bower_components/js-cookie/src/js.cookie.js',
        '/bower_components/alertify/alertify.min.js',
        '/bower_components/holderjs/holder.min.js',

        'app.js',
        'utilities.js',
        'help.js'
    ], ['block' => 'js_foot']);
?>
<!doctype html>
<html>
    <head>
        <title><?= $this->fetch('title') ?> | FunJob</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0">
        <base href="/">

        <?php
            echo $this->fetch('meta');
            //echo $this->Html->css(['reset.css']);
        ?>

        <?php echo $this->fetch('css_head') ?>
        <?php echo $this->fetch('js_head') ?>

        <style type="text/css">
            <?=
                preg_replace(
                    ['/<!--(.*)-->/Uis', '/\/\*\*?(.*)\*?\*\//Uis', "/[[:blank:]]+/"],
                    ['', '', ' '],
                    str_replace(["\n","\r","\t"], '', $this->fetch('css_head--inline'))
                )
            ?>
        </style>

        <link rel="icon" type="image/x-icon" sizes="16x16" href="/favicon-16x16.png" />
        <link rel="icon" type="image/x-icon" sizes="32x32" href="/favicon-32x32.png" />
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />
    </head>
    <body>
        <div id="app-page" class="container">
            <!-- Spacers navbar -->
            <div class="app-page-spacer visible-md visible-lg" style="padding-top:118px"></div>
            <div class="app-page-spacer visible-sm visible-xs" style="padding-top:118px"></div>

            <div class="row no-margin">

                <?php // Contenuti ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 app-content no-padding">
                    <div class="row">
                        <?php echo $this->element('ui/primary-menu') ?>
                    </div>

                    <section class="row">
                        <div id="app-content-section" class="<?= $this->fetch('content--class') ? $this->fetch('content--class') : 'col-md-10' ?>">
                            <?php
                                echo $this->fetch('breadcrumb');
                                echo $this->fetch('header');

                                echo $this->Flash->render();
                                echo $this->Flash->render('auth');
                                echo $this->fetch('content');
                            ?>
                        </div>

                        <?php
                            // Sidebar laterale
                            if ($this->fetch('sidebar')) {
                                echo $this->fetch('sidebar');
                            }
                        ?>

                    </section>

                </div>

            </div>
        </div>

        <div class="container">
            <hr class="margin--sm">
        </div>

        <footer id="app-footer" class="container">
            <div class="row">
                <div id="app-footer-info" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <p class="font-size-sm text-color-primary">
                        <i class="font-size-md2 fontello-brain"></i>
                        FunJob.it &copy; <?php echo date('Y') ?>
                        <small class="text-muted font-size-xs">
                            “<?= __('The Social Talent Network') ?>”
                        </small>
                    </p>
                    <p class="font-size-sm">
                        <?=
                            __(
                                'FunJob è un marchio e un progetto registrato alla {siae_start}SIAE{siae_end} e {uibm_start}UIBM{uibm_end}', [
                                    'siae_start' => '<a target="_blank" href="https://www.siae.it">',
                                    'siae_end'   => '</a>',

                                    'uibm_start' => '<a target="_blank" href="https://uibm.mise.gov.it">',
                                    'uibm_end'   => '</a>'
                                ]
                            ) ?>
                    </p>
                </div>
                <div id="app-footer-links" class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <div class="pull-right">
                        <a class="display-inline" href="https://www.facebook.com/FunJob-126487058073869" target="_blank" title="Facebook">
                            <i class="fa fa-2x fa-facebook-square"></i>
                        </a>
                        <a class="display-inline" href="https://www.instagram.com/funjob.social" target="_blank" title="Instagram">
                            <i class="fa fa-2x fa-instagram"></i>
                        </a>
                        <a class="display-inline" href="https://www.youtube.com/channel/UCmIK8nn3zYZYtAQ4bkroXbg" target="_blank" title="Youtube">
                            <i class="fa fa-2x fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p class="text-center font-size-xs">
                        <?=
                            __('Made in Italy with {icon}', ['icon' => '<i style="color:red" class="fa fa-heart"></i>'])
                        ?>
                    </p>
                </div>
            </div>
        </footer>

        <?php echo $this->cell('CookiePolicy', [], []) ?>
        <?php echo $this->element('ui/main-menu') ?>


        <link rel="stylesheet" href="fontello/css/fontello.css">

        <script defer="defer" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script defer="defer" src="bower_components/jquery-unveil/jquery.unveil.min.js"></script>

        <script defer="defer" src="bower_components/jQuery.mmenu/dist/js/jquery.mmenu.all.min.js"></script>
        <link rel="stylesheet" href="bower_components/jQuery.mmenu/dist/css/jquery.mmenu.all.css">

        <!--
        <script src="webroot/bower_components/alertify/alertify.min.js"></script>
        <link href="webroot/bower_components/alertify/themes/alertfify.bootstrap.css">
        -->

        <?php echo $this->fetch('js_foot') ?>
        <?php echo $this->fetch('css_foot') ?>

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-22824867-8', 'auto');
          ga('send', 'pageview');
        </script>
  </body>
</html>
