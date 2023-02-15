<?php
    $this->assign('title', __('I tuoi quiz'));

    $this->Breadcrumbs
        ->add($UserAuth->username, ['_name' => 'me:dashboard'])
        ->add(__('I miei quiz'));
?>

<?php $this->append('css_head--inline') ?>
    .list-group-item {padding:5px !important;}
    .list-group-item h4 {margin:0;}
    .contextual-buttons .btn {
        min-width:80px;
    }
<?php $this->end() ?>

<?php $this->start('quiz:filters') ?>
    <?php
        echo $this->Form->create(null, [
            'valueSources' => ['query', 'context']
        ]);

        echo $this->Form->control('term', [
            'label'       => false,
            'placeholder' => __('Nome quiz')
        ]);

        // echo $this->Form->control('status', [
        //     'label'   => __d('backend', 'Stato quiz'),
        //     'empty'   => __('Tutti'),
        //     'options' => [
        //         'draft'       => __('Allestimento (domande non raggiunte, o non ancora pubblicato)'),
        //         'published'   => __('Giocabili (domande raggiunte e in archivio)'),
        //         'disabled'    => __('Non giocabili (disabilitate da admin)')
        //     ]
        // ]);

        echo $this->Form->control('type', [
            'label'       => false,
            'empty'       => __('Ogni tipologia'),
            'options'     => [
                'default' => __('Quiz normale'),
                'funjob'  => __('Quiz certificato FunJob')
            ]
        ]);
        echo $this->Form->button(__d('backend', 'Filtra'), ['class' => 'btn btn-sm btn-primary']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<?php $this->start('loop') ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <tr>
                <th class="text-center"><?= __d('backend', 'Tipologia') ?></th>
                <th><?= __d('backend', 'Nome') ?></th>
                <th><?= __d('backend', 'Domande') ?></th>
                <th><?= __d('backend', 'Amministra') ?></th>
            </tr>

            <?php if (!$quizzes->isEmpty()) : ?>
                <?php foreach ($quizzes as $quiz) : ?>
                <tr>
                    <!--
                    <td class="text-center" style="vertical-align:middle;">
                        <?php if ($quiz->status == 'draft') : ?>
                            <span class="fa-stack fa-md">
                              <i class="text-muted fa fa-gamepad fa-stack-1x"></i>
                              <i class="fa fa-times fa-stack-1x text-info"></i>
                            </span>
                            <br>
                            <?php echo __d('backend', 'allestimento') ?>
                        <?php elseif ($quiz->status == 'published') : ?>
                            <span class="fa-stack fa-md">
                              <i class="text-success fa fa-gamepad fa-stack-1x"></i>
                              <i class="fa fa-circle-thin fa-stack-2x text-success"></i>
                            </span>

                            <br>
                            <?php echo __d('backend', 'Giocabile') ?>
                        <?php elseif ($quiz->status == 'unpublished' || $quiz->status == 'hidden'): ?>
                            <span class="fa-stack fa-md">
                              <i class="text-muted fa fa-gamepad fa-stack-1x"></i>
                              <i class="fa fa-ban fa-stack-2x text-danger"></i>
                            </span>
                            <br>
                            <?php echo __d('backend', 'Non giocabile') ?>
                        <?php endif ?>
                    </td>
                    -->

                    <td class="text-center">
                        <?php if ($quiz->type == 'default') : ?>
                            <i style="color:#5F5F5F;font-size:20px;padding:0;margin:0" class="fa fa-user"></i>
                        <?php else: ?>
                            <i style="color:#00adee;font-size:20px;padding:0;margin:0" class="fontello-brain"></i>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($quiz->status == 'published') : ?>
                        <a href="<?= $this->Url->build($quiz->url) ?>">
                            <?= h($quiz->title) ?>
                            <?php //echo str_repeat('*', (100 - strlen($quiz->title))) ?>
                        </a>
                        <?php else: ?>
                            <?= h($quiz->title) ?>
                        <?php endif ?>
                    </td>
                    <td><?= $quiz->published_questions ?></td>

                    <td>
                        <a class="btn btn-default btn-xs" href="<?= $this->Url->build(['_name' => 'quiz:edit', $quiz->id]) ?>">
                            <i class="fa fa-pencil"></i>
                            <?= __d('backend', 'Modifica') ?>
                        </a>
                    </td>

                </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" class="text-muted text-center">
                        <?= __d('backend', 'Nessun gioco da mostrare') ?>
                    </td>
                </tr>
            <?php endif ?>

        </table>
    </div>
<?php $this->end() ?>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $this->fetch('quiz:filters') ?>
                <hr>
            </div>
        </div>

        <div class="row">

            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php $_status = $this->request->getParam('pass.0', 'published') ?>
                    <li role="presentation" class="<?= $_status == 'published' ? 'active ' : '' ?>">
                        <a href="<?= $this->Url->build([0 => 'published']) ?>" aria-controls="Giocabili" role="tab">
                            <?= __('Giocabili') ?>
                        </a>
                    </li>
                    <li role="presentation" class="<?= $_status == 'draft' ? 'active ' : '' ?>">
                        <a href="<?= $this->Url->build([0 => 'draft']) ?>" aria-controls="tab" role="tab">
                            <?= __('Bozze') ?>
                        </a>
                    </li>

                    <?php
                    /*
                    <li role="presentation" class="<?= $_status == 'hidden' ? 'active ' : '' ?>">
                        <a href="<?= $this->Url->build([0 => 'hidden']) ?>" aria-controls="tab" role="tab">
                            <?= __('Nascosti o Bannati') ?>
                        </a>
                    </li>
                    */
                    ?>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="Giocabili">
                        <?php echo $this->fetch('loop') ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php echo $this->element('pagination') ?>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('.popover-btn').popover({
            container : "body",
            trigger   : "hover",
            placement : "bottom"
        });
    })
</script>
