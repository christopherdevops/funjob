<div class="alert alert-warning">
    <?= __('Il CV di {0} necessita di un\'autorizzazione per poter essere visualizzato', $User->username) ?>
</div>

<?php
    echo $this->Form->create($CvAuthorization, ['url' => ['_name' => 'cv:request', 'uuid' => $User->account_info->cv_uuid]]);
    echo $this->Form->hidden('user_id', ['value' => $User->id]);

    $help = __x(
        'Modulo autorizzazione CV',
        'Se sei un azienda specifica sempre la motivazione per invogliare l\'utente a condividere il suo CV con te'
    );

    echo $this->Form->control('reason', [
        'type'        => 'textarea',
        'label'       => __x('Modulo autorizzazione CV', 'Motivazione'),
        'placeholder' => __x('Modulo autorizzazione CV', 'Specifica la motivazione per cui richiedi la visualizzazione'),
        'help'        => '<span class="font-size-sm">' .$help. '</small>'
    ]);
    echo $this->Form->button(__('Invia'), ['class' => 'btn btn-block btn-primary']);
    echo $this->Form->end(['data-type' => 'hidden']);
?>
