<?php
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add($User->username, ['_name' => 'user:profile:home', 'id' => $User->id, 'username' => $User->slug])
        ->add(__('Impostazioni'));
?>

<?php $this->append('css_head--inline') ?>
    #user-profile-cover {
        width:100%;
        height:150px;
    }

    #user-profile-cover[class*="gradients-"] {}


    .tab-pane > .page-header {
        color:#00adee;
    }
    .tab-pane > .page-header h1 {
        font-size:20px !important;
        text-transform:uppercase;
        font-weight:bold;

        text-align:center;
    }
    .tab-pane > .page-header small {
        display:block;
        text-align:center;
    }

    #tabs .panel-title {color:#666666 !important;}
<?php $this->end() ?>

<?php $this->start('cover') ?>
    <fieldset>
        <?php
            use \Cake\Core\Configure;
            echo $this->Form->input('background_cover', [
                'type'    => 'select',
                'label'   => __('Immagine di copertina'),
                'options' => [
                    __('Gradienti')           => Configure::readOrFail('funjob.userProfile.cover.gradient'),
                    //__('Gradienti complessi') => Configure::readOrFail('funjob.userProfile.cover.gradientComplex')
                ],
                'default' => $User->background_cover
            ]);
            echo '<div id="user-profile-cover" class="thumbnail gradients-simple-' .$User->background_cover. ' " style="height:150px;"></div>';
        ?>
    </fieldset>
    <?php $this->Html->scriptStart(['block' => 'js_foot']); ?>
    $(function() {
        var getStyle = function(uri) {
            return $("<link/>", {
               rel  : "stylesheet",
               type : "text/css",
               href : uri
            }).appendTo("head");
        };

        $('#background-cover').on("change", function(evt) {
            var previewBox = document.querySelector("#user-profile-cover");

            if (this.value.match(/^gradients/i) !== null) {
                getStyle("/css/" + this.value + ".css");

                var className = this.value.replace(/\//g, "-");
                previewBox.classList = [];
                previewBox.style     = "";

                previewBox.classList.add("thumbnail");
                previewBox.classList.add(className);
            } else {
                var src = "/backgrounds/" +this.value+ ".jpg";

                previewBox.classList = ["thumbnail"];
                previewBox.style.backgroundImage = "url(" +src+ ")";
            }

        }).trigger("change");
    });
    <?php $this->Html->scriptEnd() ?>
<?php $this->end(); ?>

<?php $this->start('tab:account') ?>
    <div class="page-header">
        <h1><?php echo __('Impostazioni relative al tuo account funjob') ?></h1>
    </div>

    <?php
        echo $this->Form->create($User, [
            'type'    => 'file',
            'url'     => ['_name' => 'me:settings:prefixed', 'prefix' => $this->request->prefix, '#' => 'account'],
            'context' => [
                'validator' => [
                    'Users' => 'settingsAccountUser'
                ]
            ]
        ]);
    ?>
    <fieldset>
        <?php
            echo $this->Form->hidden('user_id', ['value' => $User->id]);

            echo $this->Form->control('email', [
                'label'    => __('E-mail di login'),
                'help'     => __('Utile per recuperare la password, e NECESSARIA per acquistare dallo store'),
                'disabled' => 'disabled'
            ]);
            echo $this->Form->control('password', [
                'label' => __('Password di login'),
                'type'  => 'password',
                'value' => '',
                'help'  => __('Compila questo campo solo se intendi cambiare la password')
            ]);
            echo $this->Form->control('password_confirm', [
                'label' => __('Ripeti password di login'),
                'type'  => 'password',
                'value' => '',
                'help'  => __('Compila questo campo solo se intendi cambiare la password')
            ]);

            echo $this->Form->input('lang', [
                'label'   => __('Lingua interfaccia'),
                'options' => $langs
            ]);

            echo $this->Form->hidden('_tab', ['value' => 'account']);
            echo $this->Form->submit(__('Aggiorna'), ['class' => 'btn btn-block btn-primary']);
        ?>
    </fieldset>
    <?php echo $this->Form->end() ?>

    <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a href="<?= $this->Url->build(['_name' => 'me:disable']) ?>" class="pull-right btn btn-sm btn-danger">
                <i class="fa fa-warning"></i>
                <?= __('Elimina account') ?>
            </a>
        </div>
    </div>
<?php $this->end() ?>

<?php $this->start('tab:profile') ?>
    <div class="page-header">
        <h1><?php echo __('Impostazioni profilo Fun (informale)') ?></h1>
        <small><?= __('Profilo personale pubblico') ?></small>
    </div>

    <?php
        echo $this->Form->create($User, ['type' => 'file', 'url' => ['_name' => 'me:settings:prefixed', 'prefix' => $this->request->prefix, '#' => 'profile']]);

        // Il campo cover viene impostato tramite un blockView
        // Il FormHelper sembra che non lo aggiunge nella lista dei campi permessi a quanto pare
        $this->Form->unlockField('background_cover');
        $this->Form->unlockField('avatar');

        $this->Form->setTemplates([
            'uploadWidgetContainer' => '<div class="row">{{content}}</div>',
            'uploadWidgetPreview'   => '<div class="col-md-4"><img height="80" width="80" class="img-circle" alt="" src="{{src}}" /></div>',
            'uploadWidgetInput'     => '<div class="col-md-6">{{input}} <label{{attrs}}>{{text}}</label></div>'
        ]);
    ?>

    <fieldset>
        <?php
            // Usato per UploadBehavior (definisce la path di destinazione tramite {{field:user_id}})
            echo $this->Form->hidden('user_id', ['value' => $User->id]);
            echo $this->Form->control('avatar', [
                'type'  => 'upload',
                'label' => __('Immagine del profilo (avatar)'),
                'help'  => 'Dimensione massima 200 KB, formato minimo 100x100'
            ]);

            // Necesita $this->Form->unlockField su background_cover
            echo $this->fetch('cover');

            echo '<div class="row">';
            echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">';
            echo $this->Form->input('first_name', [
                'label' => __('Nome'),
                'max'   => 140,
                'help'  => __('Utilizzato nelle ricerche')
            ]);
            echo '</div>';
            echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">';
            echo $this->Form->input('last_name', [
                'label' => __('Cognome'),
                'max'   => 140,
                'help'  => __('Utilizzato nelle ricerche')
            ]);
            echo '</div>';
            echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">';
            echo $this->Form->control('show_fullname', [
                'type'  => 'checkbox',
                'label' => __('Mostra Nome e Cognome nel tuo profilo')
            ]);
            echo '</div>';

            echo '</div>';


            echo $this->Form->input('account_info.sex', [
                'label'   => __('Sesso'),
                'empty'   => __('Non specificato'),
                'options' => [
                    'male'   => __('Maschio'),
                    'female' => __('Femmina'),
                ]
            ]);

            echo $this->Form->input('account_info.phone', [
                'label'   => __('Numero telefonico'),
            ]);
            echo $this->Form->input('account_info.show_phone', [
                'type'   => 'checkbox',
                'label'  => __('Mostrare nel profilo')
            ]);

            echo $this->Form->input('title', [
                'label' => __('Titolo'),
                'max'   => 140,
                'help'  => __('Descriviti in {characters} caratteri (te o la tua professione)', ['characters' => 140])
            ]);

            echo $this->Form->input('account_info.profession', [
                'label' => __('Professione'),
                'max'   => 40,
                'help'  => __('La tua professione in breve (massimo {characters} caratteri)', ['characters' => 40])
            ]);

            if ($User->is_bigbrain) {
                echo $this->Form->input('bigbrain_area', [
                    'label'  => __('{icon} Bigbrain: Materie', ['icon' => '<i class="fontello-brain text-color-primary"></i>']),
                    'max'    => 150,
                    'escape' => false,
                    'help'   => __('Le materie in cui sei specializzato')
                ]);
            } else {
                $this->Form->hidden('bigbrain_area', ['value' => null]);
            }

            echo $this->element('UserSettings/age');
            echo $this->element('UserSettings/born_city');
            echo $this->element('UserSettings/live_city');

            echo $this->Form->hidden('profile_block.id', ['value' => $User->profile_block->id]);
            echo $this->Form->hidden('profile_block.user_id', ['value' => $User->id]);

            // PROFILE FIELDs
            echo $this->element('UserSettings/links');
            echo $this->element('UserSettings/user_profile_box__fun');
            echo $this->element('UserSettings/user_profile_box__job');

            echo $this->Form->hidden('_tab', ['value' => 'profile']);
            echo $this->Form->submit(__('Aggiorna'), ['class' => 'btn btn-block btn-primary']);
        ?>
    </fieldset>
    <?php echo $this->Form->end() ?>
<?php $this->end() ?>

<?php $this->start('tab:job') ?>
    <div class="page-header">
        <h1><?php echo __('Impostazioni profilo Job (professionale)') ?></h1>
        <small><?= __('Attraverso queste impostazioni verrai ricercato dalle aziende per offerte di lavoro') ?></small>
    </div>

    <?php echo $this->Form->create($User, ['type' => 'file', 'url' => ['_name' => 'me:settings:prefixed', 'prefix' => $this->request->prefix, '#' => 'job']]); ?>
    <fieldset>
        <?php
            echo $this->Form->hidden('user_id', ['value' => $User->id]);
        ?>
        <?php //echo $this->element('UserSettings/age') ?>
        <?php //echo $this->element('UserSettings/born_city') ?>
        <?php //echo $this->element('UserSettings/live_city') ?>

        <?php echo $this->element('UserSettings/cv_settings') ?>
        <?php echo $this->element('UserSettings/skill_tags') ?>

        <?php
            // Utilizzato User.UserSkills (skill_tags element)
            // echo $this->Form->input('job_offers._ids', [
            //     'type'     => 'select',
            //     'multiple' => 'checkbox',
            //     'options'  => $UserJobOffers,
            //     'label'    => __('Interessato ad offerte di lavoro per il ruolo di'),
            //     'help'     => __('Le aziende potranno cercarti e contattarti per offerte di lavoro'),
            // ]);

            echo $this->Form->hidden('_tab', ['value' => 'job']);
            echo $this->Form->submit(__('Aggiorna'), ['class' => 'btn btn-block btn-primary']);
        ?>
    </fieldset>
    <?php echo $this->Form->end() ?>
<?php $this->end() ?>

<div id="tabs" role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#account" aria-controls="account" role="tab" data-toggle="tab">
                <i class="fa fa-cogs"></i>
                <?php echo __('Generali') ?>
            </a>
        </li>
        <li role="presentation">
            <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                <i class="fa fa-user-circle-o"></i>
                <?php echo __('Profilo') ?>
            </a>
        </li>
        <li role="presentation">
            <a href="#job" aria-controls="job" role="tab" data-toggle="tab">
                <i class="fa fa-handshake-o"></i>
                <?php echo __('Lavoro') ?>
            </a>
        </li>

        <li class="pull-right" role="presentation">
            <a target="_blank" href="<?= $this->Url->build(['_name' => 'me:profile']) ?>">
                <i class="fa fa-eye"></i>
                <?php echo __('Mostra profilo') ?>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="account">
            <?php echo $this->fetch('tab:account') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">
            <?php echo $this->fetch('tab:profile') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="job">
            <?php echo $this->fetch('tab:job') ?>
        </div>
    </div>
</div>



<script>
    $(function() {
        if ($.trim(window.location.hash) !== "") {
            //$("#tabs a[role=tab][href='" +window.location.hash+ "']").tab("show");
            var $el = $(window.location.hash);
            if ($el) {
                var $tabCnt = $el.closest('.tab-pane');
                var tabID   = $tabCnt.prop("id");
                var $tabLnk = $("#tabs a[role=tab][href='#" + tabID + "']");

                $tabLnk.tab("show");
                setTimeout(function() {
                    $("html,body").animate({scrollTop: $tabLnk - $(".app-menu-nav-secondary").offset().top })
                }, 500);
            }
        }
    });
</script>
