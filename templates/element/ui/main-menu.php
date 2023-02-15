<style type="text/css">
    /* Hide the menu until the document is done loading. */
    #menu:not(.mm-menu) {display: none;}

    .mm-menu .mm-title {text-transform:uppercase;font-weight:bold}
    .mm-menu .mm-search input {
        border:1px solid rgba(0, 173, 238, 0.55) !important;
        color:black !important;
        font-size:15px !important;
    }

    .mm-menu .mm-search ::-webkit-input-placeholder {
        color:#00adee !important;
        opacity:0.66;
        font-size:10px !important;
        text-transform:uppercase !important;
    }
    .mm-menu .mm-search ::-moz-placeholder {
        color:#00adee !important;
        opacity:0.66;
        font-size:10px !important;
        text-transform:uppercase !important;
    }
    .mm-menu .mm-search ::-ms-input-placeholder {
        color:#00adee !important;
        opacity:0.66;
        font-size:10px !important;
        text-transform:uppercase !important;
    }
    .mm-menu .mm-search :-moz-placeholder {
        color:#00adee !important;
        opacity:0.66;
        font-size:10px !important;
        text-transform:uppercase !important;
    }
    .mm-menu .mm-search :-ms-input-placeholder
    {
        color:#00adee !important;
        opacity:0.66;
        font-size:10px !important;
        text-transform:uppercase !important;
    }
</style>

<nav id="menu">
    <ul role="mmenu-list">

        <?php if ($this->request->getSession()->check('Auth.User')) : ?>
        <li>
            <span>
                <?= $this->User->avatar($UserAuth->avatarSrcMobile, ['height' => 12, 'width' => 12]) ?>
                <?= __('Il tuo account') ?>
            </span>
            <ul>
                <li>
                    <a href="<?php echo $this->Url->build(['_name' => 'me:dashboard']) ?>">
                        <?= __('Sommario') ?></a>
                    </li>
                <li>
                <li>
                    <a href="<?php echo $this->Url->build(['_name' => 'me:settings']) ?>">
                        <?= __('Opzioni account') ?></a>
                    </li>
                <li>
                    <a href="<?php echo $this->Url->build(['_name' => 'user:profile', 'id' => $this->request->getSession()->read('Auth.User.id'), 'username' => $this->request->getSession()->read('Auth.User.username')]) ?>">
                        <?= __('Profilo pubblico') ?>
                    </a>
                </li>
                <li>
                    <span>
                        Amici
                    </span>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'index']) ?>">
                                <?= __('Amici') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'waiting']) ?>">
                                <?= __('Richieste') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <span><?= __('Gruppi') ?></span>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'mygroups:archive:created']) ?>">
                                <?= __('Gruppi creati') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'mygroups:archive:joined']) ?>">
                                <?= __('Gruppi di cui faccio parte') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <span>
                        <?= __('Curriculum') ?>
                    </span>
                    <ul>
                        <li>
                            <a href="<?php echo $this->Url->build(['_name' => 'me:settings', '#' => 'job']) ?>">
                                <?= __('Carica') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'cv:authorizations:archive']) ?>">
                                <?= __('Autorizzazioni') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <span>
                        <?= __('Giochi') ?>
                    </span>
                    <ul>
                        <li>
                            <a href="<?php echo $this->Url->build(['_name' => 'me:quizzes']) ?>">
                                <?= __('Giochi creati') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Url->build(['_name' => 'me:quizzes:completed']) ?>">
                                <?= __('Giochi completati') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'me:orders']) ?>">
                        <?php echo __('Premi richiesti') ?>
                    </a>
                </li>
                <li>
                    <span>
                        <?= __('Pubblicità') ?>
                    </span>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'sponsor', 'controller' => 'SponsorAdvs', 'action' => 'index']) ?>">
                                <?php echo __('Pubblicità create') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'sponsor', 'controller' => 'SponsorAdvs', 'action' => 'add']) ?>">
                                <?php echo __('Crea nuova') ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'me:settings', '#' => 'tab-skills']) ?>">
                        <?php echo __('Ricevi offerte di lavoro') ?>
                    </a>
                </li>


                <li class="divider"></li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'auth:logout']) ?>">
                        <?php echo __('Esci') ?>
                    </a>
                </li>
            </ul>
        </li>
        <?php else: ?>
            <li>
                <span> <i class="fa fa-sign-in"></i>  <?php echo __('Accedi') ?></span>
                <ul>
                    <li>
                        <a href="<?php echo $this->Url->build(['_name' => 'auth:register']) ?>">
                            <?= __('Registrati') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Url->build(['_name' => 'auth:login']) ?>">
                            <?= __('Accedi') ?>
                        </a>
                    </li>
                </ul>
            </li>
        <?php endif ?>

        <li>
            <a href="<?= $this->Url->build(['_name' => 'funjob:info']) ?>">
                <i class="text-color-primary fa fa-sign-in"></i>
                <?= __('Guida FunJob') ?>
            </a>
        </li>

        <li>
            <span> <i class="fa fa-info-circle" aria-hidden="true"></i> <?= __x('menu', 'Informazioni') ?></span>
            <ul>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:whois']) ?>">
                        <?= __('Chi siamo?') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:info']) ?>">
                        <?= __('Guida FunJob') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:profiles']) ?>">
                        <?= __('FunJob per ...') ?>
                    </a>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:user']) ?>">
                                <?= __('Utenti') ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:company']) ?>">
                                <?= __('Aziende') ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['_name' => 'funjob:profiles:sponsor']) ?>">
                                <?= __('Sponsor') ?>

                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'store:index']) ?>"><?= __('Negozio') ?></a>
                </li>


                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:terms']) ?>"><?= __('Termini e Condizioni') ?></a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'funjob:cookies']) ?>"><?= __('Cookies') ?></a>
                </li>
            </ul>
        </li>

        <li>
            <span>
                <i class="fa fontello-brain"></i>
                <?= __('BigBrain (collaboratori)') ?>
            </span>
            <ul>
                <li><a href="<?= $this->Url->build(['_name' => 'bigbrains:index']) ?>"><?= __('Collaboratori') ?></a></li>
                <li><a href="<?= $this->Url->build(['_name' => 'bigbrains:contact']) ?>"><?= __('Collabora con noi') ?></a></li>
            </ul>
        </li>

        <li>
            <span> <i class="fa fa-gamepad" aria-hidden="true"></i> <?php echo __('Gioca') ?></span>
            <ul>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'quiz:index', '?' => ['open-search' => true]]) ?>">
                        <?php echo __('Cerca argomento') ?>
                    </a>
                </li>

                <li class="divider"></li>
                <li>
                    <a href="<?= $this->Url->build(['plugin' => false, 'controller' => 'quizzes', 'action' => 'add']) ?>">
                        <?php echo __('Crea nuovo') ?>
                    </a>
                </li>

                <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'me:quizzes']) ?>">
                        <?= __('I miei quiz') ?>
                    </a>
                </li>
                <?php endif ?>
            </ul>
        </li>

        <li>
            <span> <i class="fa fa-shopping-cart"></i> <?= __('Negozio') ?></span>
            <ul>
                <li><a href="<?= $this->Url->build(['_name' => 'store:index']) ?>"><?= __('Premi in palio') ?></a></li>
                <li><a href="<?= $this->Url->build(['_name' => 'me:orders']) ?>"><?= __('I miei ordini') ?></a></li>
            </ul>
        </li>

        <li>
            <span> <i class="fa fa-users" aria-hidden="true"></i> <?php echo __('Utenti') ?></span>
            <ul>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'user:search']) ?>">
                        <?= __('Cerca utente') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'groups:archive']) ?>">
                        <?= __('Gruppi di utenti') ?>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <span> <i class="fa fa-university" aria-hidden="true"></i> <?php echo __('Gruppi') ?></span>
            <ul>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'groups:archive']) ?>">
                        <?= __('Sfoglia gruppi') ?>
                    </a>
                </li>

                <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'mygroups:archive:created']) ?>">
                        <?= __('Gruppi che hai creato') ?>
                    </a>
                </li>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'mygroups:archive:joined']) ?>">
                        <?= __('Gruppi di cui fai parte') ?>
                    </a>
                </li>
                <?php endif ?>

                <li>
                    <a href="<?= $this->Url->build(['_name' => 'groups:create']) ?>">
                        <?= __('Nuovo gruppo') ?>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <span>  <i class="fa fa-handshake-o"></i> <?php echo __('Aziende') ?></span>
            <ul>
                <li>
                    <a href="<?= $this->Url->build(['_name' => 'companies:index']) ?>">
                        <?= __('Cerca aziende') ?>
                    </a>
                </li>

                <?php // TODO ?>
                <!--
                <li>
                    <a href="#">
                        Offerte di lavoro
                        <span class="tag tag--job"></span>
                    </a>
                </li>
                -->
            </ul>
        </li>

        <li>
            <a href="<?= $this->Url->build(['prefix' => false, 'plugin' => false, 'controller' => 'contacts']) ?>">
                <i class="text-color-primary fa fa-envelope"></i>
                <?php echo __('Contatti') ?>
            </a>
        </li>

        <?php if ($this->request->getSession()->read('Auth.User.type') == 'admin'): ?>
        <li>
            <span> <i class="fa fa-cogs" aria-hidden="true"></i> <?= __('Amministratore') ?></span>
            <ul>
                <li>
                    <span><?= __('Homepage') ?></span>
                    <ul>
                        <li>
                            <?= $this->Html->link(__('Impostazioni generali'), ['prefix' => 'admin', 'controller' => 'Homepages', 'action' => 'index']) ?>
                        </li>

                        <li>
                            <?= $this->Html->link(__('Categorie Popolari'), ['prefix' => 'admin', 'controller' => 'Homepages', 'action' => 'popularCategories']) ?>
                        </li>

                        <li>
                            <?= $this->Html->link(__('Quiz Popolari'), ['prefix' => 'admin', 'controller' => 'Homepages', 'action' => 'popularQuizzes']) ?>
                        </li>
                    </ul>
                </li>

                <li>
                    <span><?= __('Giochi') ?></span>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'quizzes', 'action' => 'index' ]) ?>">
                                <?= __('Archivio') ?>
                            </a>
                        </li>
                        <li>
                            <span><?= __('Categorie (Materie)') ?></span>
                            <ul>
                                <li><a href="<?= $this->Url->build(['_name' => 'admin/quiz-categories/index']) ?>"><?= __('Mostra') ?></a></li>
                                <li><a href="<?= $this->Url->build(['_name' => 'admin/quiz-categories/add']) ?>"><?= __('Nuova') ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <span><?= __('Utenti') ?></span>
                    <ul>
                        <li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index', 'prefix' => 'admin']) ?>">Mostra</a></li>
                    </ul>
                </li>

                <li>
                    <span><?= __('Gruppi utenti') ?></span>
                    <ul>
                        <li><a href="<?= $this->Url->build(['controller' => 'UserGroups', 'action' => 'index', 'prefix' => 'admin']) ?>">Mostra</a></li>
                    </ul>
                </li>

                <?php
                /*
                <li>
                    <span>Categorie Job</span>
                    <ul>
                        <li><a href="<?= $this->Url->build(['_name' => 'admin/quiz-categories/index']) ?>">Mostra</a></li>
                        <li><a href="<?= $this->Url->build(['_name' => 'admin/quiz-categories/add']) ?>">Nuova</a></li>
                    </ul>
                </li>
                */
               ?>

                <li>
                    <span><?= __('Pubblicità') ?></span>
                    <ul>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'SponsorAdvPackages', 'action' => 'index']) ?>">
                                Pacchetti pubblicitari
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'SponsorAdvPackages', 'action' => 'add']) ?>">
                                Nuovo pacchetto
                            </a>
                        </li>
                        <li>
                            <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'SponsorAdvs', 'action' => 'index']) ?>">
                                Mostra pubblicità create
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <span><?= __('Negozio') ?></span>
                    <ul>
                        <li>
                            <span><?= __('Prodotti') ?></span>
                            <ul>
                                <li>
                                    <a href="<?= $this->Url->build(['_name' => 'store:admin:product:index']) ?>">Mostra</a>
                                </li>
                                <li>
                                    <a href="<?= $this->Url->build(['_name' => 'store:admin:product:add']) ?>">Nuovo</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span><?= __('Categorie') ?></span>
                            <ul>
                                <li><a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'StoreProductCategories', 'action' => 'index']) ?>"><?= __('Mostra') ?></a></li>
                                <li><a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'StoreProductCategories', 'action' => 'add'])  ?>"><?= __('Nuova') ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <span><?= __('Ordini') ?></span>
                            <ul>
                                <li><a href="<?= $this->Url->build(['_name' => 'store:admin:order:index']) ?>">Mostra</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <span><?= __('Aziende') ?></span>
                    <ul>
                        <li><a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'CompanyCategories', 'action' => 'index']) ?>"><?= __('Settori') ?></a></li>
                    </ul>
                </li>

            </ul>
        </li>
        <?php endif ?>

    </ul>
</nav>

<?php // Non funziona usando css_head--inline ?>
<?php $this->append('css_foot') ?>
<style>
    .mm-menu img {
        height:20px;width:20px;
    }

    <?php
    // Problema con offCanvas.blockUI=true (sposta il contenuto a lato - nasconde la scrollbar laterale)
    // @see https://github.com/FrDH/jQuery.mmenu/issues/181
   ?>
    html.mm-opened {
        overflow: auto !important;
    }
</style>
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
<script>
   $(function() {

    <?php // CONFIGURAZIONE JQUERY-MMENU ?>
    $("#menu").mmenu(
        {
            extensions: [
                <?php // CREA BLOCKUI SU CANVAS ?>
                "pagedim",
            ],

            dropdown: {
                drop: true
            },


            searchfield: {
               resultsPanel  : true,
               showTextItems : true,
               noResults     : <?= json_encode(__('Nessun risultato')) ?>,
               placeholder   : <?= json_encode(__('Cerca nel menu')) ?>
            },

            offCanvas: {
                <?php
                    // Crea blockUI all'apertura del menù; disabilitato perchè nasconde
                    // la scroll spostando il contenuto di qualche px sulla destra
                ?>
                blockUI: true,
            },

            onClick : {
                close          : true,
                preventDefault : false,
            },

            navbars: [
               {
                  position : "top",
                  content  : [
                     "searchfield"
                  ]
               }
            ]
        },
        {
            clone: false,

            offCanvas: {
                pageNodetype: "section",
                pageSelector: ".app-content"
            },

            classNames: {
               fixedElements: {
                  fixed: "Fixed"
               }
            },
        }
    );

    $("#app-menu-primary").click(function( ev ) {
        ev.preventDefault();
        var $menu = $("#menu");
        var api   = $menu.data("mmenu");

        if ($("html").hasClass("mm-opened")) api.close();
        else api.open();
    });

});
</script>
<?php $this->end() ?>
