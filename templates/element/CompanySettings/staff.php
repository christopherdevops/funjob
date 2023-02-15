<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Staff'));
$this->assign('subtitle', __('personale aziendale'));
$this->assign('tab', 'staff');
?>

<?php
echo $this->Form->input('profile_block.staff', [
    'label'       => __('Staff'),
    'placeholder' => '',
    'help'        => ''
]);
