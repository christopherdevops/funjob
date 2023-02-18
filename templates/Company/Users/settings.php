<?php
    $this->assign('header', ' ');

    $this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot']);
    $this->Html->css(['/bower_components/select2/dist/css/select2.min.css', 'features/select2-bootstrap.min.css'], ['block' => 'css_foot']);

    $this->Breadcrumbs
        ->add($User->username, ['_name' => 'user:profile:home', 'id' => $User->id, 'username' => $User->slug])
        ->add(__('Impostazioni'), $this->request->here);
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

<?php $this->start('js:category:select2') ?>
    $(".js-company-category").select2({
        placeholder : <?= json_encode(__('Tutte le categorie')) ?>,
        width       : "100%",
        theme       : "bootstrap"
    });
<?php $this->end() ?>


<?php $this->start('cover') ?>
    <fieldset>
        <?php
            use \Cake\Core\Configure;
            echo $this->Form->control('background_cover', [
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

        $("#background-cover").on("change", function(evt) {
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
                    'Users' => 'settingsAccountCompany'
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

            echo $this->Form->control('lang', [
                'label'   => __('Lingua interfaccia'),
                'options' => $langs
            ]);
            echo $this->Form->hidden('_tab', ['value' => 'account']);
            echo $this->Form->submit(__('Aggiorna'), ['class' => 'btn btn-block btn-primary']);
        ?>
    </fieldset>

    <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a href="<?= $this->Url->build(['_name' => 'me:disable']) ?>" class="pull-right btn btn-sm btn-danger">
                <i class="fa fa-warning"></i>
                <?= __('Elimina account') ?>
            </a>
        </div>
    </div>

    <?php echo $this->Form->end() ?>
<?php $this->end() ?>

<?php $this->start('tab:profile') ?>
    <div class="page-header">
        <h1><?php echo __('Impostazioni relative al tuo profilo personale') ?></h1>
        <small><?php echo __('Profilo Job (professionale)') ?></small>
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

            echo $this->Form->control('name', [
                'label' => __('Ragione sociale'),
                'max'   => 140,
                'help'  => __('Sarà mostrato nel tuo profilo')
            ]);

            echo $this->Form->control('title', [
                'label' => __('Titolo'),
                'max'   => 140,
                'help'  => __('Descriviti in {characters} caratteri (te o la tua professione)', ['characters' => 140])
            ]);

            echo $this->Form->control('account_info.phone', [
                'label' => __('Recapito telefonico'),
                'max'   => 80,
                'help'  => __('Sarà mostrato nel tuo profilo')
            ]);

            // COMPANY INFOS
            echo '<label>' .__('Città'). '</label>';
            echo $this->element('CompanySettings/city', []);

            echo $this->Form->control('categories._ids', [
                'class'    => 'js-company-category',
                'multiple' => 'radio',
                'options'  => $companyCategories,
                'help'     => __('Potrai essere ricercato tramite queste categorie')
            ]);

            echo $this->Html->scriptStart(['block' => 'js_foot']);
            echo $this->fetch('js:category:select2');
            echo $this->Html->scriptEnd();

            echo $this->Form->control('account_info.url', [
                'label' => __('Pagina web'),
                'type'  => 'url'
            ]);

            // BOXES PROFILO
            echo $this->Form->hidden('profile_block.id');
            echo $this->Form->hidden('profile_block.user_id');

            echo $this->element('CompanySettings/links');

            echo $this->element('CompanySettings/history');
            echo $this->element('CompanySettings/mission');
            echo $this->element('CompanySettings/staff');

            echo $this->element('CompanySettings/candidates');
            echo $this->element('CompanySettings/job_roles');

            echo $this->Form->hidden('_tab', ['value' => 'profile']);
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
