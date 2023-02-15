<?php
    $this->extend('CompanyProfile/tabs/tab');
    $this->assign('tab:name', 'job-roles');
    $this->assign('tab:icon', 'fa fa-bullhorn');
    $this->assign('tab:title', __('Posizione lavorative aperte'));
?>

<div class="funjob-userprofile-job-roles">
    <div class="funjob-userprofile-job-roles-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->job_roles) ?>
    </div>
</div>
