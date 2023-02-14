<?php
$config = [];

// Lingue supportate
$config['app']['languages'] = [
    'it' => __('Italiano'),
    'en' => 'English',
    'ru' => 'Russian',
    'fr' => 'French',
    'es' => 'Espanol'
];

// Lingua standard
$config['app']['defaultLanguage'] = 'it';
$config['app']['quiz']['randomizeAnswers'] = env('APP_QUIZ_RANDOMIZE_QUESTION', true);

$config['app']['quiz']['default'] = [
    // FUTURE: Punteggio minimo per superare quiz (+1 punto a domanda)
    'minScoreRequired' => 8,

    // Domande minime per i quiz di tipo default
    'minQuestions' => 13
];

$config['app']['quiz']['funjob'] = [
    'minQuestions' => 13, // Per livello

    // FUTURE: Punteggio minimo per superare quiz (+1 punto a domanda)
    'minScoreRequired' => 8,

    // Livello difficioltà domande in base a livello
    'answerDifficultyLevel' => [
        1 => [1,2,3],   // facile
        2 => [4,5,6,7], // medio
        3 => [8,9,10],  // difficile

    ]
];

// FUTURE
$config['app']['quiz']['funjob_extended'] = [
    'minQuestions' => 30,

    // Punteggio minimo per superare quiz (+1 punto a domanda)
    'minScoreRequired' => 8,

    // Livello difficioltà domande in base a livello
    'answerDifficultyLevel' => [
        // Facile
        1 => [1,2,3],
        2 => [1,2,3],
        3 => [1,2,3],
        // Medio
        4 => [4,5,6,7],
        5 => [4,5,6,7],
        6 => [4,5,6,7],
        // Difficile
        7 => [8,9,10],
        8 => [8,9,10],
        9 => [8,9,10],
    ]
];

// Domande corrette necessarie per passare il quiz
$config['app']['quiz']['minScoreRequired'] = 8;

// Tempo disponibile per rispondere ad una domanda
$config['app']['quizAnswer']['timeout'] = 27;
// Secondi di tolleranza da aggiungere a timeout (l'utente potrebbe rispondere a 1secondo dalla fine del timer, ma la connessione) potrebbe
// essere lenta per inviare la risposta
$config['app']['quizAnswer']['timeoutTolerance'] = 3;

// Tempo in cui si deve visualizzare la pubblicità
$config['app']['quizAnswer']['advCountdown'] = 0;

// Tipologia domande quiz disponibili
$config['app']['quizQuestion']['types'] = ['default', 'true_or_false'];

// Domande per quiz
$config['app']['quizQuestion']['count'] = 10;

// Caratteri per domanda
$config['app']['quizQuestion']['minChars'] = 10;
$config['app']['quizQuestion']['maxChars'] = 100;

// Configurazione QuizQuestionDefault
$config['app']['quizQuestion']['default'] = [
    'answersCount' => 4
];

// Configurazione QuizQuestionTrueOrFalse
$config['app']['quizQuestion']['true_or_false'] = [
    'answersCount' => 2
];

// Utilizzato per bottone paypal (pagamento)
$config['payment']['paypal']['merchant'] = env('PAYMENT_PAYPAL_ADDRESS', 'colagiu@gmail.com'); // merchant id or email
$config['payment']['bank']['iban'] = env('PAYMENT_BANK_IBAN', 'IT782374872348239023094333333');

/**
 * USER GROUPS
 */
$config['usergroup']['scopes'] = [
    'other' => [
         'group' => __('Gruppo di utenti'),
         'text'  => __('Generico'),
         'value' => 'other',
         'tags'  => [
         ]
    ],

    'universitary' => [
        'group' => __x('Contesto gruppo utente', 'Gruppo Scolastico'),
        'text'  => __('Universitario'),
        'value' => 'universitary',
        'tags'  => [
            __x('tag', 'scuola'),
            __x('tag', 'università')
        ]
    ],
    'high school'  => [
        'group' => __x('Contesto gruppo utente', 'Gruppo Scolastico'),
        'text'  => __('Liceale'),
        'value' => 'high school',
        'tags'  => [
            __x('tag', 'scuola'),
            __x('tag', 'liceo')
        ]
    ],
];


/**
 * User gratients
 * Cover per profili utenti
 *
 * key   = classe css (filename: gradients/)
 * value = nome
 */

// Gradienti (colori)
$config['funjob']['userProfile']['cover']['gradient'] = [
    ['text' => __x('Nome colore profilo', 'Predefinito: FunJob'), 'value' => 'gradients/simple/funjob'],
    ['text' => __x('Nome colore profilo', 'Bloody mary'), 'value' => 'gradients/simple/bloody-mary'],
    ['text' => __x('Nome colore profilo', 'Mojito'), 'value' => 'gradients/simple/mojito'],
    ['text' => __x('Nome colore profilo', 'Pesca'), 'value' => 'gradients/simple/peach'],
    ['text' => __x('Nome colore profilo', 'Ciliegia'), 'value' => 'gradients/simple/cherry'],
    ['text' => __x('Nome colore profilo', 'Sogni pacifici'), 'value' => 'gradients/simple/pacific-dream'],
    ['text' => __x('Nome colore profilo', 'Laguna blu'), 'value' => 'gradients/simple/blue-lagoon'],
    ['text' => __x('Nome colore profilo', 'Brezza di mare'), 'value' => 'gradients/simple/sea-weed'],
    ['text' => __x('Nome colore profilo', 'Idrogeno'), 'value' => 'gradients/simple/hydrogen'],
    ['text' => __x('Nome colore profilo', 'Blue sky'), 'value' => 'gradients/simple/blue-sky'],

    ['text' => __x('Nome colore profilo', 'Dull'), 'value' => 'gradients/simple/dull'],
    ['text' => __x('Nome colore profilo', 'Relay'), 'value' => 'gradients/simple/relay'],
    ['text' => __x('Nome colore profilo', 'Orange Coral'), 'value' => 'gradients/simple/orange-coral'],
    ['text' => __x('Nome colore profilo', 'Purpink'), 'value' => 'gradients/simple/purpink'],
    ['text' => __x('Nome colore profilo', 'Vice City'), 'value' => 'gradients/simple/vice-city'],
    ['text' => __x('Nome colore profilo', 'Black rosè'), 'value' => 'gradients/simple/black-rose'],
    ['text' => __x('Nome colore profilo', '50 shades of Gray'), 'value' => 'gradients/simple/50-shades-of-grey'],
    ['text' => __x('Nome colore profilo', 'Passion'), 'value' => 'gradients/simple/passion'],
    ['text' => __x('Nome colore profilo', 'Piglet'), 'value' => 'gradients/simple/piglet'],
    ['text' => __x('Nome colore profilo', 'Vine'), 'value' => 'gradients/simple/vine'],
    ['text' => __x('Nome colore profilo', 'Facebook messenger'), 'value' => 'gradients/simple/facebook-messenger'],
    ['text' => __x('Nome colore profilo', 'Elettric Violet'), 'value' => 'gradients/simple/elettric-violet'],
    ['text' => __x('Nome colore profilo', 'Midnight City'), 'value' => 'gradients/simple/midnight-city'],
    ['text' => __x('Nome colore profilo', 'Juicy Orange'), 'value' => 'gradients/simple/juicy-orange'],
    ['text' => __x('Nome colore profilo', 'Youtube'), 'value' => 'gradients/simple/youtube'],
    ['text' => __x('Nome colore profilo', 'PinotNoir'), 'value' => 'gradients/simple/pinot-noir'],
    ['text' => __x('Nome colore profilo', 'Learning and leaning'), 'value' => 'gradients/simple/learning-and-leaning'],
    ['text' => __x('Nome colore profilo', 'Army'), 'value' => 'gradients/simple/army']
];

// Gradienti (compless)
$config['funjob']['userProfile']['cover']['gradientComplex'] = [
    // ['text' => __('Cielo stellato notturno'), 'value' => 'grandients/complex/night'],
    // ['text' => __('Gradini'), 'value' => 'gradients/complex/steps'],
    // ['text' => __('Striscie'), 'value' => 'gradients/complex/stripes'],
    // ['text' => __('Weaves 1'), 'value' => 'gradients/complex/weave'],
    // ['text' => __('Weaves 2'), 'value' => 'gradients/complex/weaves'],
    // ['text' => __('Imbottitura'), 'value' => 'gradients/complex/upholstery'],
    // ['text' => __('Zen'), 'value' => 'gradients/complex/seigaiha'],
];

// Immagini (non più utilizzati al momento...)
$config['funjob']['userProfile']['cover']['images'] = [
];


// Validazioni uploads
$config['funjob']['upload'] = [
    'extensions' => ['image/png', 'image/jpg', 'image/jpeg'],
    'maxSize'    => 200000, // in KB (100kb)
    'minWidth'   => 500,    // in px
    'minHeight'  => 300,    // in px
];

$config['funjob']['quizColors'] = [
    '#009933' => __('Verde'),
    '#339999' => __('Verde acqua'),
    '#ff66cc' => __('Rosa'),
    '#9933cc' => __('Viola'),
    '#000099' => __('Blue'),
    '#cc3333' => __('Rosso'),
    '#000000' => __('Nero'),
    '#333333' => __('Grigio'),
    '#ff9900' => __('Arancione'),
    '#ffa05c' => __('Arancione 2'),

    '#00adee' => __('Celeste'),
    '#337ab7' => __('Blue')
];


// EMAILS
$config['funjob']['contacts'] = [
    'founder'   => ['subject' => __('Fondatore'), 'to' => 'funjob.fondatore@gmail.com'],
    'info'      => ['subject' => __('Informazioni generali'), 'to' => 'info.funjob@gmail.com'],
    'bigbrain'  => ['subject' => __('Bigbrain'), 'to' => 'funjob.bigbrain@gmail.com'],
    'store'     => ['subject' => __('Negozio'), 'to' => 'funjob.store@gmail.com'],
    'store:pix' => ['subject' => __('PIX'), 'to' => 'funjob.store@gmail.com'],
    'sponsor'   => ['subject' => __('Sponsor e info commerciali'), 'to' => 'funjob.sponsor@gmail.com'],
    'company'   => ['subject' => __('Aziende'), 'to' => 'funjob.aziende@gmail.com'],
    'reporting' => ['subject' => __('Segnalazioni'), 'to' => 'funjob.alert@gmail.com'],
    'jobwithus' => ['subject' => __('Lavora con noi'), 'to' => 'funjob.job@gmail.com'],
];

return $config;
