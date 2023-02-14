<?php
    $this->extend('CompanyProfile/tabs/tab');
    $this->assign('tab:name', 'history');
    $this->assign('tab:icon', 'fa fa-globe');
    $this->assign('tab:title', __('Storia aziendale'));
?>

<div class="funjob-userprofile-history">
    <div class="funjob-userprofile-history-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->history) ?>
    </div>
</div>
