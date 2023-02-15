<?php
    use Cake\Core\Configure;
    use Cake\Utility\Hash;

    $this->assign('title', __('Contattaci'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Contattaci'));
?>

<div class="page-header">
    <i class="fa fa-envelope fa-2x text-color-primary"></i>
    <div class="text-bold display-inline-block font-size-lg text-color-gray--dark">
        <?php echo __('Contatti') ?>
    </div>
</div>


<?php
    echo $this->Form->create($Form, ['valueSources' => ['query', 'context']]);
    echo $this->Form->hidden('user_id', ['value' => $this->request->getSession()->read('Auth.User.id')]);
?>
<div class="well well-sm">
    <fieldset>
        <legend><?php echo __('Mittente') ?></legend>
        <?php
            echo $this->Form->control('fullname', [
                'label'   => __('Nominativo'),
                'default' => $this->request->getSession()->read('Auth.User.first_name') .' '. $this->request->getSession()->read('Auth.User.last_name')
            ]);

            echo $this->Form->control('from', [
                'label' => __('Indirizzo e-mail'),
                'default' => $this->request->getSession()->read('Auth.User.email')
            ]);
        ?>
    </fieldset>
</div>

<div class="well well-sm">
    <fieldset∂>
        <legend><?php echo __('Richiesta') ?></legend>
        <?php
            $values = [];
            foreach (Configure::readOrFail('funjob.contacts') as $type => $typeConfig) {
                $values[ $type ] = $typeConfig['subject'];
            }

            echo $this->Form->control('type', [
                'label'   => __('Tipologia richiesta'),
                'options' => $values
            ]);

            echo $this->Form->control('subject', [
                'label' => __('Oggetto')
            ]);

            echo $this->Form->control('body', [
                'label' => __('Testo'),
                'type'  => 'textarea'
            ]);
        ?>
    </fieldset>
</div>

<div class="well well-sm">
    <?php echo $this->Recaptcha->display(); ?>

    <?php if ($this->request->is('post') && $this->Form->hasError('g-recaptcha-response')) : ?>
    <span class="text-bold text-danger">
        <?= __('Verifica richiesta') ?>
    </span>
    <?php endif ?>
</div>

<?php
    echo $this->Form->button(__('Invia'), ['class' => 'btn btn-sm btn-primary']);
    echo $this->Form->end();
?>
