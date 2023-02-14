<div class="page-header">
    <h4>L'utente <?= $order->user->username ?> ha effettuato un NUOVO ordine da funjob.it</h4>
</div>

<table>
    <tr>
        <td>Ordine N°</td>
        <td><?= $order->id ?></td>
    </tr>
    <tr>
        <td>Effettuato il</td>
        <td><?= $order->created ?></td>
    </tr>
    <tr>
        <td>Articolo</td>
        <td><?= $order->product->name ?></td>
    </tr>
</table>

Per visualizzare ulteriore dettagli, clicca
<a href="<?= $this->Url->build(['_name' => 'store:admin:order:view', 'id' => $order->id, '_full' => true]) ?>">qui</a>
