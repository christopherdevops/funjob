<?= __('Ciao @{username}', ['username' => $User->username]) ?>, <br>
<?= __('Per poter accedere a tutte le funzioni di FunJob è necessario che confermi il tuo account cliccando questo link') ?>
<br>
<br>
<?php $url = $this->Url->build(['_name' => 'account:confirmation', 'uuid' => $User->confirmation_token], true) ?>
<?= $this->Html->link($url, $url) ?>
