<?php
$this->extend('profiles/all');
$this->assign('title_for_layout', __('Utenti'));
$this->assign('cover', '/img/funjob-profiles-background-user'); // senza .ext;


$this->Breadcrumbs->add(__('Informazioni su FunJob'), ['_name' => 'funjob:info']);
$this->Breadcrumbs->add(__('A chi è rivolto?'), ['_name' => 'funjob:profiles']);
$this->Breadcrumbs->add(__('Utenti'), $this->request->getAttribute('here'));
?>

<?php $this->append('css_head--inline') ?>
<?php $this->end() ?>

<?php // FUNZIONALITÀ ?>
<?php $this->append('funjob-profile-features--items') ?>
    <li class="list-group-item">
        <i class="fa fa-check pull-left font-size-md"></i>
        <h4 class="font-size-md"><?= __d('site', 'Profilo personale') ?></h4>
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
        <h4 class="font-size-md"><?= __d('site', 'CV') ?></h4>
    </li>
<?php $this->end() ?>


<div class="funjob-features-grid-row row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fontello-quiz-play"></i>
                <?= __d('site', '“Your Mind, Your Business”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Puoi giocare solo per divertimento e in modo anonimo e se i risultati ti soddisferanno potrai decidere di condividerli con Utenti e Aziende, mettendoti in mostra con entrambi.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Sceglierai il tuo Gioco a Quiz in relazione ai tuoi gusti, al tuo umore o alla tua cultura. Per prepararti ad un concorso, ripassare una materia d’esame, per rivisitare i ricordi dell’ultimo libro che hai letto, per rivisitare le caratteristiche del tuo hobby, per informarti sulle news, sulla moda e quant’altro. E potrai anche tu creare Giochi a Quiz e pubblicizzarli sui Social Network guadagnando dagl’introiti generati da chi li giocherà.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Giocando accumulerai PIX da spendere, scalerai le classifiche, creerai il tuo Profilo in base al tuo talento, sfiderai altri utenti, creerai e parteciperai ai tornei promossi da FunJob, Utenti o Aziende. Troverai nuovi amici, sarai contattato dalle Aziende e tanto altro ancora. Semplicemente: “Giocando”.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-graduation-cap"></i>
                <?= __d('site', '“La Meritocrazia prima di tutto”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">

                <p class="font-size-md">
                    <?= __d('site', 'Il nostro è un meccanismo semplice ma rivoluzionario. Infatti, tutto è cultura! L’ultimo romanzo che hai
                    letto, le materie universitarie che stai studiando, le tue passioni e ogni tipo di conoscenza che possiedi è
                    funzionale per tracciare un profilo reale di te! Per far sapere al mondo il tuo valore!') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Il tuo cervello è una miniera d’informazioni che se tradurrai in “Domande” da inserire in FUNJOB e giocherai ai nostri Quiz,
                    otterrai molti risultati: quello di guadagnare quando gli altri utenti ci giocheranno, quello di guadagnare tu
                    stesso quando giocherai a FUNJOB, trovare Lavoro, nuovi Amici e la possibilità di costruirti un Profilo
                    Culturale personale.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Tutto questo ti qualificherà. Ti renderà unico.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Traccerà i lineamenti del tuo carattere e della tua personalità, cultura e talento che un semplice Curriculum non riuscirebbe mai a fare. In una parola: “Meritocrazia”.') ?>
                </p>

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-users"></i>
                <?= __d('site', '“Amici sì ma di Qualità!”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'FUNJOB utilizza l’intelligenza collettiva al servizio dei rapporti umani. Realizzati i tuoi quiz potrai condividerli
                    con gli amici ed invitarli a svolgerli, guadagnando ogni qualvolta essi vi parteciperanno. Allo stesso modo
                    potrai partecipare ai Quiz creati dai BigBrain di FUNJOB, dagl’altri utenti o dei tuoi stessi amici.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Così ti presenterai al mondo per ciò che sei, per quanto vali a prescindere dai titoli accademici. Le tue passioni, i tuoi hobby, tutto diventerà un elemento in grado di qualificarti agl’occhi del mondo.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'In questo modo potrai trovare nuovi amici in base alle passioni comuni, alle affinità culturali e chissà,
                    magari intraprendere con essi un percorso professionale oltre che personale.') ?>
                </p>
            </div>
        </div>
    </div>

</div>

<div class="funjob-features-grid-row row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-dollar"></i>
                <?= __d('site', '“FUNJOB inventa il concetto di: “Rendita da Conoscenza”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Non è magia, è matematica! Perché di cultura si deve poter quantomeno sopravvivere!
                    FUNJOB è l’unica società al mondo che ridistribuisce ai propri utenti oltre il 50% dei guadagni ricavati dal
                    gettito pubblicitario generato dalla circolazione delle conoscenze degli utenti stessi.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Ogni Gioco a Quiz superato da diritto all’utente di percepire in PIX, cioè la nostra moneta virtuale spendibile
                    nel nostro Store, il <strong>35%</strong> del guadagno generato dalla visione della pubblicità inserita nel quiz stesso e che
                    comunque sarà sempre disinseribile a scelta dell’utente.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Ogni Quiz creato dall’utente farà guadagnare il <strong>15%</strong> degli introiti pubblicitari generati ogni volta che viene
                    giocato e a prescindere che il quiz venga superato o meno dal giocatore.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'A queste opzioni vanno aggiunti i guadagni dai Tornei tematici multi giocatore dai montepremi più alti, i
                    Quiz degli Sponsor con particolari coupon per i vincitori e quelli proposti dalle Aziende in cui il premio sarà
                    un colloquio di lavoro.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Il tutto sempre con l’opzione di disabilitare la pubblicità e di giocare in modo anonimo.
                    Finalmente l’ultimo libro che hai letto, la tua passione per l’arte e quant’altro faccia parte delle tue
                    conoscenze, avrà un valore monetario e per il solo fatto di conoscere qualcosa avrai una “Rendita da
                    Conoscenza”. Non passerai più ore ed ore in Social Network senza guadagnarci nulla!') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Non ti resta che giocare, creare i tuoi Giochi a Quiz, condividerli nei Social Network e sfidare gli amici!') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fontello-credits"></i>
                <?= __d('site', '“PIX: moneta virtuale, guadagno reale!”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', '1 PIX corrisponde ad 1 centesimo di euro e si accumula giocando all’interno di FUNJOB.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Ogni Quiz Giocato (se non decidi di disabilitare i messaggi pubblicitari) genera un profitto che FUNJOB
                    divide con i propri utenti. <br/>
                    In relazione alle sfide tra utenti ed ai tornei, il guadagno in PIX sarà generato dalle quote d’iscrizione di ogni
                    partecipante.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Potrai spendere i tuoi PIX acquistando i prodotti all’interno del nostro STORE, potrai convertirli in Codici
                    Sconto per acquistare ciò che desideri altrove, spenderli per fagare la quota d’iscrizione di alcuni Tornei o
                    donarli ad iniziative benefiche.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Lo scopo primario di FUNJOB è quello di offrire a prezzi molto bassi, tutti quegli alimenti e oggetti facenti
                    parte di ciò che serve per soddisfare i bisogni primari di ogni persona perché uno dei concetti alla base della
                    nostra filosofia è quello che la conoscenza dovrebbe permettere di poter sopravvivere.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-vcard-o"></i>
                <?= __d('site', '“Il Profilo FUNJOB è più affidabile del CV e le Aziende lo sanno”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Attesta le tue conoscenze. FUNJOB è un Social Talent Network che offre ai suoi utilizzatori la possibilità di
                    avere un profilo personale che fotografi le sue conoscenze, le sue passioni, capacità e cultura. La
                    Meritocrazia abita qui rivoluzionando il sistema di recruiting per le Aziende che attraverso FUNJOB possono
                    cercare il loro candidato ideale potendo contare su dati veri, attuali e certificati da speciali servizi come il
                    “MiCam”, cioè l’opzione di salvare l’audio e il video di se stesso mentre si svolge il proprio quiz così da
                    offrire un’assoluta garanzia delle proprie conoscenze alle Aziende che diversamente dovrebbero spendere
                    mesi del proprio tempo per approfondimenti culturali di quel genere!') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Chi ci assicura che un laureato in Giurisprudenza conosca il Diritto della Navigazione o ricordi ancora le
                    materie studiate? O che un laureato in lettere ricordi ancora la parafrasi della Divina Commedia? Chi l’ha
                    detto che una donna matura non conosca le ricette di cucina meglio di molti chef stellati? Gli esempi
                    sarebbero infiniti come infinite sono le conoscenze di ogni essere umano.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'FUNJOB, Quiz dopo Quiz, tassello dopo tassello, traccia i lineamenti della personalità di un potenziale
                    candidato, di un nuovo amico, di un potenziale socio e quant’altro.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Sei pronto ad essere trovato dal lavoro? Bene, allora inizia a divertirti giocando!') ?>
                </p>
            </div>
        </div>
    </div>

</div>

<div class="funjob-features-grid-row row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fa fa-handshake-o"></i>
                <?= __d('site', '“Fatti trovare! In te c’è più Conoscenza e Talento di quanto credi!”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">

                <p class="font-size-md">
                    <?= __d('site', 'Le Aziende e anche gli Sponsor, potranno visionare le Classifiche per Materia dei nostri giocatori, trovare
                    nuovi talenti e inviare loro una “richiesta di contatto”. L’utente, se sarà interessato, potrà permettere
                    all’Azienda di visionare il proprio Curriculum Vitae e mostrare la propria fotografia culturale a 360°.
                    Le Aziende potranno scegliere di non essere visibili per poter scegliere in tranquillità a quali utenti proporre
                    di svolgere i Quiz da esse create nella forma dei Tornei o dei Quiz Individuali o invitarlo direttamente ad un
                    colloquio anche via Skype, invitarlo ad un colloquio di persona o via Skype, proporgli di partecipare a Tornei
                    a numero chiuso redatti solo per esso.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'La garanzia dei Quiz FUNJOB (Certificati dai nostri BigBrain a differenza di quelli degli utenti) e le

                    registrazioni opzionali “MiCam” fatte dall’utente che ha deciso di autocertificarsi completeranno il quadro

                    di garanzie sul vostro talento.

                    In FUNJOB puoi giocare in modo anonimo e senza impegno ma se scegli di mettere in evidenza il tuo

                    talento lo farai seriamente!') ?>
                </p>

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <div class="funjob-features-title font-size-lg" style="">
                <i class="fontello-brain"></i>
                <?= __d('site', '“Se conosci bene un argomento sei già un <span class="text-transform:none !important">BigBrain</span>”') ?>
            </div>
            <hr>
            <div class="funjob-features-descr">
                <p class="font-size-md">
                    <?= __d('site', 'Per diventare un BigBrain dovrai essere un utente registrato e certificato da FUNJOB, dimostrare di
                    conoscere bene la materia o l’argomento che ti sarà assegnato e dovrai redigere i Nostri giochi a Quiz.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Come per tutti gli altri utenti guadagnerai per ciò che produrrai ma a differenza degli altri utenti avrai una
                    corsia preferenziale nei confronti delle Aziende.') ?>
                </p>
                <p class="font-size-md">
                    <?= __d('site', 'Un BigBrain rappresenta una persona che in una determinata materia o argomento è un eccellenza.
                    Per diventarlo inviaci una richiesta attraverso il “Modulo Contatti”.') ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    </div>

</div>
