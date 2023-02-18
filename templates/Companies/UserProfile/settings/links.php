<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Collegamenti'));
$this->assign('subtitle', __('Links alle tue pagine web (visualizzati nel tuo profilo)'));
$this->assign('tab', 'links');
?>

<?php
echo $this->Form->control('user_profile_box.links', [
    'label'       => __('Links'),
    'placeholder' => __('Nome sito http://www....'),
    'help'        => __('I links alle tue pagine web (uno per linea)')
]);
