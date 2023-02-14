<?php
    $this->assign('title', __('Nuova categoria'));
    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Negozio'), '#');
    $this->Breadcrumbs->add(__('Categorie'), ['action' => 'index']);
    $this->Breadcrumbs->add(__('Nuova'), $this->request->here);
?>

<div class="quizCategories form large-9 medium-8 columns content">
    <?= $this->Form->create($category, ['valueSources' => ['context', 'query']]) ?>
    <fieldset>
        <?php
            echo $this->Form->input('parent_id', [
                'label'   => __('Sotto categoria di'),
                'empty'   => __('-- Nodo principale'),
                'options' => $parentCategories,
            ]);
            echo $this->Form->input('name', [
                'label' => __('Nome categoria'),
                'help'  => __('Traduzioni disponibili nella pagina di modifica'),
            ]);
        ?>
    </fieldset>

    <?php
        echo $this->Form->control('in_homepage', [
            'label' => __d('backend', 'Mostra tra le categorie in home'),
            'help'  => __d('backend', 'Verrà mostrata nella homepage del negozio')
        ]);
    ?>

    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-block btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
