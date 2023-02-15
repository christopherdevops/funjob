<?php
    $this->extend('CompanyProfile/tabs/tab');
    $this->assign('tab:name', 'staff');
    $this->assign('tab:icon', 'fa fa-users');
    $this->assign('tab:title', __('Staff aziendale'));
?>

<div class="funjob-userprofile-staff">
    <div class="funjob-userprofile-staff-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->staff) ?>
    </div>
</div>
