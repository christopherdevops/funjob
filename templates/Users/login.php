<?php
    $this->assign('title', __('Accedi a FunJob'));
    $this->assign('header', ' ');
    //$this->assign('breadcrumb', ' ');

    $this->Breadcrumbs
        ->add(__('Accedi a FunJob'));
?>

<?php $this->Flash->render('auth') ?>

<div class="row row-eq-height">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-key"></i>
                    <?= __('Accedi a FunJob') ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php
                    echo $this->Form->create();
                    echo $this->Form->control('username', [
                        'label'       => __('Nome utente'),
                        'placeholder' => 'mario.rossi'
                    ]);
                    echo $this->Form->control('password', [
                        'label'       => __('Password'),
                        'placeholder' => '****'
                    ]);
                ?>
                <hr>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <?php
                            echo $this->Form->button(
                                '<strong>' .__('Accedi'). '</strong>', [
                                'type'  => 'submit',
                                'class' => 'btn btn-sm btn-primary btn-block',
                                'escape' => false
                            ]);
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <a class="btn btn-sm btn-block btn-default" href="<?= $this->Url->build(['_name' => 'account:recovery']) ?>">
                            <?= __('Password dimenticata') ?>
                        </a>
                    </div>
                </div>

                <?php
                    echo $this->Form->end();
                ?>
            </div>
        </div>


        <?= $this->Form->end() ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-user"></i>
                    <span><?= __('Non sei registrato?') ?></span>
                </h3>
            </div>
            <div class="panel-body">
                <p class="font-size-md2">
                    <?= __('Registrati a FunJob per sbloccare alcune funzionalità') ?>
                </p>

                <ul style="list-style:none;" class="fa-ul">
                    <li class="font-size-md">
                        <i class="text-color-primary fa-li fa fa-1x fa-id-card-o"></i>
                        <?= __('Profilo personale con CV') ?>
                    </li>
                    <li class="font-size-md">
                        <i class="text-color-primary fa-li fa fa-1x fa-history"></i>
                        <?= __('Punteggi sui quiz svolti') ?>
                    </li>
                    <li class="font-size-md">
                        <i class="text-color-primary fa-li fa fa-1x fa-handshake-o"></i>
                        <?= __('Essere trovato da utenti e da aziende (per offerte lavorative)') ?>
                    </li>
                    <li class="font-size-md">
                        <i class="text-color-primary fa-li fa fa-1x fa-dollar"></i>
                        <?= __('Guadagnare creando quiz o giocando quelli altrui') ?>
                    </li>
                    <li class="font-size-md">
                        <i class="text-color-primary fa-li fa fa-1x fa-cart-plus"></i>
                        <?=
                            __('Convertire i PIX guadagnati in premi')
                        ?>
                    </li>

                    <li class="font-size-md">
                        <i class="text-color-primary fa-li fa fa-1x fa-comments"></i>
                        <?=
                            __('Sfidare altri utenti e partecipare ai tornei')
                        ?>
                    </li>

                    <li style="margin-left:-17px;">
                        <span class="text-color-primary text-bold"><?= __('E tanto altro ancora ...') ?></span>
                    </li>
                </ul>

                <?php
                    echo $this->Html->link(
                        '<strong>'. __('Registrati... è gratis!') . '</strong>',
                        ['_name' => 'auth:register'],
                        ['class' => 'btn btn-sm btn-block btn-primary', 'escape' => false]
                    )
                ?>
            </div>
        </div>

    </div>
</div>
