
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= __('Nominativo') ?></th>
                <th><?= __('Telefono') ?></th>
                <th><?= __('E-mail') ?></th>

                <th><?= __('Indirizzo') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $SponsorAdv->billing_name ?></td>
                <td><?= $SponsorAdv->billing_phone ?></td>
                <td><?= $SponsorAdv->billing_email ?></td>

                <td>
                    <?= $SponsorAdv->billing_address ?>
                    <small>
                        <?= $SponsorAdv->billing_city .' '. $SponsorAdv->billing_state .' '. $SponsorAdv->billing_cap ?>
                    </small>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<?php
    echo $this->Form->create($SponsorAdv);

    // TODO:
    // Permettere di cambiare title/descr
    echo $this->Form->control('title', [
        'label'    => __('Title'),
        'max'      => 100
    ]);
    echo $this->Form->control('descr', [
        'label'    => __('Descrizione'),
        'max'      => 150
    ]);

    echo $this->Form->control('impression_lefts', [
        'label' => __('Impressioni totali'),
        'help'  => __('Visualizzazioni rimanenti')
    ]);

    echo $this->Form->control('amount', [
        'label' => __('Prezzo'),
        'help'  => __('Prezzo complessivo')
    ]);


    echo $this->Form->control('active_from', [
        'label' => __('Validità dal'),
        'help'  => __('Data in sarà visualizzato l\'annuncio')
    ]);
    echo $this->Form->control('active_to', [
        'label' => __('Validità al'),
        'help'  => __('Ultimo giorno in cui verrà visualizzato l\'annuncio')
    ]);

    echo $this->Form->button(__('Aggiorna'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>
