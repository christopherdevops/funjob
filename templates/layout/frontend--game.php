<?php
    $this->element('blocks-frontend');
    $this->Html->css([
        'design2.css',
        'design-game.css',

        'app-menu-primary.css',
        'app-menu-mmenu.css',

        '/bower_components/bootstrap/dist/css/bootstrap.min.css',
        '/bower_components/animate.css/animate.min.css',
        '/bower_components/font-awesome/css/font-awesome.min.css',
        '/fontello-game/css/fontello.css',

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
        //'/bower_components/alertify/alertify.min.js',

        '/bower_components/visibilityjs/lib/visibility.core.js',
        '/bower_components/visibilityjs/lib/visibility.timers.js',

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
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
    </head>
    <body>
        <div id="app-page" class="container">
            <div class="row gutter-10 no-margin">

                <?php // Contenuti ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 app-content no-padding">
                    <div class="row">
                        <nav class="app-nav-primary navbar navbar-default navbar-fixed-top Fixed">
                            <?php // PRIMARY MENU ?>
                            <div class="app-menu-primary container no-padding">
                                <div class="row no-margin">

                                    <div class="col-xs-4 col-sm-5 col-md-5 no-padding">

                                        <a href="<?= $this->Url->build(['_name' => 'quiz:view', 'id' => $quiz->id, 'title' => $quiz->slug]) ?>" id="app-menu-primary" class="app-menu-primary-btn app-menu-primary-btn--back text-center flex-align-center flex-align-center--inline">
                                            <i class="fa fa-arrow-left" class="display-inline-block"></i>
                                            <span class="back-text"><?= __('Indietro') ?></span>
                                        </a>
                                    </div>

                                    <div class="col-xs-4 col-sm-2 col-md-2">
                                        <a style="position:relative;" href="<?= $this->Url->build(['_name' => 'home']) ?>" class="app-menu-primary-logo-btn">
                                            <img src="logo.png" class="app-menu-primary-logo animated pulse" alt="">
                                        </a>
                                    </div>

                                    <div class="col-xs-4 col-sm-5 col-md-5 no-padding">
                                        <div class="row row-height-100">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height:100%;">
                                                <div class="container-fluid pull-right">

                                                    <style type="text/css">
                                                        .right-list-icons {
                                                            line-height:50px; /* Stessa dimensione header */
                                                        }
                                                        .right-list-icons li {vertical-align:middle;}
                                                    </style>
                                                    <ul class="list-inline right-list-icons">
                                                        <li>
                                                            <a href="" class="display-inline-block js-sound-toggle">
                                                                <span class="fa-stack fa-lg">
                                                                  <i class="fa fa-volume-up fa-stack-1x"></i>
                                                                  <i class="<?= $this->request->cookie('sounds') == 'false' ? '' : 'hidden' ?> fa fa-ban fa-stack-2x text-danger"></i>
                                                                </span>
                                                            </a>
                                                            <script type="text/javascript">
                                                                $(function() {
                                                                    $(".js-sound-toggle").on("click", function(evt) {
                                                                        evt.preventDefault();

                                                                        var flag      = Cookies.get("sounds") || "true";
                                                                        var toBool    = $.parseJSON(flag);
                                                                        var toBoolSwp = (!toBool).toString();

                                                                        var icon  = $(".js-sound-toggle .fa-ban");
                                                                        var timer = document.querySelector("#sound-timer");

                                                                        if (toBoolSwp === "true") {
                                                                            icon.addClass("hidden");
                                                                            timer.play();
                                                                        } else {
                                                                            icon.removeClass("hidden");
                                                                            timer.pause();
                                                                        }

                                                                        Cookies.set("sounds", toBoolSwp, {expires: 9999999});
                                                                        return false;
                                                                    });
                                                                });
                                                            </script>
                                                        </li>
                                                        <li>
                                                            <a style="display:table-cell" href="<?php echo $this->Url->build(['_name' => 'me:dashboard']) ?>">
                                                                <?php
                                                                    echo $this->User->avatar($UserAuth->avatarSrcMobile, ['class' => 'img-circle visible-xs visible-sm']);
                                                                    echo $this->User->avatar($UserAuth->avatarSrcDesktop, ['class' => 'img-circle visible-md visible-lg']);
                                                                ?>
                                                            </a>
                                                        </li>
                                                    </ul>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <section style="margin-top:80px;" class="row gutter-10">
                        <div id="app-content-section" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php
                                echo $this->Flash->render();
                                echo $this->Flash->render('auth');
                                echo $this->fetch('content');
                            ?>
                        </div>
                    </section>

                </div>

            </div>
        </div>

        <script defer="defer" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
