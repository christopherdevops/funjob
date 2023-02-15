<?= __('Ciao @{username}', ['username' => $User->username]) ?>, <br>
<?= __('Hai richiesto di ripristinare le tue credenziali d\'accesso a funjob.it, segui il link di seguito') ?>
<br>
<br>
<?php $url = $this->Url->build(['_name' => 'account:reset', 'uuid' => $User->recovery_token], true) ?>
<?= $this->Html->link($url, $url) ?>
