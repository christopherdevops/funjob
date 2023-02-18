<?php
    $this->assign('title', 'Funjob');
    $this->assign('eyelet', __('Benvenuto in FunJob'));

    $this->assign('header', ' ');
    $this->assign('breadcrumb', ' ');

    $this->Html->css([
        'features/bootstrap-gutters',
        '//fonts.googleapis.com/css?family=Inconsolata:400,700',
        'features/bs3-carousel-slider.css'
    ], ['block' => 'css_head']);
    $this->Html->script([
        '/bower_components/js-cookie/src/js.cookie.js',
        '/bower_components/matchHeight/dist/jquery.matchHeight-min.js'
    ], ['block' => 'js_foot']);
?>

<?php $this->append('css_head--inline') ?>
    .font-family-nova-mono {
        font-family: 'Inconsolata', monospace;
        font-weight:bold;
    }

    .funjob-pseudo-logo  {
        vertical-align: middle;
        text-align: center;
        margin:10px;
    }

    .funjob-mainpage-about-text {text-align:justify;}

    .margin-top--lg {margin-top:50px}
    .margin-top--md {margin-top:40px}
    .margin-top--sm {margin-top:20px}
    .margin-top--xs {margin-top:10px}

    .well-hero {
        border:2px solid #00adee !important;
    }

    .first-letter-primary-color {
        display:inline-block;
        font-family:"Courier New", cursive, sans-serif;
        font-weight:bold;
    }

    .first-letter-primary-color:first-letter {}

    /** Margine superiore btn-block  */
    @media (max-width: 767px) {
        #funjob-community-btns .btn-block
        {
            margin-top:5px;
        }
    }

    .well-help {
        margin-bottom:0 !important;
        border:0 !important;
        background-color:transparent !important;
        color:#00adee !important;
    }


    .funjob-home-block .panel-heading {color:white !important}
    .funjob-home-block {
        margin-bottom:10px;
    }

    .thumbnail .caption {padding:2px;}

    /** HOWOTO **/
    .funjob-howto-block {
        border:1px solid whitesmoke;
        padding:10px;
        border-radius:4px;
        background-color:whitesmoke;
        box-shadow:0 0 2px 0px grey;
        color:white;
    }

    .funjob-howto-block h1{ margin-bottom:14px !important;}
    .funjob-howto-block img {border:1px solid gray;border-radius:2px;}
    .funjob-howto-block hr {margin:5px;}

    @media only screen
    and (min-width : 320px)
    and (max-width : 480px)
    {
        .funjob-howto-block {margin:7px auto;}
    }

    /** TRAILER VIDEO **/
    .funjob-howto-video-btn {
        position:relative;
        top:0;left:0;
        display:block;
        height:100%
    }

    .funjob-howto-video-icon {
        position:absolute;top:25%;left:45%;
        font-size:400%;
        color:white;
        text-shadow:2px 2px 4px black;
    }

    .funjob-howto-video-icon-text {
        position:absolute;
        top:62%;left:auto;

        width:100%;
        margin:0 auto;
        text-align:center;
        display:block !important;

        color:white;text-shadow:2px 2px 3px gray;
    }

    /** SPONSOR: buttons **/
    .row-howto-three-buttons div[class*="col-"] {
        padding-left:5px;
        padding-right:5px;
    }

    .row-howto-three-buttons div[class*="col-"] {
        padding-left:5;
        padding-right:5;
    }
    .row-howto-three-buttons div[class*="col-"]:first-child {
        padding-left:15px;
        padding-right:5px;
    }
    .row-howto-three-buttons div[class*="col-"]:last-child {
        padding-left:5px;
        padding-right:15px;
    }

    .funjob-howto-block--job .btn {background-color: #e9e7e7;}

    /** HOWTO: close **/
    .funjob-howto-close-btn {
        font-size:20px;
        color:gray;

        position: absolute;
        top:-10px;right:1px;

        display:none;
    }

    .well-howto--test1 {background: linear-gradient(141deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);}

    .well-howto,
    .well-howto-toggle-block
    {
        height:40px; /** cambia anche valore line-height in .well-howto-title e .well-howto img **/
        padding:0;
    }
    .well-howto img {
        max-height:34px;
        margin-left:5px;
        margin-right:5px;
    }

    .well-howto-title {
        line-height:40px;
        letter-spacing:3px;
        color:white;
    }

    .well-howto-toggle-block {
        text-align:center;
        padding:2px;
    }

    .well-howto {
        background: rgba(0,175,238,1);
            background: -moz-linear-gradient(left, rgba(0,175,238,1) 0%, rgba(0,175,238,1) 11%, rgba(0,175,238,1) 47%, rgba(196,196,196,1) 70%, rgba(196,196,196,1) 100%);
            background: -webkit-gradient(left top, right top, color-stop(0%, rgba(0,175,238,1)), color-stop(11%, rgba(0,175,238,1)), color-stop(47%, rgba(0,175,238,1)), color-stop(70%, rgba(196,196,196,1)), color-stop(100%, rgba(196,196,196,1)));
            background: -webkit-linear-gradient(left, rgba(0,175,238,1) 0%, rgba(0,175,238,1) 11%, rgba(0,175,238,1) 47%, rgba(196,196,196,1) 70%, rgba(196,196,196,1) 100%);
            background: -o-linear-gradient(left, rgba(0,175,238,1) 0%, rgba(0,175,238,1) 11%, rgba(0,175,238,1) 47%, rgba(196,196,196,1) 70%, rgba(196,196,196,1) 100%);
            background: -ms-linear-gradient(left, rgba(0,175,238,1) 0%, rgba(0,175,238,1) 11%, rgba(0,175,238,1) 47%, rgba(196,196,196,1) 70%, rgba(196,196,196,1) 100%);
            background: linear-gradient(to right, rgba(0,175,238,1) 0%, rgba(0,175,238,1) 11%, rgba(0,175,238,1) 47%, rgba(196,196,196,1) 70%, rgba(196,196,196,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00afee', endColorstr='#c4c4c4', GradientType=1 );
    }

    .panel {background-color:whitesmoke !important;}

    /* Smartphones (portrait and landscape) ----------- */
    @media only screen
    and (min-width : 320px)
    and (max-width : 480px)
    {
        div[id*='funjob-home-block-'] {
            margin-top:7px;
        }
    }
<?php $this->end() ?>

<?php // CATEGORIE POPOLARI ?>
<?php $this->start('widget-categories--popular') ?>
    <?php $this->Html->css('home/widget-popular-categories', ['once' => true, 'block' => 'css_head']) ?>
    <div class="panel panel-sm panel-info">
        <div class="panel-heading">
            <span class="panel-title font-size-md2 text-color-primary">
                <i class="fa fa-thumb-tack font-color-primary"></i>
                <?php echo __('Categorie popolari') ?>
            </span>
        </div>
        <div class="panel-body" id="quiz-categories-popularity">
            <div class="row gutter-10">
                <?php foreach ($categories as $category) : ?>
                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                    <figure class="thumbnail">
                        <a href="<?= $this->Url->build(['_name' => 'quiz:categories:archive-slug', 'id' => $category->id, 'title' => $category->slug]) ?>">
                            <img class="img-responsive" src="<?php echo $category->imageSize($category->coverFallback, '300x150') ?>" alt="">

                            <figcaption class="caption font-size-md2 text-truncate" data-toggle="popover" data-content="<?= $category->name ?>" data-trigger="hover" data-placement="top">
                                <?= $category->name ?>
                            </figcaption>
                        </a>
                    </figure>
                </div>
                <?php endforeach ?>

                <!--
                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                    <a href="#" class="thumbnail" style="min-height:78px !important">
                        <div class="text-center">
                            <i class="text-center fa fa-3x fa-search"></i>
                        </div>
                        <div class="caption">
                            Mostra altri ..
                        </div>
                    </a>
                </div>
                -->

            </div>
        </div>
    </div>

<?php $this->end() ?>

<?php $this->start('widget-quizzes--popular') ?>
    <?= $this->cell('HomeRandomBigBrains::display', [], []) ?>
<?php $this->end() ?>

<?php $this->start('howto--open') ?>
    <span class="font-size-md hidden-xs">
        <?= __('Apri') ?>
    </span>
    <i class="font-size-lg fa fa-angle-double-down" aria-hidden="true"></i>
<?php $this->end() ?>
<?php $this->start('howto--close') ?>
    <span class="font-size-md hidden-xs">
        <?= __('Chiudi') ?>
    </span>
    <i class="font-size-lg fa fa-angle-double-up" aria-hidden="true"></i>
<?php $this->end() ?>


<?php // BLOCCO INFORMATIVO (COMMUNITY + TRAILER) ?>
<div class="funjob-home-block funjob-community">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well well-xs well-howto" style="margin:0 0 5px 0;overflow:hidden;">
                <a id="funjob-howto-toggle-btn" href="#" class="display-block font-size-lg" style="margin:0 auto;">
                    <img style="margin-top:2px" class="pull-left" src="/img/einstein.png" alt="">

                    <span class="well-howto-title funjob-pseudo-logo font-family--alba visible-sm-inline visible-md-inline visible-lg-inline">
                        <?= __('Introduzione a Funjob') ?>
                        <i class="fa fa-film" style="color:white"></i>
                    </span>
                    <span class="well-howto-title funjob-pseudo-logo font-family--alba visible-xs-inline">
                        <?= __('Intro a Funjob') ?>
                        <i class="fa fa-film" style="color:white"></i>
                    </span>

                    <div class="well-howto-toggle-block pull-right">
                        <div id="well-howto-toggle-content">
                            <?php if ($this->request->getCookie('home__hide_howto')) : ?>
                                <?= $this->fetch('howto--open') ?>
                            <?php else: ?>
                                <?= $this->fetch('howto--close') ?>
                            <?php endif ?>
                        </div>
                    </div>
                </a>
                <script>
                    $(function() {
                        $("#funjob-howto-toggle-btn").on("click", function() {
                            var $toggle = $(".funjob-howto-toggle");
                            $toggle.slideToggle("fast", function(evt) {
                                if ($toggle.is(":hidden")) {
                                    Cookies.set('home__hide_howto', true, {expires: 1000, path: "/"});
                                    $("#well-howto-toggle-content").html($("#tpl-howto--open").html());
                                } else {
                                    Cookies.remove('home__hide_howto');
                                    $("#well-howto-toggle-content").html($("#tpl-howto--close").html());
                                }
                            });
                        });
                    });
                </script>
                <script type="text/template" id="tpl-howto--open"><?= $this->fetch('howto--open') ?></script>
                <script type="text/template" id="tpl-howto--close"><?= $this->fetch('howto--close') ?></script>
            </div>
        </div>
    </div>

    <div class="funjob-howto-toggle" style="<?= $this->request->getCookie('home__hide_howto') ? 'display:none' : '' ?>">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
               <div class="funjob-howto-block funjob-howto-block--fun">
                    <h1 class="funjob-pseudo-logo font-family--alba no-margin text-center text-color-primary">
                        Fun

                        <a class="funjob-howto-close-btn" href="#" class="pull-right">
                            <i class="fontello-cancel-circled"></i>
                        </a>
                    </h1>
                    <div class="funjob-howto-video">
                       <a href="#" class="funjob-howto-video-btn funjob-howto-video-btn--fun funjob-trailer-play">

                           <div style="text-align:center;background-color:#00adee;width:100%;height:150px;">
                                <i style="color:white;font-size:25px" class="fontello-brain"></i>
                                <span style="color:white" class="text-bold"><?= __('VIDEO INTRODUTTIVO FUN') ?></span>
                           </div>

                           <i class="funjob-howto-video-icon fa fa-play"></i>
                           <span class="funjob-howto-video-icon-text font-size-lg">
                               <?php echo __('Guarda video') ?>
                           </span>
                       </a>

                        <hr>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                                    <a href="<?= $this->Url->build(['_name' => 'quiz:index']) ?>" class="btn btn-xs btn-info btn-block">
                                        <i class="fontello-quiz-play text-color-whitesmoke"></i>
                                        <?= __('Gioca') ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= $this->Url->build(['_name' => 'auth:register']) ?>" class="btn btn-xs btn-info btn-block">
                                        <i class="fontello-quiz-play text-color-whitesmoke"></i>
                                        <?= __('Gioca') ?>
                                    </a>
                                <?php endif ?>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <a href="<?= $this->Url->build(['_name' => 'funjob:info']) ?>" class="btn  btn-xs btn-info btn-block">
                                    <i class="fa fa-question-circle text-color-whitesmoke"></i>
                                    <?= __('Scopri') ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <script id="funjob-howto-videoplayer--fun" type="text/template">
                        <div class="text-center">
                            <?php if ($this->request->is('mobile')) : ?>
                                <iframe width="100%" height="250" src="https://www.youtube.com/embed/4PgTxvgFJ5s" frameborder="0" allowfullscreen></iframe>
                            <?php elseif ($this->request->is('tablet')) : ?>
                                <iframe width="100%" height="300" src="https://www.youtube.com/embed/4PgTxvgFJ5s" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <iframe width="100%" height="400" src="https://www.youtube.com/embed/4PgTxvgFJ5s" frameborder="0" allowfullscreen></iframe>
                            <?php endif ?>
                        </div>
                    </script>
                    <script type="text/javascript">
                       document.querySelector(".funjob-howto-video-btn--fun").addEventListener("click", function() {
                            bootbox.dialog({
                                modalClass : "funjob-modal",
                                message    : document.querySelector("#funjob-howto-videoplayer--fun").innerHTML
                            });
                       });
                    </script>
               </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="funjob-howto-block funjob-howto-block--job">
                    <h1 class="funjob-pseudo-logo font-family--alba no-margin text-center" style="color:gray;">
                     Job

                        <a class="funjob-howto-close-btn" href="#" class="pull-right">
                            <i class="fontello-cancel-circled"></i>
                        </a>
                    </h1>
                    <div class="funjob-howto-video">
                        <a href="#" class="funjob-howto-video-btn funjob-howto-video-btn--job funjob-trailer-play">

                           <div style="text-align:center;background-color:#00adee;width:100%;height:150px;">
                                <i style="color:white;font-size:25px" class="fontello-brain"></i>
                                <span style="color:white" class="text-bold"><?= __('VIDEO INTRODUTTIVO JOB') ?></span>
                           </div>

                            <i class="funjob-howto-video-icon fa fa-play"></i>
                            <span class="funjob-howto-video-icon-text font-size-lg">
                                <?php echo __('Guarda video') ?>
                            </span>
                        </a>

                         <hr>
                         <div class="row">
                             <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                 <a href="<?= $this->Url->build(['_name' => 'auth:register']) ?>" class="btn btn-xs btn-default btn-block">
                                     <i class="fa fa-user-plus text-color-primary"></i>
                                     <?= __('Registrati') ?>
                                 </a>
                             </div>
                             <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                 <a href="<?= $this->Url->build(['_name' => 'funjob:info']) ?>" class="btn  btn-xs btn-default btn-block">
                                     <i class="fa fa-question-circle text-color-primary"></i>
                                     <?= __('Scopri') ?>
                                 </a>
                             </div>
                         </div>
                     </div>

                     <script id="funjob-howto-videoplayer--job" type="text/template">
                        <div class="text-center">
                            <?php if ($this->request->is('mobile')) : ?>
                                <iframe width="300" height="250" src="https://www.youtube.com/embed/lrtDSj2AjqI" frameborder="0" allowfullscreen></iframe>
                            <?php elseif ($this->request->is('tablet')) : ?>
                                <iframe width="100%" height="300" src="https://www.youtube.com/embed/lrtDSj2AjqI" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <iframe width="500" height="400" src="https://www.youtube.com/embed/lrtDSj2AjqI" frameborder="0" allowfullscreen></iframe>
                            <?php endif ?>
                        </div>
                     </script>
                     <script type="text/javascript">
                        document.querySelector(".funjob-howto-video-btn--job").addEventListener("click", function() {
                            bootbox.dialog({
                                modalClass : "funjob-modal",
                                message    : document.querySelector("#funjob-howto-videoplayer--job").innerHTML
                            });
                        });
                     </script>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <div class="funjob-howto-block funjob-howto-block--partner">
                    <h1 class="funjob-pseudo-logo font-family-nova-mono no-margin text-center" style="color:#aeaeae;">
                        Partner

                        <a class="funjob-howto-close-btn" href="#" class="pull-right">
                            <i class="fontello-cancel-circled"></i>
                        </a>
                    </h1>
                    <div class="funjob-howto-video">
                        <a href="#" class="funjob-howto-video-btn funjob-howto-video-btn--sponsor funjob-trailer-play">

                            <div style="text-align:center;background-color:#00adee;width:100%;height:150px;">
                                 <i style="color:white;font-size:25px" class="fontello-brain"></i>
                                 <span style="color:white" class="text-bold"><?= __('VIDEO INTRODUTTIVO PARTNER') ?></span>
                            </div>

                            <i class="funjob-howto-video-icon fa fa-play"></i>
                            <span class="funjob-howto-video-icon-text font-size-lg">
                                <?php echo __('Guarda video') ?>
                            </span>
                        </a>

                         <hr>
                         <div class="row row-howto-three-buttons">
                             <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                 <a href="<?= $this->Url->build(['_name' => 'auth:register']) ?>" class="btn btn-xs btn-default btn-block">
                                     <i class="fa fa-user-plus text-color-primary"></i>
                                     <?= __('Registrati') ?>
                                 </a>
                             </div>
                             <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                 <a href="<?= $this->Url->build(['_name' => 'funjob:info']) ?>" class="btn  btn-xs btn-default btn-block">
                                     <i class="fa fa-question-circle text-color-primary"></i>
                                     <?= __('Scopri') ?>
                                 </a>
                             </div>
                             <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                 <a href="<?= $this->Url->build(['controller' => 'contacts']) ?>" class="btn btn-xs btn-default btn-block">
                                     <i class="fa fa-envelope text-color-primary"></i>
                                     <?= __('Contatti') ?>
                                 </a>
                             </div>
                         </div>
                     </div>

                     <script id="funjob-howto-videoplayer--sponsor" type="text/template">
                        <div class="text-center">
                            <?php if ($this->request->is('mobile')) : ?>
                                <iframe width="300" height="250" src="https://www.youtube.com/embed/ZKVcAEvfgMw" frameborder="0" allowfullscreen></iframe>
                            <?php elseif ($this->request->is('tablet')) : ?>
                                <iframe width="100%" height="300" src="https://www.youtube.com/embed/ZKVcAEvfgMw" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <iframe width="500" height="400" src="https://www.youtube.com/embed/ZKVcAEvfgMw" frameborder="0" allowfullscreen></iframe>
                            <?php endif ?>
                        </div>
                     </script>
                     <script type="text/javascript">
                        document.querySelector(".funjob-howto-video-btn--sponsor").addEventListener("click", function() {
                            bootbox.dialog({
                                modalClass : "funjob-modal",
                                message    : document.querySelector("#funjob-howto-videoplayer--sponsor").innerHTML
                            });
                        });
                     </script>
                </div>
            </div>

        </div>
    </div>

</div>

<?php // ULTIMI QUIZ INSERITI ?>
<div class="row funjob-home-block row-match-height gutter-10 funjob-home-block">

    <div data-mh="block-1" class="hidden-xs hidden-sm col-md-3">

        <div class="panel panel-sm panel-info">
            <div class="panel-heading">
                <div class="panel-title font-size-md2">
                    “The Social Talent Network”
                </div>
            </div>
            <div class="panel-body">
                <img style="margin:0 auto;max-height:224px" class="img-responsive" src="/img/einstein_prof.png" alt="">
                <br>
                <p class="no-margin text-center font-size-md text-muted">
                    <?php echo __('@einstein Tu devi essere il nuovo assistente ...', ['nl' => '<br/>']) ?>
                </p>
                <p class="no-margin text-center font-size-md text-muted">
                    <strong>Vuoi guadagnare e trovare lavoro giocando con FunJob?</strong>
                    <br>
                    <br>
                    <style type="text/css">
                        .btn-circle {
                            border:1px solid white;

                            display:inline-block;
                            width:35px;height:35px;
                            border-radius:50%;

                            background-color:#00adee;
                                color:white;

                            text-align:center;
                            font-size:1em;
                        }
                    </style>
                    <div class="text-center">
                        <button style="border:0 !important" class="btn-circle" onclick="bootbox.dialog({message: $('#funjob-modal--yes').html()});return false;">
                            <?= __('Si') ?>
                        </button>
                        <button style="border:0 !important" class="btn-circle" onclick="bootbox.dialog({message: $('#funjob-modal--no').html()});return false;">
                            <?= __('No') ?>
                        </button>
                    </div>
                </p>
            </div>
        </div>

        <script type="text/template" id="funjob-modal--yes">
            <div class="text-center">
                <i style="font-size:32px" class="text-color-primary fontello-brain"></i>
            </div>
            <div class="">
                <p class="font-size-md2">
                <?= __('Gioca e crea i tuoi quiz. Dimostra le tue capacità e guadagna fino al 50% dei profitti generati dalla pubb
                licità.') ?>
                <?= __('Metti a frutto le tue conoscenze, condividi i risultati dei giochi e fatti trovare dalle aziende in cerca di personale di talento.') ?></p>
            </div>
        </script>
        <script type="text/template" id="funjob-modal--no">
            <div class="text-center">
                <i style="font-size:32px" class="text-color-primary fontello-brain"></i>
            </div>
            <div class="text-center">
                <p class="font-size-md2">
                    <?= __('Puoi giocare con Funjob senza condividere i tuoi risultati e senza vedere pubblicità.') ?>
                </p>
            </div>
        </script>
    </div>

    <div data-mh="block-1" class="col-xs-12 col-md-9">
        <div class="panel panel-sm panel-info">
            <div class="panel-heading">
                <span class="panel-title font-size-md2">
                    <i class="fontello-quiz-play"></i>
                    <?php echo __('Giochi consigliati') ?>
                </span>
            </div>
            <div class="panel-body">
                <?= $this->cell('HomePopularQuizzes::display', [], []) ?>

                <div class="row gutter-10">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="<?= $this->Url->build(['_name' => 'quiz:index']) ?>" class="btn btn-primary btn-block btn-xs">
                            <i class="fa fa-search"></i>
                            <?= __('Cerca gioco') ?>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="<?= $this->Url->build(['_name' => 'quiz:create']) ?>" class="btn btn-primary btn-block btn-xs">
                            <i class="fa fa-edit"></i>
                            <?= __('Nuovo gioco') ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="row funjob-home-block row-match-height gutter-10">

    <div data-mh="block-2" class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <?php echo $this->fetch('widget-categories--popular') ?>
    </div>

    <div data-mh="block-2" id="funjob-home-block-bigbrains" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <?= $this->cell('HomeRandomBigBrains::display', [], []) ?>
    </div>
</div>


<?php // UTENTI: RANDOM BIGBRAINS + ULTIMI UTENTI REGISTRATI ?>
<div class="row row-match-height gutter-10 funjob-home-block">

    <div data-mh="block-3" id="funjob-home-block-groups" class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <?= $this->cell('HomeLatestGroups::display', [], ['cache' => ['name' => 'xshort']]) ?>
    </div>

    <div data-mh="block-3" id="funjob-home-block-bigbrains" class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <?php echo $this->cell('HomeVideosForeground::display', [], []) ?>
    </div>
</div>

<div class="row row-match-height gutter-10 funjob-home-block">
    <div data-mh="block-4" id="funjob-home-block-users" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="panel panel-sm panel-info">
            <div class="panel-heading">
                <div class="panel-title font-size-md2">
                    <i class="fa fa-user-plus"></i>
                    <?= __('Ultimi utenti registrati') ?>
                </div>
            </div>
            <div class="panel-body">
                <?php echo $this->cell('HomeUserLatests::display', [], []) ?>
            </div>
        </div>

    </div>
    <div data-mh="block-4" id="funjob-home-block-companies" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="panel panel-sm panel-default">
            <div style="background-color:gray" class="panel-heading">
                <div class="panel-title font-size-md2">
                    <i class="fa fa-handshake-o"></i>
                    <?= __('Ultime aziende registrate') ?>
                </div>
            </div>
            <div class="panel-body">
                <?php echo $this->cell('HomeCompaniesLatests::display', [], []) ?>
            </div>
        </div>
    </div>
</div>


<div class="row row-match-height gutter-10 funjob-home-block">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo $this->cell('StoreCompaniesLogos::display', [], []) ?>
    </div>
</div>

<div id="home-store-products" class="row gutter-10 funjob-home-block">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo $this->cell('StoreProductSlideshow', [], []) ?>
    </div>
</div>



<?php $this->append('js_foot') ?>
<script>
    $(function() {
        $("[data-toggle=popover]").popover({
            container: "body"
        });

        $(".row-match-height .panel").css({"margin-bottom": 0});
        $(".row-match-height .panel").matchHeight({
            byRow    : true,
            property : "height"
        });
    });
</script>
<?php $this->end() ?>


<?php //if (!$this->request->getCookie('show_welcome_modal')) : ?>
<?php //echo $this->element('welcome-modal') ?>
<?php //endif ?>
