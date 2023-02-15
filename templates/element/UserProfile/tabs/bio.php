<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:name', 'bio');
    $this->assign('tab:icon', 'fa fa-book');
    $this->assign('tab:title', __('Biografia'));
?>

<?php $this->append('css_head--inline') ?>
    .funjob-userprofile-bio {
        position:relative;
        top:0;

        min-height:200px;
    }

    .funjob-userprofile-bio-text {
        position:relative;
        top:0;
        z-index:1;
    }

    .funjob-userprofile-bio-bg {
        background:transparent url(https://www.colourbox.com/preview/15079524-biography-text-concept.jpg) no-repeat center center;
        background-size:cover;

        opacity:0.11;
        position:absolute;top:0;left:0;
        width:100%;height:100%;
        z-index:0;
    }
<?php $this->end() ?>

<div class="funjob-userprofile-bio font-size-md">
    <div class="funjob-userprofile-bio-bg"></div>
    <div class="funjob-userprofile-bio-text font-size-md3">
        <?= $this->Text->autoParagraph($User->profile_block->biography) ?>
    </div>
</div>
