<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:cat', 'job');
    $this->assign('tab:name', 'studies');
    $this->assign('tab:icon', 'fa fa-graduation-cap');
    $this->assign('tab:title', __('Studi'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-studies {
        position:relative;
        top:0;

        min-height:200px;
    }
<?php $this->end() ?>


<div class="funjob-userprofile-studies">
    <div class="funjob-userprofile-studies-bg"></div>
    <div class="funjob-userprofile-studies-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->studies) ?>
    </div>
</div>
