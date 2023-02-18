<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Profilo Fun (informale)'));
$this->assign('subtitle', __('Campi personali: mostrati nel tuo profilo'));
$this->assign('tab', 'fun-profile');
?>

<?php
echo $this->Form->control('profile_block.biography', [
    'label'       => __('Biografia'),
    'placeholder' => __('Scrivici qualcosa su di te..'),
]);

echo $this->Form->control('profile_block.hobbies', [
    'label'       => __('Hobbies e Interessi'),
    'placeholder' => __('Scrivi cosa ti piace fare...'),
]);

echo $this->Form->control('profile_block.films', [
    'label'       => __('Film preferiti'),
    'placeholder' => __(''),
]);

echo $this->Form->control('profile_block.music', [
    'label'       => __('Musica preferita'),
    'placeholder' => __(''),
]);

echo $this->Form->control('profile_block.quotes', [
    'label'       => __('Citazioni preferite'),
    'placeholder' => __(''),
]);

echo $this->Form->control('profile_block.inspirations', [
    'label'       => __('Personaggi che ti inspirano'),
    'placeholder' => __(''),
]);
