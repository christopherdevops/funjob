<?php
    $this->assign('title', __('Modifica domanda'));
    $this->Breadcrumbs
        ->add($Question->quiz->title, ['prefix' => false, 'controller' => 'Quizzes', 'action' => 'edit', $Question->quiz->id])
        ->add(__('Modifica domanda'))
?>

<?php
    echo $this->Form->create($Question);
    echo $this->Form->control('id');

    echo $this->Form->control('question', [
        'label' => __d('backend', 'Domanda')
    ]);

    if ($Question->type != 'true_or_false') {
        foreach ($Question->quiz_answers as $i => $Answer) {
            echo $this->Form->control(sprintf('quiz_answers.%d.id', $i), ['value' => $Answer->id, 'type' => 'hidden']);
            echo $this->Form->control(sprintf('quiz_answers.%d.is_correct', $i), ['value' => $Answer->is_correct, 'type' => 'hidden']);
            echo $this->Form->control(sprintf('quiz_answers.%d.answer', $i), [
                'prepend' => $Answer->is_correct ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-remove text-danger"></i>',
                'label'   => false,
                'default' => $Answer->answer
            ]);
        }
    } else {
        echo '<div class="alert alert-info">'. __('Non puoi modificare le risposte di tipologia VERO/FALSO'). '</div>';
    }

    echo $this->Form->control('is_banned', [
        'label' => __('Ban domanda'),
        'help'  => (
            __('La domanda sarà esclusa dal sistema: gioco, export, e non potrà essere ripristinata dall\'utente') .
            '<br> <span class="text-danger"><i class="fa fa-warning text-danger"></i> '.
                __('Assicurarsi che ci sia un numero di domande sufficiente per non compromettere il gioco') .
            '</span>'
        )
    ]);

    echo $this->Form->button(__('Modifica'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>
