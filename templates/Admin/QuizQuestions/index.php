<?php
    $this->assign('title', __('Ricerca domande'));
?>

<?php if (!$this->request->is('ajax')) : ?>
    <?php
        // Select2Tree (https://github.com/lonlie/select2tree)
        // $this->Html->script([
        //     '/bower_components/select2/dist/js/select2.min.js',
        //     'select2tree'
        // ], ['block' => 'js_foot']);
        // $this->Html->css([
        //     '/bower_components/select2/dist/css/select2.min.css',
        //     'https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.6/select2-bootstrap.min.css'
        // ], ['block' => 'css_foot']);
    ?>

    <?php
        $this->Html->script([
            '/bower_components/select2/dist/js/select2.min.js'
        ], ['block' => 'js_foot']);
        $this->Html->css([
            '/bower_components/select2/dist/css/select2.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.6/select2-bootstrap.min.css'
        ], ['block' => 'css_foot']);
    ?>
<?php endif ?>

<style>
    .highlight {
        background: rgba(255, 230, 0, 0.5);
        padding: 3px 5px;
        margin: -3px -5px;
        line-height: 1.7;
        border-radius: 5px;
        display:inline-block;
    }

    .funjob-questions-modal .modal-dialog {
        width:90% !important;
        overflow:auto !important;
    }

    .dl-horizontal dt {
        margin:0 !important;
        max-width:20px;
        text-align:left;
    }
    .dl-horizontal dd {margin:0 !important;}

    .select2-container--default .select2-results>.select2-results__options{max-height: 400px;}
    @media only screen and (min-height: 800px) {
        .select2-container--default .select2-results>.select2-results__options{max-height: 500px;}
    }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-lg">
            <?php
                echo $this->Form->create(null, [
                    'class'        => 'js-filter-form',
                    'valueSources' => ['query', 'context']
                ]);
                echo $this->Form->control('term', [
                    'label'       => false,
                    'placeholder' => __d('backend', 'Termine di ricerca'),
                    'help'        => __d('backend', 'Viene ricercato nella domanda e nelle risposte (solo normali)')
                ]);
                echo $this->Form->control('category', [
                    'class'    => 'select2',
                    'label'    => false,
                    'type'     => 'select',
                    'options'  => $categories,
                    'empty'    => __d('backend', 'Tutte le categorie'),
                    //'multiple' => 'multiple'
                ]);
                echo $this->Form->control('complexity', [
                    'label'   => false,
                    'help'    => __d('backend', 'Complessità domanda'),
                    'empty'   => __d('backend', 'Tutte'),
                    'options' => [
                        'easy'   => __('Facili'),
                        'medium' => __('Medie'),
                        'hard'   => __('Difficili')
                    ]
                ]);

                echo $this->Form->hidden('quiz_id', ['value' => $this->request->getQuery('quiz_id')]);
                echo $this->Form->button(__d('backend', 'Filtra'), ['class' => 'js-filter-submit-btn btn btn-default']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
</div>


<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="col-md-1"></th>
                <th class="col-md-5"><?= __d('backend', 'Domanda') ?></th>
                <th class="col-md-3"><?= __d('backend', 'Risposte') ?></th>
                <th class="col-md-1"><?= __d('backend', 'Difficoltà') ?></th>
                <th class="col-md-3"><?= __d('backend', 'Quiz') ?></th>
                <th class="col-md-1"><?= __d('backend', 'Autore') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $question) : ?>
            <tr>
                <td>
                    <?php
                        echo $this->Form->create($question, ['class' => 'js-import-question-form', 'url' => ['prefix' => 'admin', 'action' => 'import']]);
                        echo $this->Form->control('quiz_id', ['type' => 'hidden', 'value' => $this->request->getQuery('quiz_id')]);
                        echo $this->Form->control('id', ['type' => 'hidden']);
                        echo $this->Form->button(__d('backend', 'Importa'), ['class' => 'btn btn-sm btn-success']);
                        echo $this->Form->end();
                    ?>
                </td>
                <td>
                    <small>#<?= $question->id ?></small>
                    <?= $this->Text->highlight(h($question->question), $term) ?>
                </td>
                <td>
                    <dl class="dl-horizontal">
                        <?php foreach ($question->quiz_answers as $i => $answer) : ?>
                        <dt class="<?= $answer->is_correct ? 'text-success' : 'text-danger' ?>">
                            <?= $answer->is_correct ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>' ?>
                        </dt>
                        <dd class="<?= $answer->is_correct ? 'text-success' : 'text-danger' ?>">
                            <?php if ($question->type == 'true_or_false') : ?>
                                <?php if ($answer->answer == '__TRUE__') : ?>
                                    <?= __('Vero') ?>
                                <?php else: ?>
                                    <?= __('Falso') ?>
                                <?php endif ?>
                            <?php else: ?>
                                <?= $this->Text->highlight(h($answer->answer), $term) ?>
                            <?php endif ?>
                        </dd>
                        <?php endforeach ?>
                    </dl>
                </td>
                <td>
                    <?php
                        switch($question->complexity) {
                            case '1':
                            case '2':
                            case '3':
                                $complexityStr = __d('backend', 'Facile');
                            break;

                            case '4':
                            case '5':
                            case '6':
                            case '7':
                                $complexityStr = __d('backend', 'Media');
                            break;

                            case '8':
                            case '9':
                            case '10':
                                $complexityStr = __d('backend', 'Difficile');
                            break;
                        }

                        echo __d('backend', '{complexity}: {label}', ['complexity' => $question->complexity, 'label' => $complexityStr]);
                    ?>
                </td>
                <td>
                    <?php if ($question->quiz->type == 'default') : ?>
                        <i style="font-size:14px" class="fa fa-user"></i>
                    <?php else: ?>
                        <i style="color:#00adee;font-size:14px" class="fontello-brain"></i>
                    <?php endif ?>

                    <?= $question->quiz->title ?>
                </td>
                <td><?= $question->quiz->author->username ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php echo $this->element('pagination') ?>



<script type="text/javascript">
    $(function() {
        $(".select2").select2({
            width : "100%",
            theme : "bootstrap"
        });

        // Select2Tree
        // $(".select2").select2tree({
        //     width: "100%"
        // });
    });
</script>
