<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Profilo Job (professionale)'));
$this->assign('subtitle', __('Campi personali: mostrati nel tuo profilo'));
$this->assign('tab', 'job-profile');
?>

<?php
echo $this->Form->control('profile_block.studies', [
    'type'        => 'textarea',
    'label'       => __('Cronologia studi'),
    'placeholder' => __('2012 - Laurea ingegneria informatica presso .....'),
    'help'        => __('Questo campo potrebbe interessare alle aziende')
]);

echo $this->Form->control('profile_block.job_skills', [
    'type'        => 'textarea',
    'label'       => __('Specializzazioni (Competenze lavorative)'),
    'placeholder' => __('Per cosa vorresti essere contattato?'),
    'help'        => __('Questo campo potrebbe interessare alle aziende')
]);

echo $this->Form->control('profile_block.publications', [
    'type'        => 'textarea',
    'label'       => __('Pubblicazioni'),
    'placeholder' => __('Libri pubblicati, Video pubblicati'),
    'help'        => __('Libri pubblicati, Video pubblicati, Video presentazione, Tutorial')
]);
