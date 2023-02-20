<?php
$this->extend('profiles/all');
$this->assign('title_for_layout', __('Aziende'));
$this->assign('cover', '/img/funjob-profiles-background-company');

$this->Breadcrumbs->add(__('Informazioni su FunJob'), ['_name' => 'funjob:info']);
$this->Breadcrumbs->add(__('A chi è rivolto?'), ['_name' => 'funjob:profiles']);
$this->Breadcrumbs->add(__('Aziende'), $this->request->getAttribute('here'));
?>


<?php // FUNZIONALITÀ ?>
<?php $this->append('funjob-profile-features--items') ?>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Profilo aziendale') ?></h4>
    </li>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Giocare quiz') ?></h4>
    </li>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Creare nuovi quiz') ?></h4>
    </li>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Ricerca figure professionali') ?></h4>
    </li>
<?php $this->end() ?>


<div class="funjob-features-grid-row row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-handshake-o"></i>
                <?= __d('site', '“Risparmia sul recruiting e circondati di eccellenze”') ?>
            </div>
            <hr>

            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Per le Aziende i nostri servizi sono gratuiti e ogni Azienda potrà decidere se essere visibile o di non comparire nei risultati delle ricerche che faranno gli Utenti per proporsi.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Gli strumenti che ti offriamo sono molteplici. Potrai esaminare il profilo del tuo candidato ideale (sempre che sia stato reso pubblico) conoscendo i tanti aspetti della sua formazione culturale.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Potrai proporre Tornei ai quali si potranno iscrivere gratuitamente gli Utenti, chiederci assistenza per stilare Giochi a Quiz per selezionare solo determinati profili da te ricercati.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Attraverso la messaggistica privata potrai invitare gli utenti a fare un colloquio online o di persona, dopo aver chiesto l’autorizzazione a visionare il loro Curriculum Vitae.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Le classifiche per materia e la ricerca per residenza, titolo di studi etc. etc. etc. dei potenziali candidati ti aiuteranno nelle tue ricerche.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'In breve: recruiting mirato e accesso alla migliori menti! Tutto gratuitamente!') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-thumbs-o-up"></i>
                <?= __d('site', '“Utilizza FUNJOB, ne gioverà la tua reputazione!”') ?>
            </div>
            <hr>

            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Le Aziende che utilizzeranno FUNJOB lanciano un chiaro segnale alla società sul proprio approccio meritocratico verso le assunzioni o più semplicemente ai colloqui.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Indirettamente, l’utilizzo dei nostro sistema, ti gioverà in termini d’Immagine.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'In FUNJOB non esistono raccomandazioni, qui vige una sola regola: “La meritocrazia” e un azienda che l’abbraccia non può che essere amata dai potenziali lavoratori ma anche dai potenziali clienti.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-search-plus"></i>
                <?= __d('site', '“Conoscere bene i propri collaboratori per esaltarne il talento”') ?>
            </div>
            <hr>

            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Cosa dice un Curriculum Vitae di qualcuno? Poco o nulla. Solo “Parole”.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'L’attività d’impresa, qualunque essa sia, richiede fatti, conoscenze concrete e ancora vive e attuali.') ?>
                    <?= __d('site', 'FUNJOB sa bene che leggere un titolo accademico in un CV non da nessuna garanzia su ciò che realmente si ricorda di quelle materie ed i tempi stretti del recruiting non permettono di approfondire più di tanto.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Al contempo, una persona non è solo ciò che ha studiato: la vita, le esperienze, la pratica sul campo, le passioni, tutto questo è inesprimibile attraverso un classico Curriculum Vitae ma sono tutti elementi capaci di fare la differenza tra un buon collaboratore ed un collaboratore eccellente.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Come un’anziana nonna è spesso più brava a cucinare di uno chef stellato dal curriculum altisonante, così il mondo è pieno di talenti dalla cultura coltivata privatamente che FUNJOB fa emergere attraverso i propri Giochi a Quiz.')
                    ?>
                </p>
            </div>
        </div>
    </div>

</div>
