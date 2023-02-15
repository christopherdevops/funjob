<?php
    $this->extend('UserProfile/tabs/tab');
    $this->assign('tab:name', 'bigbrain');
    $this->assign('tab:icon', 'fa fontello-brain');
    $this->assign('tab:title', __('BigBrain'));
?>

<div class="funjob-userprofile-bigbrain">
    <div class="funjob-userprofile-bigbrain-bg"></div>
    <div class="funjob-userprofile-bigbrain-text font-size-md">
        <p class="font-size-md text-bold">
            <?php echo __('Collaboratore da: {date}', ['date' => $User->bigbrain_from]) ?>
        </p>
        <p class="font-size-md3">
            <?= $User->bigbrain_area ?>
        </p>
    </div>
</div>
