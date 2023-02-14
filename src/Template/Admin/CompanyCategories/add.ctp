<?php
    $this->assign('header', ' ');
?>

<div class="form large-9 medium-8 columns content">
    <?= $this->Form->setValueSources(['query'])->create($Category) ?>
    <fieldset>
        <legend><?= __d('backend', 'Nuovo Settore aziendale') ?></legend>
        <?php
            echo $this->Form->control('parent_id', [
                'label'   => __('Sotto categoria di'),
                'empty'   => __('-- Nodo principale'),
                'options' => $parents,
            ]);
            echo $this->Form->control('name', [
                'label' => __('Nome categoria'),
                'help'  => __('Traduzioni disponibili nella pagina di modifica'),
            ]);
        ?>
    </fieldset>

    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-block btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
