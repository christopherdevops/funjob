<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Storia aziendale'));
//$this->assign('subtitle', __(''));
$this->assign('tab', 'history');
?>

<?php
echo $this->Form->control('profile_block.history', [
    'label'       => __('Storia'),
    'placeholder' => '',
    'help'        => ''
]);
