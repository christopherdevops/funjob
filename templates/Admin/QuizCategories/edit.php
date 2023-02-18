<?php
    $this->assign('header', ' ');
    $this->assign('title', __('Edit categoria'));

    $this->Breadcrumbs
        ->add(__('Categorie'), ['action' => 'index'])
        ->add(__('Modifica'))
?>

<div class="form large-9 medium-8 columns content">
    <?= $this->Form->create($quizCategory, ['type' => 'file']) ?>

    <fieldset>
        <legend><?= __('Modifica: Categoria Gioco') ?></legend>
        <?php
            echo $this->Form->control('parent_id', [
                'label'   => __('Sotto categoria di'),
                'empty'   => __('-- Nodo principale'),
                'options' => $parentQuizCategories,
            ]);

            echo $this->Form->control('name', [
                'label' => __('Nome (originale)'),
                'help'  => __('Se non è prevista una traduzione di questo campo (in "Traduzioni") verrà utilizzata questo stringa'),
            ]);
        ?>
    </fieldset>


    <fieldset>
        <legend><?= __('Copertina') ?></legend>
        <img class="img-responsive" src="<?= $quizCategory->imageSize($quizCategory->coverFallback, '300x150') ?>" alt="">

        <?php
            echo $this->Form->control('cover', [
                'type'  => 'file',
                'label' => __d('backend', 'Immagine (mostrata in home)'),
                'help'  => __d('backend', 'Dimensione minima 350x150 pixel')
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
