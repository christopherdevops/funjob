<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Curriculum Vitae'));
$this->assign('subtitle', __('Permetti alle aziende di visualizzare le tue competenze tecniche'));
$this->assign('tab', 'cv');
?>

<?php if (!empty($User->account_info->cv)) : ?>
    <div class="clearfix">
        <a class="btn btn-xs btn-default" href="<?= $this->Url->build($User->account_info->cvUrl) ?>" target="_blank">
            <span class="fa-stack fa-md">
                <i class="fa fa-file-pdf-o fa-stack-1x"></i>
            </span>
            <br>
            <?= __('Visualizza') ?>
        </a>

        <a class="btn btn-xs btn-default" href="<?= $this->Url->build(['_name' => 'cv:delete']) ?>">
            <span class="fa-stack fa-md">
                <i class="fa fa-file-pdf-o fa-stack-1x"></i>
              <i class="fa fa-ban fa-stack-2x text-danger"></i>
            </span>
            <br>
            <?= __('Elimina') ?>
        </a>
        <?php
            // FUTURE:
            // Qui si è già dentro un <form> (nested forms) e non è possibile usare questo approccio
            // perchè invia tutti i dati del form (e dà naturalmente errore il SecurityComponent) dicendo
            // che alcuni dati non sono attesi

            // echo $Form->create($User->account_info, ['url' => ['_name' => 'cv:delete']]);
            // echo $Form->control('id');
            // echo $Form->button(
            //     __('Elimina'),
            //     ['class' => 'btn btn-xs btn-danger']
            // );
            // echo $Form->end();
        ?>
    </div>
    <hr>
<?php endif ?>

<?php
// Il campo cover viene impostato tramite un blockView
// Il FormHelper sembra che non lo aggiunge nella lista dei campi permessi a quanto pare
$this->Form->unlockField('account_info.cv');

echo $this->Form->control('account_info.cv', [
    'type'  => 'file',
    'label' => __('Carica il tuo CV (solo in PDF)'),
    'help'  => __('Permetti alle aziende di visualizzare il tuo CV, per proporti offerte di lavoro')
]);
?>

<?php
echo $this->Form->control('account_info.public_cv', [
    'type'  => 'checkbox',
    'label' => __('Rendi CV consultabile a tutti (pubblico)'),
    'help'  => __(
        '<span class="text-warning text-bold">Se il CV non è pubblico, dovrai autorizzare ogni utente che vuole visualizzare il tuo CV da {0}</span>',
        $this->Html->link(__('questa pagina'), ['_name' => 'cv:authorizations:archive'])
    )
]);
