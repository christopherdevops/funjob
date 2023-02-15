<p><?= __('Ciao @{username}', ['username' => $UserRecipient->username]) ?>,</p>
<p><?= __('Hai ricevuto una nuova richiesta di amicizia da parte di un utente funjob') ?></p>

<hr>
<p>
<?=
    __(
        '{requester} vorrebbe diventare tuoi amico',
        ['requester' => '<a href="' .$this->Url->build($UserRequester->url, true). '">' .$UserRequester->username. '</a>']
    )
 ?>
</p>


<?php $url = $this->Url->build(['prefix' => null, 'controller' => 'user-friends', 'action' => 'waiting'], true) ?>
<?= $this->Html->link(__('Accetta o rifiuta da questa pagina'), $url) ?>
