<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:cat', 'fun');

    $this->assign('tab:name', 'quotes');
    $this->assign('tab:icon', 'fa fa-quote-left');
    $this->assign('tab:title', __('Citazioni preferite'));
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
        <?= $this->Text->autoParagraph($User->profile_block->quotes) ?>
    </div>
</div>
