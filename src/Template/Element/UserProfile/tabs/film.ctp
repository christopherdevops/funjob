<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:name', 'movies');
    $this->assign('tab:icon', 'fa fa-film');
    $this->assign('tab:title', __('Films preferiti'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-film {
        position:relative;
        top:0;

        min-height:200px;
    }
<?php $this->end() ?>


<div class="funjob-userprofile-film">
    <div class="funjob-userprofile-film-bg"></div>
    <div class="funjob-userprofile-film-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->films) ?>
    </div>
</div>
