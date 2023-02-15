<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:cat', 'job');
    $this->assign('tab:name', 'job-skill');
    $this->assign('tab:icon', 'fa fa-briefcase fa--job');
    $this->assign('tab:title', __('Specializzazioni'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-job-skill {
        position:relative;
        top:0;

        min-height:200px;
    }
<?php $this->end() ?>


<div class="funjob-userprofile-job-skill">
    <div class="funjob-userprofile-job-skill-bg"></div>
    <div class="funjob-userprofile-job-skill-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->job_skills) ?>
    </div>
</div>
