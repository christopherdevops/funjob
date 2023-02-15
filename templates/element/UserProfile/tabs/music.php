<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:name', 'music');
    $this->assign('tab:icon', 'fa fa-music');
    $this->assign('tab:title', __('Musica preferita'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-music {
        position:relative;
        top:0;
    }
<?php $this->end() ?>

<div class="funjob-userprofile-music">
    <div class="funjob-userprofile-music-bg"></div>
    <div class="funjob-userprofile-music-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->music) ?>
    </div>
</div>
