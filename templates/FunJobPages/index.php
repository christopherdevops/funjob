<?php
    $this->assign('content--class', 'content--fullscreen col-xs-12 col-sm-12 col-md-12 col-lg-12');
    $this->assign('title', __('FunJob: cos\'è?'));
    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Informazioni su FunJob'), ['_name' => 'funjob:info']);

    $this->Html->script([
        '/bower_components/matchHeight/dist/jquery.matchHeight-min.js'
    ], ['block' => 'js_foot']);


    $i18nGlobalTags = [
        'bold_open'  => '<strong>',
        'bold_close' => '</strong>'
    ];
?>

<?php $this->append('css_head--inline') ?>

    .funjob-summary .panel-heading {
        color:white !important;
    }

    .funjob-summary .panel-title {
        font-weight:bold !important;
    }

    .funjob-summary hr {
        border-color:#00adee;
        margin-left:5%;
        margin-right:5%;
    }

    .funjob-summary-icon {
        margin-top:20px;
        font-size:106px !important;
    }


    .funjob-summary-btn {
        color:#00adee !important;
    }
<?php $this->end() ?>

<?php $this->start('translate') ?>
    <div id="google_translate_element"></div>
    <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'it', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
    }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php $this->end() ?>

<div class="well well-sm">
    <div class="row funjob-mainpage-about">

        <div class="col-md-4">

            <iframe
                id="funjob-mainpage-about-video"
                class="visible-xs visible-sm"
                width="100%" height="300"
                src="https://www.youtube.com/embed/UUM29SBcKMY"
                frameborder="0" allowfullscreen>
            </iframe>

            <iframe
                id="funjob-mainpage-about-video"
                class="visible-md visible-lg"
                width="100%" height="200"
                src="https://www.youtube.com/embed/UUM29SBcKMY"
                frameborder="0" allowfullscreen>
            </iframe>

        </div>

        <div class="col-md-5">
            <div id="funjob-mainpage-about-text">

                <p class="text-muted font-size-md text-align-justify">
                    <?=
                        __d('site', 'FUNJOB è un Social Talent Network in grado di dare valore economico all’intelligenza collettiva che redistribuisce agli Utenti oltre il 50% del guadagno generato dal proprio utilizzo.')
                    ?>
                </p>
                <p class="text-muted  font-size-md text-align-justify">
                    <?php
                        echo __d('site', 'Un {bold_open}Gioco{bold_close} che usa la cultura e la meritocrazia per fare emergere il talento, dando alle Aziende la possibilità di contattare i migliori utenti risparmiando tempo nel recruiting e agli Utenti di trovare nuovi amici e guadagnare dalla propria cultura.', $i18nGlobalTags);
                        echo __d('site', 'Uno strumento che al contempo è sia un gioco fruibile anonimamente che il trampolino di lancio per i talenti che potranno decidere di creare il proprio profilo culturale.');
                        echo __d('site', 'In FUNJOB non bastano i Titoli Accademici conta solo la {bold_open}Meritocrazia{bold_close}.', $i18nGlobalTags);
                    ?>
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <a href="<?= $this->Url->build(['_name' => 'quiz-categories:search']) ?>">
                <img class="hidden-xs hidden-sm img-responsive" src="img/funjob-home.png" alt="">
            </a>
        </div>
    </div>

</div>

<!-- SUMMARY -->
<div class="row funjob-summary">

    <div data-mh="funjob-summary-profiles" class="col-xs-12 col-sm-4 col-md-4 col-lg-4 funjob-summary-col">

        <div class="panel panel-sm panel-info panel-info">
            <div class="panel-heading">
                <h4 class="panel-title text-center font-size-lg">
                    <?php echo __d('site', 'UTENTI') ?>
                </h4>
            </div>
            <div class="panel-body">

                <div class="text-center">
                    <i class="funjob-summary-icon text-color-primary fa fa-users"></i>
                </div>
                <hr>

                <p class="text-muted font-size-md text-align-justify">
                    <?= __d('site', 'Hai mai pensato che tutte le tue conoscenze potessero darti un guadagno economico?') ?>
                    <?= __d('site', 'Di metterti in mostra con Aziende e nuovi Amici per il tuo vero talento?') ?>
                    <?= __d('site', 'D’imparare giocando?') ?>
                </p>

                <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:user']) ?>" class="funjob-summary-btn btn btn-default btn-md btn-block">
                    <?php echo __d('site', 'Approfondisci') ?>
                    <i class="fa fa-arrow-right"></i>
                </a>

            </div>
        </div>

    </div>

    <div data-mh="funjob-summary-profiles" class="col-xs-12 col-sm-4 col-md-4 col-lg-4 funjob-summary-col">

        <div class="panel panel-sm panel-info panel-info">
            <div class="panel-heading">
                <h4 class="panel-title text-center font-size-lg">
                    <?php echo __d('site', 'AZIENDE') ?>
                </h4>
            </div>
            <div class="panel-body">

                <div class="text-center">
                    <i class="funjob-summary-icon text-color-primary fa fa-handshake-o"></i>
                </div>
                <hr />

                <p class="text-muted font-size-md text-align-justify">
                    <?= __d('site', 'Quante volte al CV non corrisponde la reale cultura del candidato?') ?>
                    <?= __d('site', 'Quanti dei suoi aspetti non hai avuto il tempo di vagliare?') ?>
                    <?= __d('site', 'Prova i nostri strumenti di ricerca del personale.') ?>
                </p>

                <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:company']) ?>" class="funjob-summary-btn btn btn-default btn-md btn-block">
                    <?php echo __d('site', 'Approfondisci') ?>
                    <i class="fa fa-arrow-right"></i>
                </a>

            </div>
        </div>

    </div>

    <div data-mh="funjob-summary-profiles" class="col-xs-12 col-sm-4 col-md-4 col-lg-4 funjob-summary-col">
        <div class="panel panel-sm panel-info panel-info">

            <div class="panel-heading">
                <h4 class="panel-title text-center font-size-lg">
                    <?php echo __d('site', 'SPONSOR') ?>
                </h4>
            </div>

            <div class="panel-body">

                <div class="text-center">
                    <i class="funjob-summary-icon text-color-primary fa fa-bar-chart text-center"></i>
                </div>
                <hr>

                <p class="text-muted font-size-md text-align-justify">
                    <?= __d('site', 'Gl’internauti ti odiano ma non in FUNJOB che gli dona oltre il 50% di ciò che spendi e che possono decidere di non vedere pubblicità.') ?>
                    <?= __d('site', 'Finanzia il merito, decidi di farti amare!') ?>
                </p>

                <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:sponsor']) ?>" class="funjob-summary-btn btn btn-default btn-md btn-block">
                    <?php echo __d('site', 'Approfondisci') ?>
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        $('.funjob-summary-col .panel p').matchHeight({
            byRow: true
        });
    });
</script>

