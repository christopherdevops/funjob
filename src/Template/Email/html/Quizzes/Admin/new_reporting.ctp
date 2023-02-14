‹<?= $Quiz->title ?>› ha ricevuto una segnalazione da un utente.

<br>
<dl>
    <dt>Quiz</dt>
    <dd><?= $this->Url->build($Quiz->url, true) ?></dd>

    <dt>Segnalatore:</dt>
    <dd><?= $User->username ?></dd>

    <dt>Motivo:</dt>
    <dd><?= $request['reason'] ?></dd>
</dl>
