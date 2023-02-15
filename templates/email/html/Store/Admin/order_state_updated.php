Ciao <?= $order->user->username ?>, <br>
L'ordine in oggetto è stato modificato in "<?= $order->status ?>"
<br>
<br>
<?php if ($order->note) : ?>
    <p><?= $order->note ?></p>
<?php endif ?>


<dl>
    <dt><?= __('Oggetto richiesto') ?></dt>
    <dd><?= $order->product->name ?></dd>

    <dt><?= __('Richiesto in data') ?></dt>
    <dd><?= $order->created ?></dd>
<dl>
