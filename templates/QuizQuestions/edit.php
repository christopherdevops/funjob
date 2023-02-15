<?php
$this->extend('../Layout/TwitterBootstrap/dashboard');

$this->start('tb_actions');
?>
    <li><?=
    $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $quizQuestion->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $quizQuestion->id)]
    )
    ?>
    </li>
    <li><?= $this->Html->link(__('List Quiz Questions'), ['action' => 'index']) ?></li>
    <li><?= $this->Html->link(__('List Quizzes'), ['controller' => 'Quizzes', 'action' => 'index']) ?> </li>
    <li><?= $this->Html->link(__('New Quiz'), ['controller' => 'Quizzes', 'action' => 'add']) ?> </li>
    <li><?= $this->Html->link(__('List Quiz Answers'), ['controller' => 'QuizAnswers', 'action' => 'index']) ?> </li>
    <li><?= $this->Html->link(__('New Quiz Answer'), ['controller' => 'QuizAnswers', 'action' => 'add']) ?> </li>
<?php
$this->end();

$this->start('tb_sidebar');
?>
<ul class="nav nav-sidebar">
    <li><?=
    $this->Form->postLink(
        __('Delete'),
        ['action' => 'delete', $quizQuestion->id],
        ['confirm' => __('Are you sure you want to delete # {0}?', $quizQuestion->id)]
    )
    ?>
    </li>
    <li><?= $this->Html->link(__('List Quiz Questions'), ['action' => 'index']) ?></li>
    <li><?= $this->Html->link(__('List Quizzes'), ['controller' => 'Quizzes', 'action' => 'index']) ?> </li>
    <li><?= $this->Html->link(__('New Quiz'), ['controller' => 'Quizzes', 'action' => 'add']) ?> </li>
    <li><?= $this->Html->link(__('List Quiz Answers'), ['controller' => 'QuizAnswers', 'action' => 'index']) ?> </li>
    <li><?= $this->Html->link(__('New Quiz Answer'), ['controller' => 'QuizAnswers', 'action' => 'add']) ?> </li>
</ul>
<?php
$this->end();
?>
<?= $this->Form->create($quizQuestion); ?>
<fieldset>
    <legend><?= __('Edit {0}', ['Quiz Question']) ?></legend>
    <?php
    echo $this->Form->input('quiz_id', ['options' => $quizzes]);
    echo $this->Form->input('type');
    echo $this->Form->input('question');
    echo $this->Form->input('complexity');
    echo $this->Form->input('source_url');
    echo $this->Form->input('source_book_page');
    echo $this->Form->input('source_book_title');
    ?>
</fieldset>
<?= $this->Form->button(__("Save")); ?>
<?= $this->Form->end() ?>
