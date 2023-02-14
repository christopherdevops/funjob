<?php
    $this->assign('title', __('Account'));
    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Account'));
?>

<?php if (!$User->is_verified_mail) : ?>
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title text-center">
                <?= __('Richiesta verifica e-mail per proseguire') ?>
            </h3>
        </div>
        <div class="panel-body">
            <p class="text-warning text-center text-bold">
                <?= __('Ti è stata inviata un email a {address} contenente un link per poter verificare il tuo account email', ['address' => $User->email]) ?>
            </p>
            <hr>
            <div class="pull-right">
                <?php
                    echo $this->Form->create(null, ['url' => ['action' => 'confirmation_resend']]);
                    echo $this->Form->hidden('id', ['value' => $User->id]);
                    echo $this->Form->submit(__('Invia nuovamente'), [
                        'class' => 'btn btn-sm btn-default btn-block'
                    ]);
                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong><?= __('Campi richiesti') ?>!</strong>
        <p><?= __('Compila i campi sottostanti per poter procedere nella navigazione') ?></p>
    </div>

    <?php
        debug($User->getErrors());

        echo $this->Form->create($User);

        // TODO:
        // Fornire all'utente un'occasione per modificare il proprio username in fase di completamento dell'installazione
        // se ha usato un social network per la registrazione?!
        if (strpos($User->username, 'funjob') === 0 || $User->getErrors('username')) {
            echo $this->Form->control('username', [
                'help' => __('Sarà il tuo nome su FunJob')
            ]);
        }

        if (empty($User->email)) {
            echo $this->Form->control('email', [
                'help' => '<span class="text-warning">' .__('Verrà inviata un email a questo indirizzo per verificare l\'account') . '</span>'
            ]);
        } if ($User->is_verified_mail == false) {
            echo $this->Form->control('_email', [
                'value'    => $User->email,
                'disabled' => 'disabled',
                'help'     => '<span class="text-danger">' .__('Controlla la mail specificata, per verificare il tuo account') . '</span>'
            ]);
        }

        if (empty($User->type)) {
            echo $this->Form->control('type', [
                'label'   => false,
                'type'    => 'radio',
                'options' => [
                    'user'    => __('Sono un privato'),
                    'company' => __('Sono un azienda')
                ]
            ]);
        }

        if (empty($UserSrc->password) || $User->getErrors('password') || $User->getErrors('password_confirm')) {
            echo $this->Form->control('password');
            echo $this->Form->control('password_confirm', [
                'label' => __('Digita nuovamente la password'),
                'type'  => 'password'
            ]);
        }

        //echo $this->Form->hidden('is_verified_mail', ['value' => (int) $User->is_verified_mail]);

        echo $this->Form->button(__('Aggiorna'), ['class' => 'btn btn-sm btn-primary']);
        echo $this->Form->end();
    ?>
<?php endif ?>
