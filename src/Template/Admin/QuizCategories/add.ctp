<?php
    $this->assign('header', ' ');
    $this->assign('title', 'Nuova categoria gioco');

    $this->Breadcrumbs
        ->add(__('Categorie'), ['action' => 'index'])
        ->add(__('Nuova'));
?>


<div class="row">
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <div class="well well-sm">
            <?php
                echo $this->Form->create(null, ['valueSources' => ['context', 'query']]);
                echo $this->Form->hidden('_filter', ['value' => true]);
                echo $this->Form->control('term', [
                    'label' => __('Termine di ricerca'),
                    'help'  => 'Trascrivi la categoria da ricercare'
                ]);
                echo $this->Form->button(__('Cerca'), ['class' => 'btn btn-default btn-block']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

        <?php if (!empty($paths)) : ?>
        <div class="well well-sm">
            <ul class="list-inline">
            <?php foreach ($paths as $path) : ?>
                <li><a href="<?= $this->Url->build(['?' => ['children' => $path->id]]) ?>"><?= $path->name ?></a></li>
                <span>></span>
            <?php endforeach ?>
            </ul>
        </div>
        <?php endif ?>

        <ul class="list-group">
            <li class="list-group-item disabled">
                <?php
                    if ($this->request->getQuery('term')) {
                        echo __('Risultati di ricerca');
                    } elseif ($this->request->getQuery('parent_id')) {
                        echo __('Figli della categoria selezionata: click sul nome per mostrare le sotto categorie');
                    } else {
                        echo __('Click sul nome della categoria per mostrare le sotto categorie');
                    }
                ?>
            </li>

            <?php if ($nodes->isEmpty()) : ?>
            <li class="list-group-item">
                <?php if ($this->request->getQuery('term')) : ?>
                    <p class="text-center text-muted"><?= __('Nessuna categoria trovata') ?></p>
                <?php elseif ($this->request->getQuery('parent_id')) : ?>
                    <p class="text-center text-muted"><?= __('Non ci sono sotto-categorie') ?></p>
                <?php else: ?>
                    <p class="text-center text-muted"><?= __('Nessuna categoria creata') ?></p>
                <?php endif ?>
            </li>
            <?php endif ?>

            <?php foreach ($nodes as $category) : ?>
            <li class="list-group-item">
                <input type="radio" name="category" value="<?= $category->id ?>" />

                <a href="<?= $this->Url->build(['?' => ['children' => $category->id]]) ?>">
                    <?= $category->name ?>
                </a>
            </li>
            <?php endforeach ?>

        </ul>
    </div>
</div>
<script type="text/javascript">
    $("body").on("click", "input[name='category']", function(evt) {
        $("#parent-id").val($(this).val());
    });
</script>

<div class="quizCategories form large-9 medium-8 columns content">
    <?= $this->Form->create($Category) ?>
    <fieldset>
        <legend><?= __d('backend', 'Nuova Categoria') ?></legend>
        <?php
            echo $this->Form->control('parent_id', [
                'type'     => 'text',
                'label'    => __('Sotto categoria di'),
                'value'    => $this->request->getQuery('parent_id', null),
                'default'  => null,
                'readonly' => 'readonly',
                'help'     => __('Seleziona la categoria attraverso lo strumento di ricerca sovrastante, selezionando il pallino')
            ]);

            // echo $this->Form->input('parent_id', [
            //     'label'   => __('Sotto categoria di'),
            //     'empty'   => __('-- Nodo principale'),
            //     'options' => $parentQuizCategories,
            //     'default' => '',
            // ]);
            echo $this->Form->input('name', [
                'label' => __('Nome categoria'),
                'help'  => __('Traduzioni disponibili nella pagina di modifica'),
            ]);
        ?>
    </fieldset>

    <?= $this->Form->button(__('Crea'), ['class' => 'btn btn-block btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
