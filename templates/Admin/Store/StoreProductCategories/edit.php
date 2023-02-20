<?php
    $this->assign('title', __('Modifica: {name}', ['name' => $category->name]));
    $this->Breadcrumbs->add(__('Negozio'), '#');
    $this->Breadcrumbs->add(__('Categorie'), ['action' => 'index']);
    $this->Breadcrumbs->add(__('Modifica'), $this->request->getAttribute('here'));
?>

<div class="quizCategories form large-9 medium-8 columns content">
    <?= $this->Form->create($category) ?>
    <fieldset>
        <?php
            echo $this->Form->control('parent_id', [
                'label'   => __('Sotto categoria di'),
                'empty'   => __('-- Nodo principale'),
                'options' => $parentCategories,
            ]);

            echo $this->Form->control('name', [
                'label' => __('Nome (originale)'),
                'help'  => __('Se non è prevista una traduzione di questo campo (in "Traduzioni") verrà utilizzata questo stringa'),
            ]);
        ?>
    </fieldset>

    <fieldset>
        <?php
            // echo $this->Form->control('in_homepage_sort', [
            //     'type'  => 'number',
            //     'label' => __d('backend', 'Ordine in home'),
            //     'help'  => __d('backend', 'La categorie in home vengono ordinate in base a questo campo')
            // ]);

            echo $this->Form->control('in_homepage', [
                'label' => __d('backend', 'Mostra tra le categorie in home'),
                'help'  => __d('backend', 'Verrà mostrata nella homepage del negozio')
            ]);
        ?>
    </fieldset>

    <fieldset>
        <legend><?php echo __('Traduzioni') ?></legend>
        <?php
            echo $this->Form->control('_translations.it.name', ['required' => false, 'label' => __('Italiano')]);
            echo $this->Form->control('_translations.en.name', ['required' => false, 'label' => __('Inglese')]);
            echo $this->Form->control('_translations.fr.name', ['required' => false, 'label' => __('Francese')]);
            echo $this->Form->control('_translations.es.name', ['required' => false, 'label' => __('Spagnolo')]);
            echo $this->Form->control('_translations.ru.name', ['required' => false, 'label' => __('Russo')]);
        ?>
    </fieldset>

    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-block btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
