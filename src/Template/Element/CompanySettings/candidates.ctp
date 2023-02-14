<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Candidati ideali'));
//$this->assign('subtitle', __(''));
$this->assign('tab', 'candidates');
?>

<?php
echo $this->Form->input('profile_block.candidates', [
    'label'       => __('Candidati ideali'),
    'placeholder' => '',
    'help'        => ''
]);
