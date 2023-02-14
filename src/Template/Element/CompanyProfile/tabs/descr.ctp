<?php
    $this->extend('CompanyProfile/tabs/tab');
    $this->assign('tab:name', 'descr');
    $this->assign('tab:icon', 'fa fa-info-circle');
    $this->assign('tab:title', __('Descrizione'));
?>

<div class="funjob-userprofile-film">
    <div class="funjob-userprofile-film-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->descr) ?>
    </div>
</div>
