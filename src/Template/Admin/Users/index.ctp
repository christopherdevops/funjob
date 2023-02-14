<?php
    $this->assign('title', __('Archivio utenti registrati'));

    $this->Breadcrumbs->add(__('Utenti'), $this->request->here);
?>

<style type="text/css">
    #toolbar a:after { color:gray;content:" | ";padding:0 5px 0 5px; }
</style>

<div class="row" id="toolbar">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <a role="button" href="#search-filters" data-toggle="collapse" onclick="return false;">
            <i class="fa fa-search fa-3x"></i>
            <span><?= __('Ricerca utenti') ?></span>
        </a>

        <a role="button" href="<?= $this->Url->build(['action' => 'export', 'user']) ?>">
            <i class="fa fa-download fa-3x"></i>
            <span><?= __('Esporta utenti') ?></span>
        </a>
        <a role="button" href="<?= $this->Url->build(['action' => 'export', 'company']) ?>">
            <i class="fa fa-download fa-3x"></i>
            <span><?= __('Esporta aziende') ?></span>
        </a>

        <a class="text-danger" href="<?= $this->Url->build(['action' => 'clear_cache']) ?>">
            <i class="fa fa-trash fa-3x"></i>
            <span><?= __('Elimina cache Home') ?></span>
        </a>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <?php if (!empty($this->request->getQuery())) : ?>
        <div class="alert alert-sm alert-info">
            <?= __('Stai utilizzando dei filtri... alcuni utenti potrebbero non essere mostrati.') ?>
            <a class="btn btn-sm btn-default" href="<?= $this->request->getUri()->getPath() ?>">
                <i class="fa fa-remove"></i>
                <?= __('Rimuovi filtri') ?>
            </a>
        </div>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div id="search-filters" class="collapse out">
        <?php
            echo $this->Form->create(null);
            $this->Form->setValueSources(['data', 'query']);

            echo $this->Form->control('username', [
                'label' => __('Username')
            ]);

            echo $this->Form->control('fullname', [
                'label' => __('Nome cognome, Nome o Cognome'),
                'help'  => __('Solo per utenti privati'),
                'div' => 'col-md-6'
            ]);
            echo $this->Form->control('name', [
                'label' => __('Ragione sociale'),
                'help'  => __('Solo per utenti aziende'),
                'div' => 'col-md-6'
            ]);

            echo $this->Form->control('email', [
                'type'  => 'email',
                'label' => __('E-mail')
            ]);

            echo $this->Form->control('type', [
                'label' => __('Tipologia utente'),
                'empty' => 'Tutti',
                'options' => [
                    'user'    => __('Privato'),
                    'company' => __('Azienda')
                ]
            ]);

            echo $this->Form->button(__('Cerca'));
            echo $this->Form->end();
        ?>
    </div>
</div>

<hr>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <tr>
            <th class="hidden-xs">

            </th>
            <th><?= __d('backend', 'Account') ?></th>
            <th><?= __d('backend', 'Nominativo') ?></th>
            <th><?= __d('backend', 'Collaboratore') ?></th>
            <th><?= __d('backend', 'Registrato') ?></th>
            <th><?= __d('backend', 'Ultimo accesso') ?></th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr class="">
                <td class="hidden-xs">
                    <a href="<?= $this->Url->build(['action' => 'view', $user->id]) ?>" class="btn btn-default">
                        <i class="fa fa-legal"></i>
                        <?= __d('backend', 'Amministra') ?>
                    </a>
                </td>
                <td>
                    <?php if (in_array($user->type, ['admin', 'user'])) : ?>
                        <span class="fa-stack fa-md">
                            <i class="fa fa-user fa-stack-1x"></i>
                            <?php if (!$user->can_logon) : ?>
                            <i class="fa fa-ban fa-stack-2x text-danger"></i>
                            <?php endif ?>
                        </span>
                    <?php else: ?>
                        <span class="fa-stack fa-md">
                            <i class="fa fa-building fa-stack-1x"></i>
                            <?php if (!$user->can_logon) : ?>
                            <i class="fa fa-ban fa-stack-2x text-danger"></i>
                            <?php endif ?>
                        </span>
                    <?php endif ?>

                    <?=
                        $this->Html->link(
                            $user->username,
                            $user->url
                        )
                    ?>
                </td>
                <td>
                    <?php
                        if (in_array($user->type, ['admin', 'user'])) {
                            echo $user->fullname;
                        } else {
                            echo $user->name;
                        }
                    ?>
                </td>
                <td>
                   <?php if ($user->is_bigbrain) : ?>
                        <i style="text-weigh:bold;color:#00adee" class="fontello-brain"></i>
                        <span class="text-muted font-size-sm">
                            <?= $user->bigbrain_from ?>
                        </span>
                   <?php else: ?>
                        <i style="opacity:0.34;color:#f0f0f0" class="fontello-brain"></i>
                   <?php endif ?>
                </td>
                <td><?= $user->created ?></td>
                <td>
                    <?php
                        if ($user->has('last_seen')) {
                            echo (new \Cake\I18n\Time($user->last_seen))->timeAgoInWords([
                                'format'    => 'd/M/Y',
                                'acciuraty' => 'day',
                                'end'       => '+1 month'
                            ]);
                        } else {
                            echo (new \Cake\I18n\Time($user->created))->timeAgoInWords([
                                'format'    => 'dd/MM/Y',
                                'acciuraty' => 'day',
                                'end'       => '+1 month'
                            ]);
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <?= $this->element('pagination') ?>
</div>



<script>
    $(function() {
        $("*[data-toggle=popover]").popover({
            container: "body"
            trigger  : "hover"
        })
    })
</script>
