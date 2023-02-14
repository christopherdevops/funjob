<?php
    $this->assign('title', __('Ordine N° {0}', $order->id));

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:admin:order:index']);
    $this->Breadcrumbs->add(__('Ordini'), ['_name' => 'store:admin:order:index']);
    $this->Breadcrumbs->add('#' .$order->id, $this->request->here);
?>


<div class="row">
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a href="<?= $this->Url->build(['_name' => 'store:product:view', 'id' => $order->product->id, 'slug' => $order->product->slug]) ?>">
                        <h1>
                            <?= $order->product->name ?>
                        </h1>
                    </a>
                </h3>

                <?php if ($order->product->qty <= 0): ?>
                <span class="font-size-sm text-danger">
                    <?= __d('backend', 'Quantità rimanente {qty}', ['qty' => $order->product->qty]) ?>
                </span>
                <?php elseif ($order->product->qty < 5) : ?>
                <span class="font-size-sm text-warning">
                    <?= __d('backend', 'Quantità rimanente {qty}', ['qty' => $order->product->qty]) ?>
                </span>
                <?php else: ?>
                <span class="font-size-sm text-muted">
                    <?= __d('backend', 'Quantità rimanente {qty}', ['qty' => $order->product->qty]) ?>
                </span>
                <?php endif ?>

            </div>
            <div class="panel-body">

                <?php if ($order->status == 'pending') : ?>
                    <div class="alert alert-info">
                        <?= __d('backend', 'Questo ordine è in attesa di essere evaso') ?>
                    </div>
                <?php endif ?>

                <?php if (empty($order->user->account_info->address)) : ?>
                    <div class="alert alert-warning">
                        <strong><?= __d('backend', 'Questo utente non ha dichiarato nessun indirizzo') ?></strong>
                        <p><?= __d('backend', 'Puoi richiedere l\'indirizzo tramite il box sottostante se necessario') ?></p>
                    </div>
                <?php endif ?>

                <div class="well">
                    <?php
                        echo $this->Form->create($message);
                        echo $this->Form->control('to', [
                            'label'   => 'Destinatario',
                            'default' => $order->user->email,
                            'help'    => __('Indirizzo utente specificato in fase di registrazione')
                        ]);
                        echo $this->Form->control('subject', [
                            'label'   => 'Soggetto',
                            'default' => __('[funjob.it] Ordine: {0} (ordine n°{1})', $order->product->name, $order->id)
                        ]);
                        echo $this->Form->control('body', [
                            'label'       => __('Comunicazione a cliente'),
                            'placeholder' => '',
                            'type'        => 'textarea',
                            'help'        => __('Comunica il codice coupon attraverso questa area')
                        ]);
                        echo $this->Form->button(__('Invia email'), ['class' => 'btn btn-block btn-default']);
                        echo $this->Form->end();
                    ?>
                </div>

            </div>
        </div>

    </div>

    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= __d('backend', 'Richiesto da') ?></h3>
            </div>
            <div class="panel-body">
                <dl class="">

                    <dt></dt>
                    <dd class="">
                            <h2 class="font-size-md2 no-margin" style="line-height:32px;text-align:middle;">
                                <?= $this->User->avatar($order->user->avatarSrcDesktop, ['class' => 'img-responsive img-circle pull-left']) ?>
                                <span style="margin-left:5px"></span>

                                <?php if ($order->user->is_bigbrain) : ?>
                                <i style="color:#00adee" class="fontello-brain"></i>
                                <?php endif ?>
                                <?= $order->user->username ?>
                            </h2>

                            <a href="<?= $this->Url->build($order->user->url) ?>">
                                <i class="fa fa-user"></i>
                                <?= __('Profilo') ?>
                            </a>

                            <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'users', 'action' => 'view', 0 => $order->user->id]) ?>">
                                <i class="fa fa-user"></i>
                                <?= __('Profilo (admin)') ?>
                            </a>
                    </dd>

                    <hr>
                    <dt><?= __d('backend', 'Nominativo') ?></dt>
                    <dd>
                        <?= $order->user->fullname ?>
                    </dd>

                    <dt><?= __d('backend', 'Indirizzo') ?></dt>
                    <dd>
                        <?php if (in_array($order->user->type, ['user', 'admin'])) : ?>
                            <address>
                                <?= $order->user->account_info->address ?>
                                <br>
                                <?= $order->user->account_info->live_city ?>
                            </address>
                        <?php else: ?>
                            <address>
                                <?= $order->user->account_info->address ?>
                                <br>
                                <?= $order->user->account_info->city ?>
                            </address>
                        <?php endif ?>
                    </dd>

                    <dt><?= __d('backend', 'Titolo') ?></dt>
                    <dd><?= $order->user->title ?></dd>

                    <?php if (!empty($order->user->account_info->profession)) : ?>
                    <dt><?= __d('backend', 'Professione') ?></dt>
                    <dd><?= $order->user->account_info->profession ?></dd>
                    <?php endif ?>

                    <dt><?= __d('backend', 'Registrato dal') ?></dt>
                    <dd><?= $order->user->created ?></dd>

                    <dt><?= __d('backend', 'Ultimo accesso') ?></dt>
                    <dd><?= $order->user->last_seen ?></dd>
                </dl>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= __d('backend', 'Ordine') ?></h3>
            </div>
            <div class="panel-body">
                <dl class="">
                    <dt><?= __d('backend', 'Creato il') ?></dt>
                    <dd><?= $order->created ?></dd>

                    <dt><?= __d('backend', 'Importo') ?></dt>
                    <dd><?= $order->amount ?> PIXs</dd>
                </dl>
            </div>
        </div>

        <?php if ($order->status == 'pending') : ?>
        <div class="well">
            <?php
                echo $this->Form->create($order, ['url' => ['action' => 'edit', 0 => $order->id]]);
                echo $this->Form->control('id', ['value' => $order->id, 'type' => 'hidden']);
                echo $this->Form->control('status', [
                    'type'     => 'select',
                    'options'  => [
                        'pending'   => __('Attesa'),
                        'completed' => __('Completato'),
                        'rejected'  => __('Rifiutato')
                    ],
                    'default'  => $order->status,
                    'disabled' => ['pending'],
                    'label'    => __d('backend', 'Stato ordine')
                ]);
                echo $this->Form->control('note', [
                    'type' => 'textarea',
                    'help' => __d('backend', 'Ciò che scrivi in questo campo verrà comunicato al cliente finale a mezzo email')
                ]);
            ?>
            <hr>
            <div class="alert alert-sm alert-warning">
                <div class="text-center">
                    <i class="fa fa-warning fa-2x"></i>
                    <span class="text-bold">
                        <?php echo __('Attenzione') ?>
                    </span>
                </div>
                <hr>

                <?php echo __('Se rifiuti un ordine, è necessario:') ?>

                <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'Users', 'action' => 'view', 0 => $order->user->id, '?' => ['pixs' => $order->amount]]) ?>">
                    1. <?= __('Restituire i {amount} PIX all\'utente', ['amount' => $order->amount]) ?>
                </a>
                <br>
                <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'StoreProducts', 'action' => 'edit', 0 => $order->product->id]) ?>">
                    2. <?= __('Incrementare la quantità dell oggetto') ?>
                </a>

            </div>
            <?php
                echo $this->Form->button(__d('backend', 'Cambia stato'), ['class' => 'btn btn-primary btn-block']);
                echo $this->Form->end()
            ?>
        </div>
        <?php endif ?>

    </div>
</div>
