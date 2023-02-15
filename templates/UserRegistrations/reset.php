<?php
    $this->assign('title', __('Recupera password'));
    $this->Breadcrumbs->add(__('Recupero password'));
?>

<?php
    echo $this->Form->create($User);
    echo $this->Form->hidden('id');

    echo $this->Form->control('username', [
        'label'    => 'Username',
        'disabled' => 'disabled'
    ]);

    echo $this->Form->control('password');
    echo $this->Form->control('password_confirm', [
        'label' => __('Digita nuovamente la password'),
        'type'  => 'password'
    ]);

    echo $this->Form->button(__('Salva password'));
    echo $this->Form->end();
?>
