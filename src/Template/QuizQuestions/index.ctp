<div class="table-responsive">
    <table class="table table-hover font-size-md2">
        <thead>
            <tr>
                <th class="col-md-1 text-center"><?= __('Stato') ?></th>
                <th class="col-md-5"><?= __('Domanda') ?></th>
                <th class="col-md-3"><?= __('Risposte') ?></th>
                <th class="col-md-1"><?= __('Difficoltà') ?></th>
            </tr>
        </thead>
        <tbody >
            <?php if ($questions->isEmpty()) : ?>
            <tr>
                <td colspan="12">
                    <p class="text-muted text-center">
                        <?php echo __('Nessuna domanda trovata') ?>
                    </p>
                </td>
            </tr>
            <?php endif ?>

            <?php foreach ($questions as $question) : ?>
            <tr>
                <td>
                    <?php
                        if (!$question->is_banned) {
                            // Disabilita domanda (non verrà estratta per essere giocata)
                            echo $this->Form->create($question, [
                                'class' => 'quiz-question-visibility', 'url' => ['action' => 'visibility']
                            ]);
                            echo $this->Form->control('id');
                            echo $this->Form->control('is_published', [
                                'type'     => 'checkbox',
                                'label'    => __('Giocabile'),
                                'id'       => 'question-status-' .$question->id,
                                'class'    => 'js-question-status-checkbox',
                                'disabled' => $question->is_banned
                            ]);
                            echo $this->Form->end();
                        } else {
                            echo '<i class="text-danger fa fa-ban"></i> '. __('Bannata');
                        }
                    ?>
                </td>
                <td>
                    <small>#<?= $question->id ?></small>
                    <?= h($question->question) ?>

                    <?php if ($this->request->session()->read('Auth.User.type') == 'admin') : ?>
                    <br>
                    <a href="<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'QuizQuestions', 'action' => 'edit', $question->id]) ?>">
                        <i class="fa fa-edit"></i>
                        <?php echo __('Modifica') ?> (admin)
                    </a>
                    <?php endif ?>
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
                                <?= h($answer->answer) ?>
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
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
