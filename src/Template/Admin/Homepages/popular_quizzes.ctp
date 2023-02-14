<?php
    $this->assign('title', __('Giochi su prima pagina'));

    $this->Breadcrumbs
        ->add(__('Prima pagina'))
        ->add(__('Giochi popolari'));
?>

<?php $this->start('list') ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><?= __('Identificativo') ?></th>
                    <th><?= __('Nome Gioco') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($popularQuizzes as $entity) : $quiz = $entity->_matchingData['Quizzes'] ?>
                    <tr>
                        <td>#<?= $quiz->id ?></td>
                        <td><?= $this->Html->link($quiz->title, $quiz->url) ?></td>
                        <td>
                            <?php
                                echo $this->Form->create($entity, ['url' => ['action' => 'popularQuizzesDelete']]);
                                echo $this->Form->button(
                                    '<i class="fa fa-home"></i>'. __('Elimina'),
                                    ['class' => 'btn btn-xs btn-danger']
                                );
                                echo $this->Form->end();
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                <?php if ($popularQuizzes->isEmpty()) : ?>
                <tr>
                    <td colspan="12">
                        <p class="text-warning text-center">
                            <?= __d('backend', 'Nessun gioco mostrato in home') ?>
                        </p>
                    </td>
                </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <?php echo $this->element('pagination') ?>
<?php $this->end() ?>


<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                <i class="fa fa-home"></i>
                <?= __('In prima pagina') ?>
            </a>
        </li>
        <li role="presentation">
            <a href="<?= $this->Url->build(['controller' => 'Quizzes', 'action' => 'index']) ?>" aria-controls="search" role="tab">
                <i class="fa fa-search"></i>
                <?= __('Ricerca') ?>
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
            <?php echo $this->fetch('list') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="search">
            <?php echo $this->fetch('search') ?>
        </div>
    </div>
</div>
