<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:name', 'hobbies');
    $this->assign('tab:icon', 'fa fa-clock-o');
    $this->assign('tab:title', __('Hobbies e Interessi'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-hobbies {
        position:relative;
        top:0;

        min-height:200px;
    }
<?php $this->end() ?>


<div class="funjob-userprofile-hobbies">
    <div class="funjob-userprofile-hobbies-bg"></div>
    <div class="funjob-userprofile-hobbies-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->hobbies) ?>
    </div>
</div>
