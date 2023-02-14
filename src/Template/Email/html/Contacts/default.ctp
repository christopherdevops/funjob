<?php echo __('Ciao, hai ricevuto una richiesta di contatto da parte di un utente funjob.it.') ?> <br>
<?php echo __('Di seguito i dati del contatto:') ?>

<br><br>

<table>
    <tr>
        <td><?= __('Nominativo') ?>:</td>
        <td><?= $formData['fullname'] ?></td>
    </tr>
    <tr>
        <td><?= __('Funjob ID') ?>:</td>
        <dd><?= $formData['user_id'] ?></td>
    </tr>
    <tr>
        <td><?= __('IP') ?>:</td>
        <td><?= $formData['IP'] ?></td>
    </tr>
</table>

<br>
<br>

<blockquote style="border-left:2px solid gray;padding:5px;font-style:italic">
    <?php echo $formData['body'] ?>
</blockquote>

