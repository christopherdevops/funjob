<?php
    $this->assign('title', __('Recupera account'));
    $this->Breadcrumbs->add(__('Recupero account'));
?>

<?php
    echo $this->Form->create($User);
    echo $this->Form->control('id');

    echo $this->Form->control('email');
    echo $this->Form->button(__('Invia codice di ripristino'));
    echo $this->Form->end();
?>
