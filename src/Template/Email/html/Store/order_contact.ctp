<p><?= __('L\'utente {name} ha richiesto un contatto per l\'ordine N° ', ['name' => $Order->user->username]) ?></p>

<dl>
    <dt><?= __('Ordine') ?></dt>
    <dd><?= $Order->id ?></dd>

    <dt><?= __('Prodotto:') ?></dt>
    <dd><?= $Order->product->name ?></dd>
</dl>

<p><?= __('Motivazione del contatto') ?>:</p>
<strong><?= $requestData['subject'] ?></strong>

<p><?= __('Nota dell\'utente') ?></p>
<cite><?= $requestData['body'] ?></cite>
