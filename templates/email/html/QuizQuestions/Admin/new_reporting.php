‹<?= $Question->quiz->title ?>› ha ricevuto una segnalazione da un utente.

<br>
<dl>
    <dt>Quiz</dt>
    <dd><?= $this->Url->build($Question->quiz->url, true) ?></dd>

    <dt>Segnalatore:</dt>
    <dd><?= $User->username ?></dd>

    <dt>Domanda:</dt>
    <dd><?= $Question->question ?> (id: <?= $Question->id ?>)</dd>

    <dt>Risposta corretta:</dt>
    <dd><?= $Question->quiz_answers[0]->answer ?> (id: <?= $Question->quiz_answers[0]->id ?>)</dd>

    <dt>Motivo:</dt>
    <dd><?= $request['reason'] ?></dd>
</dl>
