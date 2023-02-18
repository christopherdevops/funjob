<?php
    $this->assign('title', __('Entra in FunJob'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Entra in FunJob'));

    $this->Html->css(['users/register.css']);
?>

<?php $this->append('css_head--inline') ?>
    .fa-user,
    .fa-chain {
        font-size:18px;
        padding-right:5px;
    }

    /* fix: bootstrap modal sotto overlay mmenu */
    .mm-slideout {
        z-index: inherit !important
    }

    .big-radio label i {
        font-size:19px !important;
        color:#00adee;
    }

    #register-panel .panel-heading {
        padding:5px !important;
    }

    #register-panel input[type=submit] {
        font-size:15px !important;
        font-weight:bold !important;
    }

<?php $this->end() ?>

<?php $this->start('form-twocol:wrapStart') ?>
    <div class="row">
<?php $this->end() ?>
<?php $this->start('form-twocol:wrapEnd') ?>
    </div>
<?php $this->end() ?>

<?php $this->start('form-twocol:start') ?>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<?php $this->end() ?>
<?php $this->start('form-twocol:end') ?>
    </div>
<?php $this->end() ?>
<?php $this->start('form-twocol:startWrap') ?>
    <div class="row">
<?php $this->end() ?>

<?php $this->start('register-form') ?>
    <?php
        echo $this->Form->create($User, ['url' => ['action' => 'register'], 'valueSources' => ['query', 'context']]);

        echo '<div class="big-radio">';
        echo $this->Form->control('type', [
            'label'   => false,
            'type'    => 'radio',
            'default' => 'user',
            'escape'  => false,
            'options' => [
                'user'    => '<i class="fa fa-user"></i> '       . __('Sono un utente privato'),
                'company' => '<i class="fa fa-building-o"></i> ' . __('Sono un azienda')
            ]
        ]);
        echo '</div>';
    ?>

    <div class="user-fields" style="<?= $this->request->getData('type', 'user') == 'user' ? '' : 'display:none' ?>">
        <?php
            echo $this->fetch('form-twocol:wrapStart');
            echo $this->fetch('form-twocol:start');
            echo $this->Form->control('first_name', [
                'label' => __('Nome'),
                'help'  => __('Non verrà visualizzato e sarà modificabile in futuro')
            ]);
            echo $this->fetch('form-twocol:end');
            echo $this->fetch('form-twocol:start');
            echo $this->Form->control('last_name', [
                'label' => __('Cognome'),
                'help'  => __('Non verrà visualizzato e sarà modificabile in futuro')
            ]);
            echo $this->fetch('form-twocol:end');
            echo $this->fetch('form-twocol:wrapEnd');
        ?>
    </div>
    <div class="company-fields" style="<?= $this->request->getData('type', 'user') == 'company' ? '' : 'display:none' ?>">
        <?php
            echo $this->Form->control('name', [
                'label' => __('Ragione sociale')
            ]);
        ?>
    </div>


    <?php
        echo $this->Form->control('email', [
            'label' => __('E-mail'),
            'prepend' => '<i class="fa fa-envelope"></i>',
            'help'  => __('Utilizzata per il recupero password (non verrà mostrata su FunJob)'),
        ]);
        echo $this->Form->control('username', [
            'label'   => __('Nome utente'),
            'prepend' => '<i class="fa fa-at"></i>',
            'help'    => __('Sarà il tuo nickname su FunJob'),
        ]);
        echo $this->Form->control('password', [
            'label' => __('Password (minimo 5 caratteri)'),
            'type'  => 'password',
        ]);
        echo $this->Form->control('password_confirm', [
            'label' => __('Password (digita nuovamente)'),
            'type'  => 'password',
        ]);

        echo $this->Form->control('accept_terms', [
            'label' => (
                __('Accetto i ') .
                ' <a style="text-decoration:underline !important;" href="'. $this->Url->build(['controller' => 'Pages', 'action' => 'display', 'terms_and_conditions']) .'" target="_blank">Termini e condizioni</a>'
            ),
            'type'  => 'checkbox',
            'escape' => false
        ]);

        echo $this->Form->submit(
            __('Registrati'),
            ['class'  => 'btn btn-primary btn-sm btn-block ']
        );
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<?php $this->start('register-social') ?>
    <div class="row">
        <div class="col-md-12">
            <p class="user-register-title">
                <?= __('Registrandoti attraverso social network FunJob accederà al tuo nome utente ed email.') ?>
            </p>

            <div class="alert alert-success">
                <p class="user-register-disclaimer">
                    <i class="fa fa-info-circle"></i>
                    <?=
                        __('Non verranno in alcun modo salvate informazioni sensibili quali password o inviati messaggi automatizzati senza il tuo consenso.')
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'auth:register:hybrid', '?' => ['provider' => 'Facebook']]) ?>" class="btn btn-default btn-md btn-block" style="background-color:#2871cd;color:white">
                <i class="fa fa-facebook"></i>
                Facebook
            </a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'auth:register:hybrid', '?' => ['provider' => 'Google']]) ?>" class="btn btn-default btn-md btn-block" style="background-color:red;color:white">
                <i class="fa fa-google"></i>
                Google
            </a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <a href="<?= $this->Url->build(['_name' => 'auth:register:hybrid', '?' => ['provider' => 'Twitter']]) ?>" class="btn btn-default btn-md btn-block" style="background-color:#00adee;color:white">
                <i class="fa fa-twitter"></i>
                Twitter
            </a>
        </div>
    </div>
<?php $this->end() ?>

<div class="no-padding col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div id="register-panel" class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">
                <i class="fa fa-user"></i>
                <span><?php echo __('Registrati') ?></span>
            </h2>
        </div>
        <div class="panel-body">

            <div class="alert alert-info">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p style="margin-bottom:20px" class="font-size-lg text-muted text-center text-bold">
                            <?= __('Acquisici dati da Social Networks') ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a href="<?= $this->Url->build(['_name' => 'auth:register:hybrid', '?' => ['provider' => 'Facebook']]) ?>" class="btn btn-default btn-sm btn-block" style="background-color:#2871cd;color:white">
                            <i class="fa fa-facebook"></i>
                            Facebook
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a href="<?= $this->Url->build(['_name' => 'auth:register:hybrid', '?' => ['provider' => 'Google']]) ?>" class="btn btn-default btn-sm btn-block" style="background-color:red;color:white">
                            <i class="fa fa-google"></i>
                            Google
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a href="<?= $this->Url->build(['_name' => 'auth:register:hybrid', '?' => ['provider' => 'Twitter']]) ?>" class="btn btn-default btn-sm btn-block" style="background-color:#00adee;color:white">
                            <i class="fa fa-twitter"></i>
                            Twitter
                        </a>
                    </div>
                </div>
            </div>

            <?php echo $this->fetch('register-form') ?>
        </div>
    </div>
</div>




<script type="text/javascript">
    $("#type-user").on("click", function(evt) {
        $(".company-fields").hide();
        $(".company-fields input").val('');
        $(".user-fields").show();
    });
    $("#type-company").on("click", function(evt) {
        $(".user-fields").hide();
        $(".user-fields input").val('');
        $(".company-fields").show();
    });
</script>
