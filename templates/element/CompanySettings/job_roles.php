<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Posizioni lavorative aperte'));
$this->assign('subtitle', __(''));
$this->assign('tab', 'job_roles');
?>

<?php
echo $this->Form->control('profile_block.job_roles', [
    'label'       => __('Posizioni ricercate'),
    'placeholder' => '',
    'help'        => ''
]);
