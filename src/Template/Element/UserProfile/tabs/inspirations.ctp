<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:cat', 'fun');

    $this->assign('tab:name', 'inspirations');
    $this->assign('tab:icon', 'fa fa-users');
    $this->assign('tab:title', __('Personaggi che ti hanno inspirato'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-quotes {
        position:relative;
        top:0;

        min-height:200px;
    }
<?php $this->end() ?>


<div class="funjob-userprofile-quotes">
    <div class="funjob-userprofile-quotes-bg"></div>
    <div class="funjob-userprofile-quotes-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->inspirations) ?>
    </div>
</div>
