<?php
    $this->assign('title', __('Modifica: {subject}', ['subject' => $Category->name]));
    $this->Breadcrumbs
        ->add(__('Settori aziendali'), ['action' => 'index'])
        ->add(__('Modifica'), $this->request->here);
?>

<div class="form large-9 medium-8 columns content">
    <?= $this->Form->create($Category) ?>
    <fieldset>
        <?php
            echo $this->Form->control('parent_id', [
                'label'   => __('Sotto categoria di'),
                'empty'   => __('-- Nodo principale'),
                'options' => $parents,
            ]);

            echo $this->Form->control('name', [
                'label' => __('Nome (originale)'),
                'help'  => __(
                    'Se non è prevista una traduzione di questo campo (in "Traduzioni") verrà utilizzata questo stringa'
                ),
            ]);
        ?>
    </fieldset>

    <fieldset>
        <legend><?php echo __('Traduzioni') ?></legend>
        <?php
            echo $this->Form->input('_translations.it.name', ['required' => false, 'label' => __('Italiano')]);
            echo $this->Form->input('_translations.en.name', ['required' => false, 'label' => __('Inglese')]);
            echo $this->Form->input('_translations.fr.name', ['required' => false, 'label' => __('Francese')]);
            echo $this->Form->input('_translations.es.name', ['required' => false, 'label' => __('Spagnolo')]);
            echo $this->Form->input('_translations.ru.name', ['required' => false, 'label' => __('Russo')]);
        ?>
    </fieldset>

    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-block btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
