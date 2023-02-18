<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Età'));
//$this->assign('subtitle', __('Permetti alle aziende d'));
$this->assign('tab', 'age');
?>

<?php
echo $this->Form->control('account_info.birthday', [
    'label'   => __('Nato il'),
    'help'    => __('NB: Spesso le aziende cerca candidati in base all\'età'),
    'empty'   => true,
    'minYear' => date('Y') - 100,
    'maxYear' => date('Y')
]);
echo $this->Form->control('account_info.show_birthday', [
    'label' => __('Mostrare nel tuo profilo'),
    'class' => 'pull-left'
]);
