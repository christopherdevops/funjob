
<?php
    echo $this->Form->create($Form);
    echo $this->Form->control('pwd', [
        'type'  => 'password',
        'label' => false,
        'placeholder' => __('Password'),
    ]);
    echo $this->Form->button(__('Accedi'), ['class' => 'btn btn-default btn-sm']);

    echo $this->Form->end();
?>
