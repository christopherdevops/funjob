<?php if ($Quiz->status !== 'published' && $canPublish) : ?>
    <p class="text-bold text-success text-center">
        <?= __('Bene, ci sono abbastanza domanda per procedere alla pubblicazione del quiz.') ?>
    </p>
<?php endif ?>

<?php if ($Quiz->status !== 'published' && !$canPublish) : ?>
    <p class="text-bold text-warning text-center">
        <?= __('Non puoi pubblicare ancora questo gioco: non ci sono abbastanza domande') ?>
    </p>
<?php endif ?>

<?php if ($Quiz->status == 'published') : ?>
    <p class="text-bold text-center text-success">
        <?= __('Il tuo quiz è giocabile dai nostri utenti') ?>
    </p>
<?php endif ?>

<?php
    echo $this->Form->create($Quiz, ['url' => ['prefix' => 'User', 'controller' => 'Quizzes', 'action' => 'status']]);
    //echo $this->Form->hidden('id', ['value' => $quiz->id]);

    $btnAttrs = ['class' => 'btn btn-success btn-block'];
    if (!$canPublish) {
        $btnAttrs['disabled'] = 'disabled';
    }

    // if ($Quiz->status != 'published') {
    //     echo $this->Form->control('status', ['type' => 'hidden', 'value' => 'published']);
    //     echo $this->Form->button(
    //         __('{icon} Pubblica', ['icon' => '<i class="fa fa-fw fa-send-o" aria-hidden="true"></i>']),
    //         $btnAttrs
    //     );
    // } else {
    //     echo $this->Form->control('status', ['type' => 'hidden', 'value' => 'hidden']);
    //     echo $this->Form->button(
    //         __('{icon} Nascondi', ['icon' => '<i class="fa fa-fw fa-trash" aria-hidden="true"></i>']),
    //         ['class' => 'btn btn-danger btn-block']
    //     );
    // }


    if ($Quiz->status != 'published') {
        echo $this->Form->control('status', ['type' => 'hidden', 'value' => 'published']);
        echo $this->Form->button(
            __('{icon} Pubblica', ['icon' => '<i class="fa fa-fw fa-send-o" aria-hidden="true"></i>']),
            $btnAttrs
        );
    }

    echo '<br/>';
    echo $this->Form->control('status', ['type' => 'hidden', 'value' => 'deleted']);
    echo $this->Form->button(
        __('{icon} Elimina', ['icon' => '<i class="fa fa-fw fa-trash" aria-hidden="true"></i>']),
        ['class' => 'btn btn-danger btn-block']
    );

    echo $this->Form->end();
?>
