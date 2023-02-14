<?php
    $this->extend('CompanyProfile/tabs/tab');
    $this->assign('tab:name', 'mission');
    $this->assign('tab:icon', 'fa fa-bullseye');
    $this->assign('tab:title', __('Missione aziendale'));
?>

<div class="funjob-userprofile-mission">
    <div class="funjob-userprofile-mission-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->mission) ?>
    </div>
</div>
