<?php
    $this->extend('CompanyProfile/tabs/tab');
    $this->assign('tab:name', 'candidates');
    $this->assign('tab:icon', 'fa fa-lightbulb-o');
    $this->assign('tab:title', __('Candidati ideali'));
?>

<div class="funjob-userprofile-candidates">
    <div class="funjob-userprofile-candidates-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->candidates) ?>
    </div>
</div>
