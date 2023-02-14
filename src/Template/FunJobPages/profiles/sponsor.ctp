<?php
$this->extend('profiles/all');
$this->assign('title_for_layout', __('Sponsor'));
$this->assign('cover', '/img/funjob-profiles-background-sponsor');

$this->Breadcrumbs->add(__('Informazioni su FunJob'), ['_name' => 'funjob:info']);
$this->Breadcrumbs->add(__('A chi è rivolto?'), ['_name' => 'funjob:profiles']);
$this->Breadcrumbs->add(__('Insersionisti'), $this->request->here);
?>


<?php // FUNZIONALITÀ ?>
<?php $this->append('funjob-profile-features--items') ?>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Profilo personale') ?></h4>
    </li>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Giocare giochi') ?></h4>
    </li>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Creare nuovi giochi') ?></h4>
    </li>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Creare banner pubblicitari') ?></h4>
    </li>
<?php $this->end() ?>


<div class="funjob-features-grid-row row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-toggle-on" aria-hidden="true"></i>
                <?= __d('site', '“Mai più pubblicità indesiderata”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Hai mai pensato di essere amato per la pubblicità che finanzi, invece di essere percepito come un disturbo dai tuoi potenziali acquirenti?') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Sappi che oltre il 50% di ciò che versi a FUNJOB per promuovere la tua Azienda sarà ridistribuito agli Utenti che comunque potranno sempre decidere di disinserire il messaggio pubblicitario.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Conosci un\'altra azienda che fa una cosa del genere?') ?>
                    <?= __d('site', 'Così facendo non solo ti promuoverai ma aiuterai anche gli Utenti FUNJOB a realizzarsi nella vita!
                    Unisci questo ai nostri prezzi concorrenziali e avrai la cifra della tua convenienza a far parte di FUNJOB.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <?= __d('site', '“La tua pubblicità sarà Osservata, non guardata con fastidio”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'FUNJOB ti offre innovativi sistemi pubblicitari in grado di farti raggiungere livelli di efficacia fin qui mai raggiunti.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Oltre ai classici banner nelle pagine, ciò che ci differenzia è il sistema: “Maximum Memories”, cioè la pubblicità della tua azienda inserita tra una domanda e l’altra dei nostri Giochi a Quiz.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Perché è efficace? Perché viene visualizzata nel momento di maggiore attenzione dell’Utente, ovvero nel momento in cui risponde alle domande a tempo. Fidati, si ricorderà di te!') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Se pensi di poter essere troppo invasivo non avere timori perché ogni Utente all’inizio del Gioco a Quiz può disinserire i messaggi pubblicitari e se non lo farà guadagnerà ben oltre il 50% di quanto corrisponderai a FUNJOB per l’inserzione.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-users" aria-hidden="true"></i>
                <?= __d('site', '“Non solo banner …”') ?>
            </div>
            <hr>

            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'I nostri Sponsor possono partecipare attivamente alla vita della Community di FUNJOB.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Possono pubblicizzarsi attraverso dei Tornei a premi, magari su tematiche riguardanti la storia della propria azienda o le caratteristiche dei propri prodotti.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Il tutto senza alcun banner pubblicitario perché la pubblicità è già nel testo del Gioco a Quiz che vi riguarda. Un modo divertente e particolarmente incisivo per rimanere nella memoria di chi vi leggerà e per
                    distribuire premi agli utenti.') ?>
                </p>
            </div>
        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-building-o"></i>
                <?= __d('site', '“Non sei solo uno Sponsor. Prima di tutto sei un Azienda”') ?>
            </div>
            <hr>
            <p class="font-size-md">
                <?= __d('site', 'Prima di essere uno Sponsor sei un’Azienda e quindi puoi partecipare anche sotto questo profilo alle
                attività della comunità di FUNJOB.') ?>
            </p>
            <p class="font-size-md">
                <?= __d('site', 'Scegliendoci hai già dimostrato di voler investire sul merito ed a favore di una filosofia di comunità che fa emergere il talento e redistribuisce i capitali percepiti agli utenti ma puoi fare di più, puoi essere un Attore principale entrando a far parte del gruppo di Aziende che abbracciano una selezione innovativa del personale.')
                ?>
            </p>
        </div>

    </div>

</div>
