<?php
    $this->assign('title', __('Collabora con noi'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('BigBrains'), ['_name' => 'bigbrains:index'])
        ->add(__('Contattaci'));
?>

<?php $this->start('css_head--inline') ?>
    .bigbrain-title-icon {margin-right:-5px}
<?php $this->end() ?>

<div class="page-header">
    <h1 class="no-margin">
        <i class="bigbrain-title-icon fontello-brain text-color-primary"></i>
        <span class="text-bold font-size-lg text-color-gray--dark">
            <?= __('Richiesta di collaborazione') ?>
        </span>
    </h1>
</div>

<?php echo $this->Form->create($BigBrainContactForm) ?>
<div class="well well-sm">
    <?php
        echo $this->Form->control('fullname', [
            'label'   => __('Nome Cognome'),
            'default' => (
                $this->request->session()->read('Auth.User.first_name') . ' ' .
                $this->request->session()->read('Auth.User.last_name')
            )
        ]);
        echo $this->Form->control('email', [
            'label'   => __('Dove contattarti'),
            'default' => $this->request->session()->read('Auth.User.email')
        ]);

        echo $this->Form->control('descr', [
            'label'       => __('Presentazione e materie conosciute'),
            'placeholder' => __('Sono specializzato e/o conosco le seguenti materie ...')
        ]);

        echo $this->Recaptcha->display();
        echo '<hr/>';

        echo $this->Form->hidden('ip', ['value' => $this->request->clientIp()]);

        echo $this->Form->hidden('user_id', ['value' => $this->request->session()->read('Auth.User.id')]);
        echo $this->Form->hidden('user_name', ['value' => $this->request->session()->read('Auth.User.username')]);

        echo $this->Form->submit(
            '<i class="fa fa-enveloper text-color-primary"></i>' . __('Invia'),
            ['class' => 'btn btn-primary btn-block', 'escape' => false]
        );
    ?>
</div>
<?php echo $this->Form->end() ?>

