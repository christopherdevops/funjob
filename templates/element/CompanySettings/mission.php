<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Missione aziendale'));
$this->assign('subtitle', __(''));
$this->assign('tab', 'mission');
?>

<?php
echo $this->Form->control('profile_block.mission', [
    'label'       => __('Missione'),
    'placeholder' => '',
    'help'        => ''
]);
