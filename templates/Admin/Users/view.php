<?php
    $this->assign('title', __d('backend', 'Dettaglio utente: {username}', ['username' => $User->username]));
    $this->assign('header', ' ');

    $this->Html->script([
        '/bower_components/blockUI/jquery.blockUI.js',
    ], ['block' => 'js_foot']);

    $this->Breadcrumbs
        ->add(__d('backend', 'Utenti'), ['action' => 'index'])
        ->add($User->username, $this->request->here);
?>

<?php // CREDITI ?>
<?php $this->start('user:credits:tool') ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php echo __d('backend', 'Aggiungi crediti') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php
                echo $this->Form->create($User->credit, [
                    'url'          => ['controller' => 'UserCredits', 'action' => 'edit'],
                    'valueSources' => ['query', 'context']
                ]);
                echo $this->Form->hidden('id');
                echo $this->Form->control('pixs', [
                    'type'        => 'number',
                    'min'         => 1,
                    'max'         => 1000,
                    'label'       => __('Attuali: {total}', ['total' => $User->credit->total]),
                    'placeholder' => __('Numero crediti'),
                    'help'        => __('Verranno accreditati a questo utente')
                ]);
                echo $this->Form->button(__d('backend', 'Aggiorna'), ['class' => 'btn btn-sm btn-default btn-block']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
<?php $this->end() ?>

<?php // FLAG BIGBRAIN ?>
<?php $this->start('user:bigbrain') ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= __d('backend', 'BigBrain') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php
                echo $this->Form->create($User, ['url' => ['action' => 'bigbrain']]);
                echo $this->Form->hidden('id');
                echo $this->Form->control('is_bigbrain', [
                    'type'  => 'checkbox',
                    'label' => __('Collaboratore?'),
                    'help'  => __d('backend', 'Dal {date}', ['date' => $User->bigbrain_from])
                ]);
                echo $this->Form->button(__('Aggiorna'), ['class' => 'btn btn-sm btn-default btn-block']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
<?php $this->end() ?>

<?php // INFORMAZIONI UTENTE ?>
<?php $this->start('user:vcard') ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= __d('backend', 'Scheda utente') ?>
            </h3>
        </div>
        <div class="panel-body">
            <dl class="">
                <dt></dt>
                <dd class="">
                    <a href="<?= $this->Url->build(['_name' => 'user:profile', 'id' => $User->id, 'username' => $User->slug]) ?>">
                        <h2 class="font-size-md2 no-margin" style="line-height:32px;text-align:middle;">
                            <?= $this->User->avatar($User->avatarSrcDesktop, ['class' => 'img-responsive img-circle pull-left']) ?>
                            <span style="margin-left:5px"></span>

                            <?php if ($User->is_bigbrain) : ?>
                            <i style="color:#00adee" class="fontello-brain"></i>
                            <?php endif ?>
                            <?= $User->username ?>
                        </h2>
                    </a>
                </dd>

                <dt><?= __d('backend', 'Nominativo') ?></dt>
                <dd>
                    <?= $User->fullname ?>
                </dd>

                <dt><?= __d('backend', 'Email') ?></dt>
                <dd>
                    <a href="mailto:<?= $User->email ?>">
                        <?= $User->email ?>
                    </a>
                </dd>

                <dt><?= __d('backend', 'Indirizzo') ?></dt>
                <dd>
                    <?php if (in_array($User->type, ['user', 'admin'])) : ?>
                        <address>
                            <?= $User->account_info->address ?>
                            <br>
                            <?= $User->account_info->live_city ?>
                        </address>
                    <?php else: ?>
                        <address>
                            <?= $User->account_info->address ?>
                            <br>
                            <?= $User->account_info->city ?>
                        </address>
                    <?php endif ?>
                </dd>

                <dt><?= __d('backend', 'Titolo') ?></dt>
                <dd><?= $User->title ?></dd>

                <?php if (!empty($User->account_info->profession)) : ?>
                <dt><?= __d('backend', 'Professione') ?></dt>
                <dd><?= $User->account_info->profession ?></dd>
                <?php endif ?>

                <dt><?= __d('backend', 'Inscritto dal') ?></dt>
                <dd><?= $User->created ?></dd>

                <dt><?= __d('backend', 'Ultimo accesso') ?></dt>
                <dd><?= $User->last_seen ?></dd>
            </dl>
        </div>
        <div class="panel-footer">
            <a href="<?= $this->Url->build($User->url) ?>" class="btn btn-sm btn-default btn-block">
                <?php echo __d('backend', 'Profilo') ?>
            </a>
        </div>
    </div>
<?php $this->end() ?>

<?php // BAN UTENTE ?>
<?php $this->start('user:ban') ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= __d('backend', 'Accesso') ?>
            </h3>
        </div>
        <div class="panel-body">
            <?php
                echo $this->Form->create($User, ['url' => ['action' => 'canlogon']]);
                echo $this->Form->control('can_logon', [
                    'label' => __d('backend', 'Permettere login'),
                    'help'  => __d('backend', 'Non potrà più accedere alla sua area personale (ban)')
                ]);
                echo $this->Form->control('is_disabled', [
                    'label' => __d('backend', 'Disabilita profilo'),
                    'help'  => __d('backend', 'Il profilo non sarà più visibile')
                ]);

                if (in_array($User->type, ['admin', 'user'])) {
                    echo $this->Form->control('type', [
                        'label'   => __d('backend', 'Gruppo utente'),
                        'options' => [
                            'admin' => __d('backend', 'Amministratore'),
                            'user'  => __d('backend', 'Privato')
                        ]
                    ]);
                } else {
                    echo $this->Form->control('type', ['value' => 'company', 'type' => 'hidden']);
                }
                echo $this->Form->button(__d('backend', 'Aggiorna'), ['class' => 'btn btn-sm btn-block btn-danger']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
<?php $this->end() ?>

<div class="page-header">
    <h1 class="font-size-lg2 text-center">
        @<?= $User->username ?>
        <small>
            <?php echo $User->fullname ?>
        </small>
    </h1>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
        <div class="tabs" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                        <?= __d('backend', 'Informazioni') ?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#quizzes" aria-controls="quizzes" role="tab" data-toggle="tab" data-ajax="<?= $this->Url->build(['action' => 'quizzes', $User->id]) ?>">
                        <?php echo __d('backend', 'Giochi creati') ?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#usergroups" aria-controls="groups" role="tab" data-toggle="tab" data-ajax="<?= $this->Url->build(['action' => 'groups', $User->id]) ?>">
                        <?php echo __d('backend', 'Gruppi creati') ?>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <?php echo $this->fetch('user:vcard') ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="quizzes">
                    <noscript>Richiede Javascript ...</noscript>
                </div>
                <div role="tabpanel" class="tab-pane" id="usergroups">
                    <noscript>Richiede Javascript ...</noscript>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <?php echo $this->fetch('user:ban') ?>
        <?php echo $this->fetch('user:bigbrain') ?>
        <?php echo $this->fetch('user:credits:tool') ?>
    </div>
</div>


<script type="text/javascript">
$(function() {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href"); // activated tab
        var _url   = $(this).data("ajax");

        if (_url == undefined) {
            return false;
        }

        var $ajax = $.ajax({
            url: $(this).data("ajax"),
            beforeSend: function() {
                $.blockUI();
            }
        });

        $ajax.done(function(response) {
            $(target).html(response);
        });
        $ajax.always(function(response) {
           $.unblockUI();
        });
    });
});
</script>
