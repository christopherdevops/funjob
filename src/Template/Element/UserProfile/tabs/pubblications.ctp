<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:cat', 'job');
    $this->assign('tab:name', 'pubblications');
    $this->assign('tab:icon', 'fa fa-bookmark');
    $this->assign('tab:title', __('Pubblicazioni'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-pubblications {
        position:relative;
        top:0;

        min-height:200px;
    }
<?php $this->end() ?>


<div class="funjob-userprofile-pubblications">
    <div class="funjob-userprofile-pubblications-bg"></div>
    <div class="funjob-userprofile-pubblications-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->publications) ?>
    </div>
</div>
